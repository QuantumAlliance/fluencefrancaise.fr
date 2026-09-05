# Guest Demo Student Dashboard Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let an unauthenticated visitor browse a read-only preview of the student portal — Courses and Exam Prep, populated from the real published catalogue — where every action opens a login/register prompt.

**Architecture:** Two public throttled Laravel endpoints return an explicitly whitelisted projection of active courses and exam preps. A parallel Vue route tree at `/demo` renders a visual twin of the student shell using those endpoints. All interactive controls call a shared gating composable that opens a modal instead of performing the action.

**Tech Stack:** Laravel 10 (PHP 8.3), Vue 3 `<script setup>`, Vue Router 4, Pinia 2, Tailwind CSS 3, Vite 8, PHPUnit 10, lucide-vue-next.

## Global Constraints

- **Commits must have no `Co-Authored-By` trailer and no Claude attribution.** Jakuan is the sole author. This overrides any default commit-message convention.
- Brand colours are exactly `#0055A4` (primary) and `#003d7a` (hover/active). Copy them verbatim; do not substitute Tailwind palette names.
- No new npm or composer dependencies. Everything needed is already installed.
- Vue components use `<script setup>`, matching every existing component in `resources/js`.
- Icons come from `lucide-vue-next`, matching `StudentLayout.vue`.
- The router rewrites every path to a trailing slash (`router/index.js:298-306`). Internal `router-link` targets are written without the trailing slash, exactly as existing links do.
- The demo must never expose `course_json_content`, `exam_prep_json_content`, `custom_url`, `custom_url_target`, `display_order`, timestamps, or any user, enrollment, payment or progress data.
- Run PHP via `php` (on PATH via Laragon, PHP 8.3.30). Run tests with `php artisan test` or `vendor/bin/phpunit`.

## Testing Reality — read before starting

This repository has **no `database/` directory** — no migrations, factories or seeders — and `phpunit.xml:24-25` has the sqlite test connection commented out. Any test using `RefreshDatabase` or a factory **cannot run**.

The plan works within that constraint:

- **Task 1 is fully TDD.** The security-critical piece — the field whitelist — is extracted into a pure mapper class that operates on in-memory Eloquent models. It needs no database, so its tests genuinely run and genuinely fail first.
- **Task 2's HTTP feature tests are written but marked skipped**, with the exact reason and the command to enable them once schema exists. They are verified manually against the dev database instead.
- **Frontend tasks have no automated tests** — there is no vitest/jest in `package.json` and adding a JS test runner is out of scope. They are verified by `npm run build` plus explicit manual browser checks.

Do not claim a test passed without running it and reading the output.

## File Structure

| File | Responsibility |
|---|---|
| `app/Support/DemoCatalogMapper.php` | **Create.** Pure whitelist projection of Course/ExamPrep to demo-safe arrays. The single security boundary. |
| `tests/Unit/DemoCatalogMapperTest.php` | **Create.** Proves the whitelist is exact and lesson content never escapes. |
| `app/Http/Controllers/Api/DemoController.php` | **Create.** Two read-only actions; querying and pagination only. |
| `tests/Feature/DemoApiTest.php` | **Create.** HTTP-level contract; skipped until schema exists. |
| `routes/api.php` | **Modify.** Register the throttled public `demo` prefix. |
| `resources/js/composables/useDemoGate.js` | **Create.** Shared gate state — the only place gating logic lives. |
| `resources/js/components/DemoGateModal.vue` | **Create.** The prompt UI. |
| `resources/js/layouts/DemoLayout.vue` | **Create.** Guest shell: sidebar, topbar, banner, gate modal host. |
| `resources/js/pages/demo/Courses.vue` | **Create.** Course catalogue cards, all actions gated. |
| `resources/js/pages/demo/ExamPrep.vue` | **Create.** Exam prep catalogue cards, all actions gated. |
| `resources/js/router/index.js` | **Modify.** `/demo` route tree + logged-in redirect. |
| `resources/js/pages/auth/Login.vue` | **Modify.** Demo link below the form. |
| `resources/js/components/PublicHeader.vue` | **Modify.** "See Demo" CTA, desktop and mobile. |

---

### Task 1: Demo catalogue mapper (the security boundary)

**Files:**
- Create: `app/Support/DemoCatalogMapper.php`
- Test: `tests/Unit/DemoCatalogMapperTest.php`

**Interfaces:**
- Consumes: `App\Models\Course`, `App\Models\ExamPrep` (existing).
- Produces: `DemoCatalogMapper::mapCourse(Course $course): array`, `DemoCatalogMapper::mapExamPrep(ExamPrep $examPrep): array`, and the constants `DemoCatalogMapper::COURSE_KEYS` / `DemoCatalogMapper::EXAM_PREP_KEYS` (both `array<int,string>`). Task 2 calls these.

**Context you need:** `StudentController::browseCourses()` at `app/Http/Controllers/Api/StudentController.php:383` returns `select('courses.*')` — the whole model including `course_json_content`, the actual lesson text. That is why this mapper exists. The output field names match what `resources/js/stores/course.js:17-26` already produces client-side, so the frontend card markup needs no new shape.

