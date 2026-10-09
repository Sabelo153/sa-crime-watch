# SA Crime Watch architecture

Laravel 12 / PHP 8.2+ / MariaDB 11.4; server-rendered Blade and static CSS.
SQLite is available for isolated tests and local previews. No frontend build
is needed.

## Relational model

| Table | Purpose and constraints |
| --- | --- |
| users | Unique email, hashed password, moderator flag excluded from registration |
| communities | Province → municipality → named area; unique geographical tuple |
| community_user | Membership; composite primary key prevents duplicate joins |
| vehicle_reports | Reporter/community foreign keys; normalized registration; vehicle details, theft date, moderation state |
| report_photos | Report foreign key; unique private generated path; JPEG MIME type |
| tips | Report foreign key; encrypted message/contact; indexed expiry; no account/IP field |
| moderation_actions | Report and moderator foreign keys; old/new status, reason, timestamps |
| sessions/cache/jobs | Laravel operational tables |

Registration lookup and status/community/theft-date and status/make/colour
indexes support browsing. Foreign key IDs are unsigned bigints. Deleting
reporters or communities is restricted while reports reference them.
Report-child data cascades only on report deletion; no report-deletion endpoint
is exposed. Audit entries are append-only through the application.

## Folder structure

- app/Http/Controllers: accounts, search, reporting, membership and moderation
- app/Http/Middleware: privacy/security response headers
- app/Models: relationships and encrypted tip casts
- app/Services: photo decoding and metadata removal
- app/Console/Commands: moderator provisioning and tip retention
- database/migrations: relational schema; database/seeders: geographic examples
- resources/views: responsive pages; public/watch.css: static stylesheet
- tests/Feature: permissions, encryption, submissions and workflow tests
- docs: architecture and verification
- .tools: ignored portable local database and data files, never publish

## Visibility and integrity

New reports always enter pending status. Submitted user IDs and status are
discarded. Only approved reports appear in missing-vehicle search. Recovered
reports retain a detail page but cannot receive tips. Pending/rejected details
and photos are available only to the reporter and moderators.
Membership does not grant moderator access.

Moderation rechecks the role server-side and locks the report inside a
transaction before updating status and adding an audit entry. Report/photo
database inserts share a transaction; failed uploads clean up files. A process
crash can still leave orphan files, so periodically reconcile private storage
against report_photos. No precise coordinates, home addresses, identity
documents or reporter contact fields are collected.

Photos use private storage with authorization on each request. GD decodes
and re-encodes them to JPEG, stripping GPS/EXIF and source filenames. Limits:
four photos, 5 MB each, 12 megapixels. Faces/documents still require human review.
Never expose storage/app/private with a public symlink.

## Tip privacy and retention

Message/contact use encrypted casts and APP_KEY. Only moderators can read
tips; they are hidden in model serialization and excluded from session input
flashing after validation errors. Pages use no-store, no-referrer, CSP and frame
protection. User text is escaped by Blade.

Account-free tip intake uses CSRF and per-IP rate limiting. The tips table does
not store IPs; rate-limiting and session infrastructure still uses connection
identifiers, and server access logs may contain IPs. This is privacy-conscious
intake, not a guarantee of absolute anonymity. Tips expire after 90 days and
the daily scheduled command deletes them. Define separate backup/log retention
before public use. Losing APP_KEY makes tips unreadable.

## MVP limits

Manual moderator verification; no police or tracker integrations. Membership
does not verify residency. No email verification, password-reset delivery,
notifications, self-service deletion, report edits or appeals yet. A corrected
report can be submitted again; moderators can reject/remove a listing.
Public launch needs recovery/email delivery, HTTPS, backup/restore procedures
and reviewed privacy/operating policies. No deployment has been performed.
