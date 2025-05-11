Hotdesk Booking System Quick Start Guide

1. Clone repository
2. Set up webserver with repo's public folder as document root
3. Install PHP 8.2 or higher. See guidenotes https://www.fadocodecamp.com/posts/installing-php-on-linux
4. Usual web server stuff, use chmod to grant ownership of the project folder to the webserver and a group that includes the user that will be running commands (in my case root).  example: chmod apache:root <projectfoldername>.  Check that the webserver user (apache/www-data) has rwx permissions to project recursively.
5. Install Mysql or Maradb
6. Copy config.php.example from project root folder to app/core and rename config.php
7. Edit config.php, leave the DBNAME as hot and enter your DB password and ROOT URL.  You can have different DB user and host if necessary but defaults are good.
8. Run command 'npm install' from project root to install required node modules
9. Run command 'hot_dbinit' to set up the database.  If you want to use a database user other than root and/or a hostname other than localhost, add -h command argument to see how
10. There will be an initial admin user set up with email: admin@null.local and password 'pavlova'.  You should log in with this and change the password from the navbar menu option.
11. You can now start adding rooms to the system, giving total number of hotdesk and an optional desk plan image for each room.
