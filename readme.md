## Music Laravel

"musiclaravel"  is my take on iTunes.

Displays songs and artists and lyrics, enables a user to create and play playlists.

This predicates you have composer.

Then clone this repository, cd into the root directory, and run composer install.

Create a mysql database.

Copy env.example to .env
Set DB credentials and other credentials in the .env file.

In order to run the Words command, external Word databases will need to be imported:
I am currently using two different Word databases, both have words the other does not.

The first is Princeton University's WordNet database.  
To install:  
Unzip storage/backups/wordnet20-from-prolog-all-3.zip  
Edit the sql file changing all TYPE=MyISAM to ENGINE=MyISAM  
Load the sql into your mysql database.  
mysql -u *user* -p*userpassword* jukebox < storage/backups/wordnet20-from-prolog-all-3.sql

The second is sourced from a Source Forge project - https://sourceforge.net/projects/mysqlenglishdictionary/  
Load the sql and create the database with storage/backups/englishdictionary.sql  
mysql -u *user* -p*userpassword* jukebox < storage/backups/englishdictionary.sql

Run database migrate scripts - php artisan migrate

Update database with existing data - storage/backups/mymusic.sql

php artisan serve


