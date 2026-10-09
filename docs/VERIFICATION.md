# Verification — 9 October 2026

- PHP 8.2.12 and Composer 2.9.5 inspected before creation.
- Laravel 12.69.3 dependencies downloaded and composer.lock generated.
- Existing sources/ reference files left untouched; all application files are
  inside the new standalone sa-crime-watch directory.
- SQLite integration suite: 13 tests, 75 assertions passed. Includes account
  creation/login/logout, report/photo visibility, filtering, sanitized uploads,
  invalid upload rejection, moderator authorization/audit, encrypted tips,
  sensitive-input session exclusion, tip expiry and community membership.
- Laravel routes enumerated and Blade templates compiled successfully.
- PHP files formatted with Laravel Pint.
- MariaDB 11.4.10 portable ZIP downloaded and SHA-256 verified. Administrator
  authentication prevented database provisioning. Authentication was left
  unchanged at the user's request; MariaDB tests were not executed successfully.
- Docker is optional and its build/Compose stack has not been executed.
- Development preview uses SQLite. No deployment or publication performed.
- Live HTTP checks: homepage, communities, login and registration returned
  successful pages with no-store/private headers; static CSS returned 200.
  Login without a CSRF token returned 419. Development router syntax checked.

MVP limits and privacy details are documented in ARCHITECTURE.md.

## Reference-inspired interface update

Homepage, navigation, report summaries and account pages redesigned from the
30-site reference register in DESIGN-REFERENCES.md. Existing suite passed:
13 tests, 75 assertions. Homepage counts query actual approved/recovered
reports and available communities; pending reports remain excluded.

Live homepage, registration, login and filtered search returned 200. Browser
checks covered desktop and 390-pixel phone layouts, with no horizontal overflow
on the registration page or phone homepage. Native SVG icons and the filter
disclosure rendered correctly. The CSS URL includes a file version so browser
caches pick up design changes. A full homepage screenshot is saved as
design-homepage.png. Temporary viewport overrides and the QA tab were removed.

## Dynamic interface and sponsorship

The expanded SQLite suite passed 19 tests and 113 assertions, including sponsor
scheduling, moderator permissions, encrypted enquiries, consent and exclusion
of ads from private and tip pages. First-party JavaScript syntax checked.
Browser checks confirmed live community filtering and the cards/list toggle.
Desktop and 390-pixel phone layouts had no horizontal overflow; browser error
logs were empty. Generated decorative imagery is labelled and documented in
GENERATED-ASSETS.md. The final homepage screenshot is design-sponsored.png.
Direct sponsorship slots and private booking enquiries are implemented; payment
and invoicing are outside the MVP. No third-party ad network was connected.
