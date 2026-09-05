# Guest Demo Student Dashboard — Design

**Date:** 2026-08-11
**Status:** Approved, pending implementation

## Problem

A visitor arriving at fluencefrancaise.fr cannot see what the student portal looks like without
creating an account. There is no way to evaluate the product before signing up.

## Goal

Let an unauthenticated visitor browse a read-only preview of the student portal showing two pages —
Courses and Exam Prep — populated with the real published catalogue. Every interactive control is
gated: clicking Enroll, View Details, or a locked navigation item opens a prompt to log in or
register. Nothing in the demo mutates data, and no private data is exposed.

## Decisions

These were settled before design and are not open questions:

| Decision | Choice |
|---|---|
| Which pages | Catalogue style — mirrors `BrowseCourses.vue` / `BrowseExamPrep.vue`, which is where Enroll exists |
| Data source | Real published records via a new public read-only API |
| Gating UX | Modal prompt, then Login / Register |
| Entry points | Bottom of login page, public site header CTA, direct URL |

## Constraint discovered during exploration

`StudentController::browseCourses()` (`app/Http/Controllers/Api/StudentController.php:383`) does
`select('courses.*')` and returns the whole model — including `course_json_content`, which holds the
actual lesson content — and does not filter on `course_is_active`. That is acceptable behind
`auth:sanctum`. It must not be replicated on a public endpoint.

The demo API therefore returns an explicit field whitelist and filters on the active flag.

## Architecture

### Backend

New controller `app/Http/Controllers/Api/DemoController.php` with two methods, `courses()` and
`examPreps()`.

Routes in `routes/api.php`, placed next to the existing public `settings/public` route:

```php
Route::middleware('throttle:30,1')->prefix('demo')->group(function () {
    Route::get('courses',    [DemoController::class, 'courses']);
    Route::get('exam-preps', [DemoController::class, 'examPreps']);
});
```

Both methods:

1. Filter to published records only — `course_is_active = true` / `exam_prep_is_active = true`.
2. Reuse the `class_types.display_order` left join from `browseCourses()` so ordering matches what
   a real student sees.
3. `paginate(10)`, matching the browse endpoints.
4. Map each record to an explicit whitelist before returning.

Response envelope matches the rest of the API (`success`, `data`, `message`), so the frontend
pagination handling already written for browse pages works unchanged.

**Whitelisted fields, courses:**

| Output key | Source column |
|---|---|
| `id` | `id` |
| `title` | `course_title` |
| `subtitle` | `course_subtitle` |
| `description` | `course_description` |
| `image_url` | `course_image`, prefixed `/storage/` |
| `level` | `course_level` |
| `category` | `course_category` |
| `language` | `course_language` |

Exam prep uses the equivalent `exam_prep_*` columns.

**Never returned:** `course_json_content`, `exam_prep_json_content`, `custom_url`,
`custom_url_target`, `display_order`, timestamps, or any enrollment, progress, payment or user data.

The mapping mirrors what `stores/course.js:17-26` already does client-side, moved server-side so raw
model fields never leave the server.

Rate limited at 30 requests/minute because the endpoints are unauthenticated.

### Frontend

| New file | Purpose |
|---|---|
| `resources/js/layouts/DemoLayout.vue` | Visual twin of `StudentLayout` |
| `resources/js/pages/demo/Courses.vue` | Catalogue cards with gated Enroll / View Details |
| `resources/js/pages/demo/ExamPrep.vue` | Same, for exam prep |
| `resources/js/components/DemoGateModal.vue` | "Create an account to continue" dialog |
| `resources/js/composables/useDemoGate.js` | Shared gating state |

`DemoLayout.vue` reproduces `StudentLayout.vue`'s shell — the `#0055A4` sidebar, the topbar, the
content area — with three changes:

- Nav renders Dashboard, Homework and Account in the locked style that already exists at
  `StudentLayout.vue:24-34` for the payment-pending case: greyed, `cursor-not-allowed`, `Lock` icon.
  Only Courses and Exam Prep are live links.
- The topbar shows "Guest — Demo preview" in place of the user's name, and the Logout button is
  replaced by **Create free account**.