- [ ] **Step 1: Write the failing test**

Create `tests/Unit/DemoCatalogMapperTest.php`:

```php
<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\ExamPrep;
use App\Support\DemoCatalogMapper;
use Tests\TestCase;

class DemoCatalogMapperTest extends TestCase
{
    private function makeCourse(): Course
    {
        $course = new Course([
            'course_title' => 'French A1 Foundations',
            'course_subtitle' => 'Start here',
            'course_description' => 'An introduction to French.',
            'course_category' => 'grammar',
            'course_language' => 'French',
            'course_level' => 'Beginner',
            'course_json_content' => '{"secret":"TOP_SECRET_LESSON_BODY"}',
            'course_image' => 'courses/a1.png',
            'course_is_active' => true,
            'custom_url' => 'https://internal.example.com/private',
            'custom_url_target' => '_blank',
            'display_order' => 3,
        ]);
        $course->id = 42;

        return $course;
    }

    public function test_map_course_returns_exactly_the_whitelisted_keys(): void
    {
        $mapped = DemoCatalogMapper::mapCourse($this->makeCourse());

        $this->assertSame(DemoCatalogMapper::COURSE_KEYS, array_keys($mapped));
    }

    public function test_map_course_copies_the_public_values(): void
    {
        $mapped = DemoCatalogMapper::mapCourse($this->makeCourse());

        $this->assertSame(42, $mapped['id']);
        $this->assertSame('French A1 Foundations', $mapped['title']);
        $this->assertSame('Start here', $mapped['subtitle']);
        $this->assertSame('An introduction to French.', $mapped['description']);
        $this->assertSame('Beginner', $mapped['level']);
        $this->assertSame('grammar', $mapped['category']);
        $this->assertSame('French', $mapped['language']);
    }

    public function test_map_course_never_leaks_private_fields(): void
    {
        $encoded = json_encode(DemoCatalogMapper::mapCourse($this->makeCourse()));

        $this->assertStringNotContainsString('TOP_SECRET_LESSON_BODY', $encoded);
        $this->assertStringNotContainsString('internal.example.com', $encoded);
    }

    public function test_map_course_builds_a_storage_image_url(): void
    {
        $mapped = DemoCatalogMapper::mapCourse($this->makeCourse());

        $this->assertSame('/storage/courses/a1.png', $mapped['image_url']);
    }

    public function test_map_course_returns_null_image_when_absent(): void
    {
        $course = $this->makeCourse();
        $course->course_image = null;

        $this->assertNull(DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_course_strips_trailing_slashes_from_image_path(): void
    {
        $course = $this->makeCourse();
        $course->course_image = 'courses/a1.png//';

        $this->assertSame('/storage/courses/a1.png', DemoCatalogMapper::mapCourse($course)['image_url']);
    }

    public function test_map_exam_prep_returns_exactly_the_whitelisted_keys(): void
    {
        $examPrep = new ExamPrep([
            'exam_prep_title' => 'TEF Written',
            'exam_prep_subtitle' => 'Written module',
            'exam_prep_description' => 'Prepare for the TEF written exam.',
            'exam_prep_category' => 'written',
            'exam_prep_language' => 'French',
            'exam_prep_level' => 'B2',
            'exam_prep_json_content' => '{"secret":"TOP_SECRET_EXAM_BODY"}',
            'exam_prep_image' => 'exams/tef.png',
            'exam_prep_is_active' => true,
        ]);
        $examPrep->id = 7;

        $mapped = DemoCatalogMapper::mapExamPrep($examPrep);

        $this->assertSame(DemoCatalogMapper::EXAM_PREP_KEYS, array_keys($mapped));
        $this->assertSame('TEF Written', $mapped['title']);
        $this->assertSame('/storage/exams/tef.png', $mapped['image_url']);
        $this->assertStringNotContainsString('TOP_SECRET_EXAM_BODY', json_encode($mapped));
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `php artisan test --filter=DemoCatalogMapperTest`

Expected: FAIL — `Class "App\Support\DemoCatalogMapper" not found`.

- [ ] **Step 3: Write the minimal implementation**

Create `app/Support/DemoCatalogMapper.php`:

```php
<?php

namespace App\Support;

use App\Models\Course;
use App\Models\ExamPrep;

/**
 * Projects catalogue records down to the fields that are safe to expose publicly.
 *
 * This is the single security boundary for the guest demo. The authenticated browse
 * endpoints return whole models (including lesson content); nothing here may.
 */
final class DemoCatalogMapper
{
    public const COURSE_KEYS = [
        'id', 'title', 'subtitle', 'description', 'image_url', 'level', 'category', 'language',
    ];

    public const EXAM_PREP_KEYS = self::COURSE_KEYS;

