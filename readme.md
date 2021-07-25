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

Update database with existing data - storage/backups/mysql.sql

For Word component:
Unzip storage/backups/wordnet20-from-prolog-all-3.zip
Edit the sql file changing all ITEM=MyISAM to ENGINE=MyISAM
Load the sql into your mysql database.