- A persistent banner sits above the content: "You're viewing a demo of the student portal" with
  Login and Register buttons.

`DemoLayout` does not call `useAuthStore`, so it never triggers the auth-loading path in the router
guard, and it makes none of the authenticated calls `StudentLayout` makes on mount
(`homework/pending-count`, `student-portal/maintenance-status`).

Router additions in `resources/js/router/index.js`:

```js
{
  path: '/demo',
  name: 'DemoLayout',
  component: () => import('../layouts/DemoLayout.vue'),
  redirect: '/demo/courses',
  meta: { requiresAuth: false },
  children: [
    { path: 'courses',   name: 'DemoCourses',   component: () => import('../pages/demo/Courses.vue') },
    { path: 'exam-prep', name: 'DemoExamPrep',  component: () => import('../pages/demo/ExamPrep.vue') },
  ],
}
```

The catch-all `/:slug` PublicPage route is declared last, so `/demo` matches first. The existing
trailing-slash normalisation in the guard applies unchanged.

One guard addition: an authenticated student landing on `/demo` is redirected to
`/student/dashboard` — they have the real thing.

### Entry points

- `pages/auth/Login.vue` — link below the form: "Curious? Preview the student dashboard →"
- `components/PublicHeader.vue` — a "See Demo" CTA
- Direct URL `/demo/`

## Data flow

```
Guest → /demo/courses/
      → DemoLayout mounts (no auth calls)
      → Courses.vue: GET /api/demo/courses  (no token)
      → DemoController: active-only + whitelist
      → cards render
      → any click (Enroll | View Details | card | locked nav)
      → useDemoGate.open()
      → DemoGateModal → Login or Register
```

There is no write path in the demo, so gating is purely a frontend concern. The backend exposes no
mutating demo endpoint to defend.

## Key trade-off: fork rather than parameterise

`pages/demo/Courses.vue` is a fork of `BrowseCourses.vue` rather than the same component with a
`readonly` prop.

The prop approach is DRY-er, but it threads conditionals through the code path paying students use
to enrol, and this repository has no test coverage to catch a regression there. The fork costs
roughly 200 lines of duplicated markup and carries a risk of visual drift over time. Given zero
tests, duplication is the cheaper mistake.

Extracting a shared `CourseCard.vue` consumed by both pages is the correct follow-up once there is
test coverage to verify the extraction against. It is deliberately out of scope here.

`BrowseCourses.vue` also imports `useCourseStore`, which calls authenticated endpoints, and its
handlers push to `/student/*` routes — so the fork diverges in behaviour, not only in styling.

## Error handling

- API failure in a demo page: inline message "Unable to load the demo catalogue" plus a retry
  button. No toast — the toast store is oriented to the logged-in shell.
- Empty catalogue (no active records): empty-state card reading "No courses available yet" with a
  Register call to action, rather than a bare empty grid.
- 429 from throttling: same inline error path.
- Missing `course_image`: existing placeholder behaviour is retained.

## Testing

`tests/Feature/DemoApiTest.php`:

1. `GET /api/demo/courses` returns 200 without any token.
2. The payload contains only whitelisted keys — asserted by diffing actual keys against the allowed
   list, so a future field addition fails loudly rather than leaking.
3. Records with `course_is_active = false` are absent.
4. Exceeding 30 requests/minute returns 429.
5. Equivalents for `/api/demo/exam-preps`.

**Caveat:** the repository has no `database/` directory — no migrations, factories or seeders — so
`RefreshDatabase` has nothing to build. These tests can only run against a hand-seeded local
database until schema exists in the repo. They will be written to be ready, but cannot be claimed to
pass on a clean checkout.

Manual verification: visit `/demo/` logged out, confirm both pages load real published content,
confirm every interactive control opens the modal, confirm a logged-in student is redirected away.

## Out of scope

- Extracting a shared card component.
- Demo versions of Dashboard, Homework or Account — those nav items render locked.
- Any demo of enrolled-state data such as progress bars.
- Server-side rendering or SEO for the demo route.