    public static function mapCourse(Course $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->course_title,
            'subtitle' => $course->course_subtitle,
            'description' => $course->course_description,
            'image_url' => self::storageUrl($course->course_image),
            'level' => $course->course_level,
            'category' => $course->course_category,
            'language' => $course->course_language,
        ];
    }

    public static function mapExamPrep(ExamPrep $examPrep): array
    {
        return [
            'id' => $examPrep->id,
            'title' => $examPrep->exam_prep_title,
            'subtitle' => $examPrep->exam_prep_subtitle,
            'description' => $examPrep->exam_prep_description,
            'image_url' => self::storageUrl($examPrep->exam_prep_image),
            'level' => $examPrep->exam_prep_level,
            'category' => $examPrep->exam_prep_category,
            'language' => $examPrep->exam_prep_language,
        ];
    }

    private static function storageUrl(?string $path): ?string
    {
        $path = is_string($path) ? rtrim(trim($path), '/') : '';

        return $path === '' ? null : '/storage/' . ltrim($path, '/');
    }
}
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `php artisan test --filter=DemoCatalogMapperTest`

Expected: PASS, 8 tests. If `test_map_course_returns_exactly_the_whitelisted_keys` fails on key *order*, the array literal order in `mapCourse` must match `COURSE_KEYS` — fix the implementation, not the test. The order assertion is deliberate: it makes any future field addition fail loudly rather than leak silently.

- [ ] **Step 5: Commit**

```bash
git add app/Support/DemoCatalogMapper.php tests/Unit/DemoCatalogMapperTest.php
git commit -m "Add demo catalogue mapper with public field whitelist"
```

---

### Task 2: Public demo API endpoints

**Files:**
- Create: `app/Http/Controllers/Api/DemoController.php`
- Modify: `routes/api.php` (add after the `settings/public` route at line 61)
- Test: `tests/Feature/DemoApiTest.php`

**Interfaces:**
- Consumes: `DemoCatalogMapper::mapCourse()`, `DemoCatalogMapper::mapExamPrep()`, `DemoCatalogMapper::COURSE_KEYS` from Task 1.
- Produces: `GET /api/demo/courses` and `GET /api/demo/exam-preps`. Both return
  `{ success: bool, data: { data: array, current_page: int, last_page: int, per_page: int, total: int }, message: string }`.
  Tasks 5 and 6 consume this shape.

**Context you need:** The category ordering join is copied from `StudentController::browseCourses()` (`app/Http/Controllers/Api/StudentController.php:386-393`) and `StudentExamPrepController::browse()` (`app/Http/Controllers/Api/StudentExamPrepController.php:146-153`) so the demo ordering matches what students see. Unlike those methods, the demo filters on the active flag. `LengthAwarePaginator::through()` maps items while preserving pagination metadata.

- [ ] **Step 1: Write the feature test**

Create `tests/Feature/DemoApiTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Support\DemoCatalogMapper;
use Tests\TestCase;

class DemoApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // This repository has no database/ directory — no migrations, factories or seeders —
        // and phpunit.xml has the sqlite test connection commented out. These tests describe
        // the intended HTTP contract and will run once schema exists in the repo.
        // To enable: add migrations, uncomment DB_CONNECTION/DB_DATABASE in phpunit.xml,
        // then delete this markTestSkipped call.
        $this->markTestSkipped('No test database: repository has no migrations. See plan Task 2.');
    }

    public function test_courses_endpoint_is_reachable_without_authentication(): void
    {
        $this->getJson('/api/demo/courses')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_courses_endpoint_returns_only_whitelisted_keys(): void
    {
        $response = $this->getJson('/api/demo/courses')->assertOk();

        foreach ($response->json('data.data') as $course) {
            $this->assertSame(DemoCatalogMapper::COURSE_KEYS, array_keys($course));
        }
    }

    public function test_courses_endpoint_excludes_inactive_records(): void
    {
        $ids = collect($this->getJson('/api/demo/courses')->json('data.data'))->pluck('id');

        $inactiveIds = \App\Models\Course::where('course_is_active', false)->pluck('id');

        $this->assertEmpty($ids->intersect($inactiveIds));
    }

    public function test_exam_preps_endpoint_is_reachable_without_authentication(): void
    {
        $this->getJson('/api/demo/exam-preps')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_exam_preps_endpoint_returns_only_whitelisted_keys(): void
    {
        $response = $this->getJson('/api/demo/exam-preps')->assertOk();

        foreach ($response->json('data.data') as $examPrep) {
            $this->assertSame(DemoCatalogMapper::EXAM_PREP_KEYS, array_keys($examPrep));
        }
    }

    public function test_demo_endpoints_are_rate_limited(): void
    {
        foreach (range(1, 30) as $ignored) {
            $this->getJson('/api/demo/courses')->assertOk();
        }

        $this->getJson('/api/demo/courses')->assertStatus(429);
    }
}
```

- [ ] **Step 2: Run the test to confirm it is skipped, not failing**

Run: `php artisan test --filter=DemoApiTest`

Expected: 6 skipped, 0 failures. A skip here is the honest outcome — do not delete the tests to make output look clean.

- [ ] **Step 3: Write the controller**

