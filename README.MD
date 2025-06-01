# Hotdesk Booking System 

This is a simple hotdesk booking system that will allow you to make hotdesks bookable in any number of rooms or locations. It uses PHP server side and Alpine JS for UI and AJAX requests.

Admin users see an additional admin panel where they can add rooms, change the number of hotdesks in a room or add/update the floor plan.  It uses Tailwind and supports both light and dark mode depending on your system / browser setting.

There's a binary which can be run to set up the database called hot_dbinit and I've left the source code there in the project root in file hot.c

![alt text](https://github.com/jmchale5555/sbox2/blob/master/screenshotG.jpg?raw=true "Hotdesk Booking System Screenshot")

## Hotdesk Booking System Quick Start Guide

1. Clone repository.
2. Set up webserver with repo's public folder as document root.  
3. Install PHP 8.2 or higher. See guidenotes https://www.fadocodecamp.com/posts/installing-php-on-linux
4. Usual web server stuff, use chmod to grant ownership of the project folder to the webserver and a group that includes the user that will be running commands (in my case root).  example: `chmod -R apache:root projectfoldername`.  Check that the webserver user (apache/www-data) and the user you're running commands as has rwx permissions to project directory recursively.
5. Install Mysql or Mariadb.
6. Copy config.php.example from project root folder to app/core and rename config.php.
7. Edit config.php, leave the DBNAME as hot and enter your DB password and ROOT URL.  You can have different DB user and host if necessary but defaults are good.  To enable LDAP auth, enter the domain name of your users container, as the example shows.
8. Run command `npm install` from project root to install required node modules.
9. Run command `hot_dbinit` to set up the database.  If you want to use a database user other than root and/or a hostname other than localhost, add -h argument to see how to set those `hot_dbinit -h`.
10. There will be an initial admin user set up with email: admin@null.local and password 'pavlova'.  You should log in with this and change the password from the navbar menu option.
11. You can now start registering users and adding rooms to the system, setting total number of hotdesks and an optional desk plan image for each room.
