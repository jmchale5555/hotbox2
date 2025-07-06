# Hotdesk Booking System 

This is a simple hotdesk booking system that will allow you to make hotdesks bookable in any number of rooms or locations. It uses PHP server side and Alpine JS for UI and AJAX requests.

Admin users see an additional admin panel where they can add rooms, change the number of hotdesks in a room or add/update the floor plan.  It uses Tailwind and supports both light and dark mode depending on your system / browser setting.

There's a binary which can be run to set up the database called hot_dbinit and I've left the source code there in the project root in file hot.c

![alt text](https://github.com/jmchale5555/sbox2/blob/master/screenshotG.jpg?raw=true "Hotdesk Booking System Screenshot")

## Hotdesk Booking System Quick Start Guide

1. Clone repository.
2. Install Node js and run the following command from project root: `npm install`
3. Copy config.php.example from project root folder to app/core and rename config.php. \
        ```
                cp config.example.php app/core/config.php
        ```
4. Edit config.php, leave the DBNAME as hot.  If using docker the DBHOST is mariadb and initial MySql password is 'rootpassword'. Set ROOT URL to your machine's name or http://localhost.
5. For production you'll need to set different MySql DB user and password by using a docker exec command and turn debug mode off in this file, but defaults are good for trialing.
6. Make sure you have Docker Engine and Docker Compose installed.  Docker Desktop will give you those plus a load of other stuff. 
7. From the root of the repo: `sudo docker compose up --build -d`
8. Once docker compose has finished building containers, type the following command:
9. `sudo docker exec -it $(docker ps | grep mariadb | cut -c1-12) /usr/local/bin/db-init`
10. This will create the database, schema and initial admin user for the system
11. Now visit your host machine in a web browser and it will be serving the Hot Desk booking system.
12. Hit Login, there will be an initial admin user set up with username: 'admin' and password: 'pavlova'.  You should log in with this and reset the password from the navbar menu option.
13. To enable Active Directory / LDAP authentication, admin users can go to the "System Settings" menu item and enter your ldap connection details.
14. You can now start registering users and adding rooms to the system, setting total number of hotdesks and an optional desk plan image for each room.

## Developers

To work on the site you'll need to run a Tailwind watcher from the project root in a terminal window.

You could set up the following alias in .bashrc:

alias tailwatch='npx tailwindcss -i ./public/assets/css/input.css -o ./public/assets/css/output.css --watch'

Then run the comman `tailwatch` from the root directory of the project.  Now when you use new tailwind classes the main css will be recompiled automatically.