Create `app/Http/Controllers/Api/DemoController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamPrep;
use App\Support\DemoCatalogMapper;
use Illuminate\Http\JsonResponse;

/**
 * Public, read-only catalogue for the logged-out demo dashboard.
 *
 * Every response goes through DemoCatalogMapper. Never return a model directly.
 */
class DemoController extends Controller
{
    private const PER_PAGE = 10;

    public function courses(): JsonResponse
    {
        $courses = Course::leftJoin('class_types', function ($join) {
                $join->on('courses.course_category', '=', 'class_types.class_name')
                     ->orOn('courses.course_category', '=', 'class_types.name');
            })
            ->select('courses.*')
            ->where('courses.course_is_active', true)
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('courses.id', 'asc')
            ->paginate(self::PER_PAGE)
            ->through(fn (Course $course) => DemoCatalogMapper::mapCourse($course));

        return response()->json([
            'success' => true,
            'data' => $courses,
            'message' => 'Demo courses',
        ]);
    }

    public function examPreps(): JsonResponse
    {
        $examPreps = ExamPrep::leftJoin('class_types', function ($join) {
                $join->on('exam_preps.exam_prep_category', '=', 'class_types.class_name')
                     ->orOn('exam_preps.exam_prep_category', '=', 'class_types.name');
            })
            ->select('exam_preps.*')
            ->where('exam_preps.exam_prep_is_active', true)
            ->orderBy('class_types.display_order', 'asc')
            ->orderBy('exam_preps.id', 'asc')
            ->paginate(self::PER_PAGE)
            ->through(fn (ExamPrep $examPrep) => DemoCatalogMapper::mapExamPrep($examPrep));

        return response()->json([
            'success' => true,
            'data' => $examPreps,
            'message' => 'Demo exam preps',
        ]);
    }
}
```

- [ ] **Step 4: Register the routes**

In `routes/api.php`, add `DemoController` to the existing `use App\Http\Controllers\Api\{...}` import group (the block starting at line 5), then insert immediately after the `settings/public` route on line 61:

```php
// Demo Routes (PUBLIC, read-only — powers the logged-out demo dashboard)
Route::middleware('throttle:30,1')->prefix('demo')->group(function () {
    Route::get('courses', [DemoController::class, 'courses']);
    Route::get('exam-preps', [DemoController::class, 'examPreps']);
});
```

- [ ] **Step 5: Verify the routes are registered**

Run: `php artisan route:list --path=demo`

Expected: two GET rows, `api/demo/courses` and `api/demo/exam-preps`, each showing the `throttle:30,1` middleware and no `auth:sanctum`.

- [ ] **Step 6: Verify manually against the dev database**

Start MySQL in Laragon, then in one terminal run `php artisan serve --port=8001` and in another:

```bash
curl -s http://127.0.0.1:8001/api/demo/courses | head -c 600
```

Expected: `{"success":true,...}` with objects containing only `id, title, subtitle, description, image_url, level, category, language`. Confirm by eye that no `course_json_content` or `custom_url` appears. If the database is empty this returns an empty `data.data` array — that is a valid result, not a failure.

- [ ] **Step 7: Commit**

```bash
git add app/Http/Controllers/Api/DemoController.php routes/api.php tests/Feature/DemoApiTest.php
git commit -m "Add public read-only demo catalogue endpoints"
```

---

### Task 3: Gate composable and prompt modal

**Files:**
- Create: `resources/js/composables/useDemoGate.js`
- Create: `resources/js/components/DemoGateModal.vue`

**Interfaces:**
- Consumes: nothing from earlier tasks.
- Produces: `useDemoGate()` returning `{ isOpen: Ref<boolean>, reason: Ref<string>, open(reason?: string): void, close(): void }`. Tasks 4, 5 and 6 all call `open()`. `DemoGateModal` is a no-prop component that renders itself when `isOpen` is true.

**Context you need:** State is declared at module scope, outside the composable function, so every importer shares one instance — the layout hosts the modal while the pages trigger it. This is the same pattern as a Pinia store but without registering one for two booleans.

- [ ] **Step 1: Write the composable**

Create `resources/js/composables/useDemoGate.js`:

```js
import { ref } from 'vue'

// Module-scope state: every component that imports this shares one gate.
const isOpen = ref(false)
const reason = ref('')

export function useDemoGate() {
  const open = (nextReason = 'to continue') => {
    reason.value = nextReason
    isOpen.value = true
  }

  const close = () => {
    isOpen.value = false
    reason.value = ''
  }

  return { isOpen, reason, open, close }
}
```

- [ ] **Step 2: Write the modal component**

Create `resources/js/components/DemoGateModal.vue`:

