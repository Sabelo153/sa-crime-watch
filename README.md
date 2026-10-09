# SA Crime Watch

Standalone Laravel/PHP community safety MVP with accounts, geographic
membership, stolen-vehicle reports/photos/search, audited moderation and
encrypted private tips. PHP 8.2+, Composer 2 and MariaDB 11.4 are supported.
The UI uses Blade and static CSS, with no Node build.

## Native setup

The current local .env is configured for a SQLite preview, as requested.
Dependencies, migrations and example communities are already initialized.
Run scripts/start-local.ps1 in PowerShell, then open localhost:8000.
MariaDB is downloaded but its authentication is unchanged and provisioning
remains unverified. The following instructions describe a fresh MariaDB setup.

Run commands from this directory. PHP needs Laravel's standard extensions,
PDO MySQL, PDO SQLite (tests) and GD (photos). On this machine GD is installed
but disabled: add -d extension=gd to PHP commands that process photos.

1. Run composer install.
2. Copy .env.example to .env only if .env does not exist. Configure the database
   and choose a strong DB_PASSWORD. Never commit .env.
3. Create a new MariaDB database/account with a database administrator:

    CREATE DATABASE sa_crime_watch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER 'watch'@'localhost' IDENTIFIED BY 'replace-with-a-strong-password';
    GRANT ALL PRIVILEGES ON sa_crime_watch.* TO 'watch'@'localhost';

4. Initialize:

    php artisan key:generate
    php artisan migrate --seed

5. Start locally, with GD enabled and sufficient upload limits:

    php -d extension=gd -d upload_max_filesize=5M -d post_max_size=24M -d memory_limit=256M -S 127.0.0.1:8000 -t public server.php

Open [localhost:8000](http://localhost:8000). Register an account, then grant
moderator access from a trusted terminal:

    php artisan watch:moderator your-email@example.com

No default credentials are seeded. Four example communities are seeded;
add your real communities through a reviewed seeder.

## Portable Windows MariaDB

See [local tools](docs/LOCAL-TOOLS.md) for the downloaded portable database,
start/stop instructions and the dedicated development port. No global service
or unrelated database is modified.

## Docker alternative

Docker is optional and has not been installed or tested in this environment.
The supplied stack binds the web app only to localhost and does not expose
MariaDB publicly.

Set APP_KEY, DB_PASSWORD and a different DB_ROOT_PASSWORD in .env first.
Generate APP_KEY with php artisan key:generate. Without local PHP/vendor,
temporarily set APP_KEY to a nonempty placeholder and use:

    docker compose run --rm --no-deps app php artisan key:generate --show

Save the generated key into .env, then run:

    docker compose up -d --build
    docker compose exec app php artisan migrate --seed

Register your moderator account, then:

    docker compose exec app php artisan watch:moderator your-email@example.com

Rebuild after source changes. Named volumes preserve database and private
storage. docker compose down stops the stack without deleting data.

## Tests

The default suite uses an isolated SQLite in-memory database:

    php -d extension=gd vendor/phpunit/phpunit/phpunit --testdox

If GD is enabled in PHP configuration, php artisan test also works.
To check MariaDB create the dedicated sa_crime_watch_test database, grant the
app account privileges there, and run:

    php -d extension=gd vendor/phpunit/phpunit/phpunit --configuration=phpunit.mariadb.xml --testdox

The MariaDB test configuration fixes the database name to sa_crime_watch_test.
Tests recreate its tables. Never change it to a database containing real data.
The optional Docker command is:

    docker compose exec app php artisan test --configuration=phpunit.mariadb.xml

## Retention and operation

Run php artisan schedule:work in a separate development terminal to run the
daily tip purge. A manual purge is php artisan watch:purge-tips. The Docker
stack does not automatically run a scheduler.

Preserve APP_KEY securely; losing it makes encrypted tips unreadable. Back up
both the database and private storage with a defined backup expiry. Serve only
public/, never the project root or private storage. Keep APP_DEBUG=false.

See [architecture](docs/ARCHITECTURE.md) for schema, folders, privacy boundaries
and MVP limits, and [verification](docs/VERIFICATION.md) for checks performed.
No deployment or publication has been performed.

## Sponsorship and dynamic browsing

The homepage includes AI-labelled editorial images, responsive sponsor slots,
search results that update without a full reload and a cards/list layout toggle.
Search still works with JavaScript disabled.

Visit /advertise to enquire about sponsorship. Moderators manage bookings
and private enquiries at /moderation/sponsors. See
[advertising workflow](docs/ADVERTISING.md) for activation, scheduling and
revenue limitations, and [image assets and prompts](docs/GENERATED-ASSETS.md).
