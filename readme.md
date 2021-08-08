## Music Laravel

"musiclaravel"  is my take on iTunes.  It is very much a work in progress.

Displays songs and artists, enables a user to create and play playlists.

This predicates you have composer and a web server running on your machine.

Then clone this repository, cd into the root directory, and run composer install.

Create a mysql database.

Copy env.example to .env and update:

APP_KEY - the value of php artisan key:generate --show
Set DB credentials and other credentials
Run database migrate scripts - php artisan migrate

Update database with existing data - storage/backups/mymusic.sql

For Word component:
Create database with https://sourceforge.net/projects/mysqlenglishdictionary/ dictory - storage/backups/englishdictionary.sql