```vue
<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-[60] flex items-center justify-center px-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="demo-gate-title"
  >
    <div class="absolute inset-0 bg-black/50" @click="close"></div>

    <div class="relative bg-white rounded-2xl shadow-xl p-8 w-full max-w-md text-center">
      <button
        type="button"
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
        aria-label="Close"
        @click="close"
      >
        <X class="w-5 h-5" />
      </button>

      <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#0055A4]/10 flex items-center justify-center">
        <Lock class="w-8 h-8 text-[#0055A4]" />
      </div>

      <h3 id="demo-gate-title" class="text-xl font-bold text-gray-800 mb-2">
        Create an account {{ reason }}
      </h3>
      <p class="text-gray-600 mb-6">
        You're viewing a demo of the student portal. Sign up free to enrol and start learning.
      </p>

      <div class="space-y-3">
        <router-link
          to="/register"
          class="block w-full bg-[#0055A4] hover:bg-[#003d7a] text-white font-bold py-3 px-4 rounded-lg transition-colors"
          @click="close"
        >
          Create free account
        </router-link>
        <router-link
          to="/login"
          class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-4 rounded-lg transition-colors"
          @click="close"
        >
          I already have an account
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Lock, X } from 'lucide-vue-next'
import { useDemoGate } from '../composables/useDemoGate'

const { isOpen, reason, close } = useDemoGate()
</script>
```

- [ ] **Step 3: Verify it compiles**

Run: `npm run build`

Expected: build succeeds. The pre-existing "Some chunks are larger than 500 kB" warning is unrelated — ignore it. Any `Failed to resolve import` error means a path is wrong; fix before continuing.

- [ ] **Step 4: Commit**

```bash
git add resources/js/composables/useDemoGate.js resources/js/components/DemoGateModal.vue
git commit -m "Add demo gate composable and account prompt modal"
```

---

### Task 4: Demo layout shell

**Files:**
- Create: `resources/js/layouts/DemoLayout.vue`

**Interfaces:**
- Consumes: `useDemoGate()` from Task 3, `DemoGateModal` from Task 3, `useSettingsStore` from `resources/js/stores/settings.js` (existing).
- Produces: the `/demo` layout with a `<router-view />` outlet. Tasks 5 and 6 render inside it.

**Context you need:** This mirrors `resources/js/layouts/StudentLayout.vue` — read it first. Two deliberate differences: it must **not** import `useAuthStore`, and it must **not** call `/api/student/homework/pending-count` or `/api/student-portal/maintenance-status`, both of which require a token and would 401 for a guest. The locked nav styling is lifted from `StudentLayout.vue:24-34`.

- [ ] **Step 1: Write the layout**

Create `resources/js/layouts/DemoLayout.vue`:

```vue
<template>
  <div class="flex h-screen bg-gray-100">
    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
      @click="isSidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <div
      class="fixed inset-y-0 left-0 z-50 w-64 text-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto md:flex md:flex-col"
      :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      style="background-color: #0055A4;"
    >
      <div class="p-6 border-b" style="border-color: #003d7a;">
        <h2 class="text-2xl font-bold">{{ settingsStore.siteName }}</h2>
        <p class="text-white/80 text-sm">Student Portal — Demo</p>
      </div>

      <nav class="mt-6 flex-1 space-y-1 px-2 overflow-y-auto">
        <template v-for="item in demoMenu" :key="item.name">
          <router-link
            v-if="item.path"
            :to="item.path"
            class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200"
            :class="isActive(item.path) ? 'text-white' : 'text-white/90 hover:opacity-80'"
            :style="isActive(item.path) ? 'background-color: #003d7a;' : ''"
            @click="isSidebarOpen = false"
          >
            <component :is="item.icon" class="w-5 h-5 mr-3" />
            <span class="font-medium">{{ item.name }}</span>
          </router-link>

          <button
            v-else
            type="button"
            class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-white/50 hover:text-white/70 transition-colors"
            @click="gate.open('to unlock this page')"
          >
            <div class="flex items-center">
              <component :is="item.icon" class="w-5 h-5 mr-3" />
              <span class="font-medium">{{ item.name }}</span>
            </div>
            <Lock class="w-4 h-4" />
          </button>
        </template>
      </nav>
    </div>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <div class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-8 z-30">
        <div class="flex items-center">
          <button
            class="mr-4 text-gray-600 hover:text-gray-900 focus:outline-none md:hidden"
            @click="isSidebarOpen = !isSidebarOpen"
          >
            <Menu class="w-6 h-6" />
          </button>
          <h1 class="text-xl font-bold text-gray-800 truncate">{{ pageTitle }}</h1>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-4">
          <div class="hidden sm:block text-right">
            <p class="text-sm font-medium text-gray-800">Guest</p>
            <p class="text-xs text-gray-500">Demo preview</p>
          </div>
          <router-link
            to="/register"
            class="px-3 sm:px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg text-sm font-medium transition-colors"
          >
            Create free account
          </router-link>
        </div>
      </div>

      <!-- Demo banner -->
      <div class="bg-amber-50 border-b border-amber-200 px-4 sm:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-amber-900">
          You're viewing a demo of the student portal. Create an account to enrol and track progress.
        </p>
        <div class="flex gap-2 shrink-0">
          <router-link
            to="/login"
            class="px-3 py-1.5 text-sm font-medium text-[#0055A4] hover:text-[#003d7a] transition-colors"
          >
            Log in
          </router-link>
          <router-link
            to="/register"
            class="px-3 py-1.5 text-sm font-medium bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
          >
            Sign up
          </router-link>
        </div>
      </div>

      <div class="flex-1 overflow-auto bg-gray-100">
        <router-view />
      </div>
    </div>

    <DemoGateModal />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { BarChart3, BookOpen, User, FileText, Menu, Lock, GraduationCap } from 'lucide-vue-next'
import { useSettingsStore } from '../stores/settings'
import { useDemoGate } from '../composables/useDemoGate'
import DemoGateModal from '../components/DemoGateModal.vue'

const route = useRoute()
const settingsStore = useSettingsStore()
const gate = useDemoGate()
const isSidebarOpen = ref(false)

// A null path marks a locked item: it renders greyed with a lock and opens the gate.
const demoMenu = ref([
  { name: 'Dashboard', path: null, icon: BarChart3 },
  { name: 'Courses', path: '/demo/courses', icon: BookOpen },
  { name: 'Exam Prep', path: '/demo/exam-prep', icon: GraduationCap },
  { name: 'Homework', path: null, icon: FileText },
  { name: 'Account', path: null, icon: User },
])

const pageTitle = computed(() => {
  const match = demoMenu.value.find(item => item.path && route.path.startsWith(item.path))
  return match ? match.name : 'Student Portal Demo'
})

const isActive = (path) => route.path.startsWith(path)

onMounted(() => {
  settingsStore.fetchSettings()
})
</script>
```

