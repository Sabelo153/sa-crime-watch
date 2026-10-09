-- Local development only; tests recreate tables in this dedicated database.
CREATE DATABASE IF NOT EXISTS sa_crime_watch_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON sa_crime_watch_test.* TO 'watch'@'%';
