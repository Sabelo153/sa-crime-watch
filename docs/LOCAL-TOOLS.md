# Portable local software

MariaDB 11.4.10 Windows x64 was downloaded from the official archive:
https://archive.mariadb.org/mariadb-11.4.10/winx64-packages/

SHA-256 verified against its sha256sums.txt:
fb7c76f0804321ee373daa49145f2056d2d88f321b614130adeb05a1644ea003

Files are under .tools/mariadb; data is under .tools/db-data, with port 3307
bound to 127.0.0.1. No Windows service, global PATH changes or unrelated
database modifications were made. The .tools directory is ignored by Git and
Docker. Its credential files are local secrets; never share them.

Use scripts/start-local.ps1 and scripts/stop-local.ps1 from PowerShell to start
or stop the project database/preview. The README describes application
initialization. Initialization must succeed before the MariaDB preview works.
The administrator account could not authenticate under this environment.
Its authentication was left unchanged at your request. The local .env uses
SQLite instead; the start script only starts MariaDB when DB_CONNECTION=mariadb.
MariaDB integration tests have not been verified.
PHP/Composer were already installed; GD is enabled per-process by the preview
script. Docker is an optional alternative, not required for this native setup.