- [ ] **Step 2: Verify it compiles**

Run: `npm run build`

Expected: build succeeds.

- [ ] **Step 3: Commit**

```bash
git add resources/js/layouts/DemoLayout.vue
git commit -m "Add guest demo layout shell"
```

---

### Task 5: Demo courses page

**Files:**
- Create: `resources/js/pages/demo/Courses.vue`

**Interfaces:**
- Consumes: `GET /api/demo/courses` from Task 2, `useDemoGate()` from Task 3.
- Produces: the `/demo/courses` route component for Task 7.

**Context you need:** This is a deliberate fork of `resources/js/pages/student/BrowseCourses.vue` — read that file first and keep the card markup identical so the demo looks like the real portal. The differences: it calls `axios` directly rather than `useCourseStore` (which hits authenticated endpoints), it has no enrolled state, and every click calls `gate.open()`. Errors render inline rather than via `useToast`, because the toast host lives in the authenticated shell.

- [ ] **Step 1: Write the page**

Create `resources/js/pages/demo/Courses.vue`:

```vue
<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Courses</h1>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading courses...</p>
    </div>

    <div v-else-if="error" class="text-center py-12 bg-white rounded-lg">
      <p class="text-gray-700 mb-4">{{ error }}</p>
      <button
        class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
        @click="loadCourses"
      >
        Try again
      </button>
    </div>

    <div v-else-if="courses.length === 0" class="text-center py-12 bg-white rounded-lg">
      <p class="text-gray-500 mb-4">No courses available yet</p>
      <router-link to="/register" class="text-[#0055A4] hover:text-[#003d7a] font-medium">
        Create a free account
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden cursor-pointer"
        @click="gate.open('to view this course')"
      >
        <img
          :src="course.image_url || 'https://via.placeholder.com/400x200?text=Course'"
          :alt="course.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-gray-800 line-clamp-2">{{ course.title }}</h3>
            <span
              v-if="course.category"
              class="px-2 py-1 bg-[#0055A4]/10 text-[#003d7a] text-xs rounded-full ml-2 flex-shrink-0"
            >
              {{ course.category }}
            </span>
          </div>
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ course.description }}</p>
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-600">
              <strong>Level:</strong> {{ course.level || 'Beginner' }}
            </span>
            <span class="text-sm text-gray-600">
              <strong>Language:</strong> {{ course.language || 'French' }}
            </span>
          </div>
          <div class="flex gap-2">
            <button
              class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors"
              @click.stop="gate.open('to view this course')"
            >
              View Details
            </button>
            <button
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
              @click.stop="gate.open('to enrol')"
            >
              Enroll
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useDemoGate } from '../../composables/useDemoGate'

const gate = useDemoGate()
const courses = ref([])
const loading = ref(false)
const error = ref('')

const loadCourses = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.get('/api/demo/courses')
    const payload = response.data?.data
    courses.value = payload?.data ?? payload ?? []
  } catch (err) {
    console.error('Failed to load demo courses:', err)
    error.value = 'Unable to load the demo catalogue. Please try again.'
    courses.value = []
  } finally {
    loading.value = false
  }
}

onMounted(loadCourses)
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
```

- [ ] **Step 2: Verify it compiles**

Run: `npm run build`

Expected: build succeeds.

- [ ] **Step 3: Commit**

```bash
git add resources/js/pages/demo/Courses.vue
git commit -m "Add gated demo courses page"
```

---

### Task 6: Demo exam prep page

**Files:**
- Create: `resources/js/pages/demo/ExamPrep.vue`

