# Reconstructed migrations — read before running

These migrations were **reverse-engineered from `app/Models/*.php`** (from `$fillable`,
`$casts`, `$table`, and relationship methods), plus the tables reached only through
`DB::table(...)`. This project has never shipped migrations; production's schema was
created outside version control.

**They are an approximation, not the production schema.** Column types, string lengths,
nullability, defaults, and indexes were inferred — `$fillable` records only column
*names*. Expect differences from production.

## Do not run these against production

There is no `migrations` table on the live database, so:

- `php artisan migrate` will fail ("table already exists")
- **`php artisan migrate:fresh` will DROP EVERY TABLE and destroy all live data**

Those two commands differ by one word. Only run either against a local database.

## Intended use

Local development when no production dump is available: it gets the app booting with an
empty catalogue so UI work can proceed. Nothing more.

## The authoritative fix

Import a real dump. On the production server:

```bash
mysqldump -u helloworld -p helloworld > ~/fluence-dump.sql
```

Then locally:

```bash
mysql -u root helloworld < fluence-dump.sql
```

Once a dump exists, **it is the source of truth — delete this directory** rather than
trying to reconcile the two.

## Seeders

`php artisan db:seed` populates local development data only. Every seeder uses
`updateOrCreate` keyed on a natural column, so re-running is idempotent.

Accounts created — **password is `password` for all three**:

| Email | user_type |
| --- | --- |
| `admin@fluencefrancaise.local` | super_admin |
| `tutor@fluencefrancaise.local` | tutor |
| `student@fluencefrancaise.local` | student |

Never run these against production — they would create real login accounts with a
known password.

Image paths in `CatalogueSeeder` reference files that genuinely exist in
`storage/app/public/{courses,books}`, so cards render real artwork. `SettingsSeeder`
sets `robots` to `noindex, nofollow` and `site_url` to `http://localhost:5174`.

## Known inference to check against a real dump

`courses` has both `course_category` (free-text label, read by views) and `category_id`
(FK to `class_types`, validated by `CourseController`). Both appear in the code, so both
are here, but production may only have one.