**Interfaces:**
- Consumes: `GET /api/demo/exam-preps` from Task 2, `useDemoGate()` from Task 3.
- Produces: the `/demo/exam-prep` route component for Task 7.

**Context you need:** The forked source is `resources/js/pages/student/BrowseExamPrep.vue`. Same structure as Task 5 — the full code is repeated below rather than referenced, because you may be reading this task on its own.

- [ ] **Step 1: Write the page**

Create `resources/js/pages/demo/ExamPrep.vue`:

```vue
<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Exam Prep</h1>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
      <p class="mt-2 text-gray-500">Loading exam preps...</p>
    </div>

    <div v-else-if="error" class="text-center py-12 bg-white rounded-lg">
      <p class="text-gray-700 mb-4">{{ error }}</p>
      <button
        class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
        @click="loadExamPreps"
      >
        Try again
      </button>
    </div>

    <div v-else-if="examPreps.length === 0" class="text-center py-12 bg-white rounded-lg">
      <p class="text-gray-500 mb-4">No exam preps available yet</p>
      <router-link to="/register" class="text-[#0055A4] hover:text-[#003d7a] font-medium">
        Create a free account
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="examPrep in examPreps"
        :key="examPrep.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden cursor-pointer"
        @click="gate.open('to view this exam prep')"
      >
        <img
          :src="examPrep.image_url || 'https://via.placeholder.com/400x200?text=Exam+Prep'"
          :alt="examPrep.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-gray-800 line-clamp-2">{{ examPrep.title }}</h3>
            <span
              v-if="examPrep.category"
              class="px-2 py-1 bg-[#0055A4]/10 text-[#003d7a] text-xs rounded-full ml-2 flex-shrink-0"
            >
              {{ examPrep.category }}
            </span>
          </div>
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ examPrep.description }}</p>
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-600">
              <strong>Level:</strong> {{ examPrep.level || 'Beginner' }}
            </span>
            <span class="text-sm text-gray-600">
              <strong>Language:</strong> {{ examPrep.language || 'French' }}
            </span>
          </div>
          <div class="flex gap-2">
            <button
              class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors"
              @click.stop="gate.open('to view this exam prep')"
            >
              View Details
            </button>
            <button
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg transition-colors"
              @click.stop="gate.open('to enrol')"
            >
              Enroll
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useDemoGate } from '../../composables/useDemoGate'

const gate = useDemoGate()
const examPreps = ref([])
const loading = ref(false)
const error = ref('')

const loadExamPreps = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await axios.get('/api/demo/exam-preps')
    const payload = response.data?.data
    examPreps.value = payload?.data ?? payload ?? []
  } catch (err) {
    console.error('Failed to load demo exam preps:', err)
    error.value = 'Unable to load the demo catalogue. Please try again.'
    examPreps.value = []
  } finally {
    loading.value = false
  }
}

onMounted(loadExamPreps)
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
```

- [ ] **Step 2: Verify it compiles**

Run: `npm run build`

Expected: build succeeds.

- [ ] **Step 3: Commit**

```bash
git add resources/js/pages/demo/ExamPrep.vue
git commit -m "Add gated demo exam prep page"
```

---

### Task 7: Router wiring

**Files:**
- Modify: `resources/js/router/index.js` (route array, and the `beforeEach` guard at line 294)

**Interfaces:**
- Consumes: `DemoLayout` (Task 4), `pages/demo/Courses.vue` (Task 5), `pages/demo/ExamPrep.vue` (Task 6).
- Produces: working `/demo/`, `/demo/courses/`, `/demo/exam-prep/` routes.

**Context you need:** The catch-all `{ path: '/:slug', name: 'PublicPage' }` is the **last** entry in the array (line 281-286). The new block must be inserted before it or `/demo` will be swallowed by PublicPage. The guard rewrites paths to a trailing slash, so guard comparisons use `/demo/...` **with** the slash while `router-link` targets omit it.

- [ ] **Step 1: Add the route block**

In `resources/js/router/index.js`, insert immediately before the `// Public Page Route (catch-all for custom pages)` comment on line 280:

```js
  {
    path: '/demo',
    name: 'DemoLayout',
    component: () => import('../layouts/DemoLayout.vue'),
    redirect: '/demo/courses',
    meta: { requiresAuth: false },
    children: [
      {
        path: 'courses',
        name: 'DemoCourses',
        component: () => import('../pages/demo/Courses.vue')
      },
      {
        path: 'exam-prep',
        name: 'DemoExamPrep',
        component: () => import('../pages/demo/ExamPrep.vue')
      }
    ]
  },
```

- [ ] **Step 2: Add the logged-in redirect to the guard**

In the same file, inside `router.beforeEach`, insert directly **after** the block that redirects authenticated users away from `/` (the block ending at line 345) and **before** the `if (to.meta.requiresAuth && !auth.isAuthenticated)` check:

```js
  // Signed-in users get the real portal, not the demo.
  if (to.path.startsWith('/demo') && auth.isAuthenticated && auth.user) {
    if (auth.user.user_type === 'admin' || auth.user.user_type === 'super_admin') return next('/admin/dashboard/')
    if (auth.user.user_type === 'tutor') return next('/tutor/dashboard/')
    return next('/student/dashboard/')
  }
```

- [ ] **Step 3: Verify it compiles**

Run: `npm run build`

Expected: build succeeds, and the output lists new `Courses-*.js`, `ExamPrep-*.js` and `DemoLayout-*.js` chunks.

- [ ] **Step 4: Verify in the browser**

With MySQL running, start `php artisan serve --port=8001` and `npm run dev`, then visit `http://localhost:5174/demo/` **logged out**. Confirm each of these:

1. It redirects to `/demo/courses/` and the sidebar renders in `#0055A4`.
2. Course cards load real catalogue content.
3. Dashboard, Homework and Account appear greyed with a lock; clicking one opens the modal.
4. Clicking Enroll, View Details, or a card body opens the modal.
5. The modal's "Create free account" navigates to `/register/`.
6. `/demo/exam-prep/` renders exam prep cards.
7. Log in as a student, visit `/demo/` — you are redirected to `/student/dashboard/`.

Fix anything that fails before committing.

- [ ] **Step 5: Commit**

```bash
git add resources/js/router/index.js
git commit -m "Wire up demo routes and redirect signed-in users to the real portal"
```

---

### Task 8: Entry points

**Files:**
- Modify: `resources/js/pages/auth/Login.vue:63-75`
- Modify: `resources/js/components/PublicHeader.vue` (desktop CTA block at lines 33-39, mobile CTA block at lines 91-101)

**Interfaces:**
- Consumes: the `/demo` route from Task 7.
- Produces: nothing consumed by later tasks. This is the final task.

**Context you need:** `Login.vue` already has a bordered link block containing "Register here" and "Forgot your password?". `PublicHeader.vue` renders its CTAs twice — once for desktop (`custom-desktop:flex` nav) and once in the mobile drawer — and both branch on whether the user is signed in. Add the demo link only to the signed-out branch in both.

- [ ] **Step 1: Add the login page link**

In `resources/js/pages/auth/Login.vue`, inside the `<div class="mt-6 space-y-3 text-center text-sm border-t pt-6">` block, add as the last child after the "Forgot your password?" paragraph:

```html
          <p class="pt-3 border-t border-gray-100">
            <router-link to="/demo" class="text-gray-600 hover:text-[#0055A4] font-medium">
              Curious? Preview the student dashboard →
            </router-link>
          </p>
```

- [ ] **Step 2: Add the desktop header CTA**

In `resources/js/components/PublicHeader.vue`, in the signed-out desktop branch, insert immediately **before** the existing `<router-link to="/login" class="action-btn-login ...">`:

```html
            <router-link to="/demo" class="action-btn-login whitespace-nowrap">SEE DEMO</router-link>
```

- [ ] **Step 3: Add the mobile drawer CTA**

In the same file, in the signed-out mobile branch, insert immediately **after** the existing `<router-link to="/login" ...>LOGIN</router-link>`:

```html
               <router-link to="/demo" class="w-full py-2 text-center text-gray-500 font-bold text-sm uppercase tracking-widest hover:text-[#0055A4] transition block">
                 See Demo
               </router-link>
```

- [ ] **Step 4: Verify the full suite and build**

Run both:

```bash
php artisan test
npm run build
```

Expected: PHPUnit reports the Task 1 unit tests passing and the Task 2 feature tests skipped, with zero failures. The Vite build succeeds.

- [ ] **Step 5: Verify the entry points in the browser**

Logged out, confirm: the demo link appears at the bottom of `/login/` and navigates to `/demo/courses/`; "SEE DEMO" appears in the desktop header; "See Demo" appears in the mobile drawer. Log in and confirm neither header CTA is shown to signed-in users.

- [ ] **Step 6: Commit**

```bash
git add resources/js/pages/auth/Login.vue resources/js/components/PublicHeader.vue
git commit -m "Add demo entry points to login page and public header"
```

---

## Verification Checklist

Run through this once all tasks are done:

- [ ] `php artisan test` — unit tests pass, feature tests skipped, no failures
- [ ] `npm run build` — succeeds
- [ ] `curl -s http://127.0.0.1:8001/api/demo/courses` returns only whitelisted keys
- [ ] `php artisan route:list --path=demo` shows no `auth:sanctum` on either route
- [ ] `/demo/` works logged out; every action opens the gate modal
- [ ] A logged-in student visiting `/demo/` lands on `/student/dashboard/`
- [ ] No commit in `git log` contains a `Co-Authored-By` trailer

## Known Follow-Ups (out of scope)

- Extract a shared `CourseCard.vue` consumed by both the browse and demo pages, once test coverage exists to verify the extraction. Until then Tasks 5 and 6 duplicate the card markup by design.
- Add migrations so `tests/Feature/DemoApiTest.php` can be un-skipped.
- `StudentController::browseCourses()` still returns whole models to authenticated students, including `course_json_content`. Narrowing that is a separate change with its own regression risk.
