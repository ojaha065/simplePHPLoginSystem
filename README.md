# Just a very barebones PHP login system

_Please note that this is is still WIP._

This project started as a simple school assignment for some PHP course I was attending. Then I needed this kind of system for some website I was making and decided to start expanding. Word of warning: This project is/will be very opinionated, meaning that I will only add features that I myself find useful or interesting enough to develop.

## Some basic info

* It should be secure enough for general use. I take no responsibility tho.
* It uses PHP's `password_hash()` function to hash and salt the passwords. Usernames are saved as a plain text.
  * I'm using `PASSWORD_DEFAULT` which at time of the writing uses BCRYPT.
* It requires MySQL database. More info about setting up your database below.
* I'm using the PDO interface. You also need PDO_MYSQL driver installed.
* I have only tested it in PHP 7.0 and above but it should work all the way down to 5.5.0.

## File structure

_AKA 'Why there are so many files?'_

#### /admin
The admin panel will be here. Similarly to many other web applications (like WordPress) the admin panel can be easily accessed by typing _[host]/admin_. 
#### /config
This folder includes two files that are used to set some config options. More info below.
#### /css
Just some CSS goodies for the UI.
#### /js
This folder includes all .js files needed for the user interface to work.
#### /utils
This folder includes the backbone of the system. It has .php files for connecting to the database, login in and out and registering an new account. There's also scripts.js that includes general JavaScript goodies for the user interface. You need to insert your database info to databaseConnect.php, check database section below for help.
#### LICENSE
You should read this before using this. It's just a normal MIT license tho.
#### README.md
This file.
#### index.php
This file is here just for the demo. The users can only see the page if they are logged in. Otherwise they are redirected to the login page.
#### login.php
My take on creating a simple login form with Bootstrap. Feel free to modify it to fit your needs.
#### register.php
My take on creating a simple registration form with Bootstrap. Feel free to modify it to fit your needs.

### Config options

_Please note that config options are subject to change. Check back often._

If you checked the /config folder, you probably noticed that there is two different files there. What gives? The main configuration file is **config.php**. It includes settings that are enforced on the server level. You should be mainly editing this file.

If you are also using the front-end user interface I provide, then you can/must also edit **config.js** There you can manage how things look for the avarage user. **Please remember that these settings are client-side only and _not_ enforced in any way so they can be edited by users.**

#### Config options in config.php
| Option name                  | Description                                                | Default value | Supported values |
|------------------------------|------------------------------------------------------------|---------------|------------------|
| $disableUserSelfRegistration | Prevent users from registering                             | false         | Boolean          |
| $usernameMinLength           | Shortest allowed username                                  | 3             | 1 ->             |
| $usernameMaxLength           | Longest allowed username                                   | 30            | 1 ->             |
| $passwordMinLength           | Minimum length of passwords                                | 8             | 1 ->             |
| $usernameRegExp              | All usernames must match this regular expression           |               | any regExp       |
| $passwordRegExp              | All usernames must match this regular expression           |               | any regExp       |
| $newAccountAccessLevel       | Useful for creating your first admin account               | "user"        | "user", "admin"  |
| $debugMode                   | Allows you to disable dabase connection (for testing only) | "no"          | "no"             |
| $debugAdminUsername          | Allows you to log in while in debug mode                   | "admin"       | any string       |
| $debugAdminPassword          | Allows you to log in while in debug mode                   | ""            | any string       |
| $dateSeperator               | The seperator between numbers in dates. (eg. "/" or ".")   | "."           | any string       |
| $timeSeperator               | The seperator between numbers in times.                    | ":"           | any string       |
| $mmddyyyy                    | Save dates in MMDDYYYY format instead of DDMMYYYY          | false         | Boolean          |


#### Config options in config.js
| Option name                 | Description                                                 | Default value | Supported values |
|-----------------------------|-------------------------------------------------------------|---------------|------------------|
| disableUserSelfRegistration | Disables any UI elements related to registration            | false         | true, false      |
| usernameMinLength           | Shortest username that UI accepts                           | 3             | 1 ->             |
| usernameMaxLength           | Longest username that UI accepts                            | 30            | 1 ->             |
| passwordMinLength           | Shortest password that UI accepts                           | 8             | 1 ->             |
| usernameRules               | This string is shown if username didn't match the regExp    |               | any string       |
| passwordRules               | This string is shown if password didn't match the regExp    |               | any string       |

## Database

As stated earlier, you need MySQL database. The database requires very little space and any fairly recent version of MySQl should work.

### Setting up the database
0. Have a MySQL database that you have access to.
1. Create table `users` with four colums: `username` , `password` , `accessLevel` and `lastLogin`. Use a string data type like CHAR. I personally like to use VARCHAR. I would also add auto incrementing id field but that is not strictly required.
2. Insert your database hostname, port, name and credentials into **/utils/databaseConnect.php**. I recommend creating dedicated account with restricted permissions.

### FAQ
#### Why don't you have a automatic wizard for inserting database info and doing all this SQL stuff?
I'm planning to create something like that sometime in near future. Stay posted.

#### I have a database but don't what any of that jargon about tables and colums mean. Help?
Don't know your SQL? Don't worry, just wait a little while. I'm planning to create a automatic wizard that can do most of this stuff for you.

If you are able to run SQL queries inside you database (it's easy to do in phpMyAdmin, for example) then something like this should get you covered:
````SQL
CREATE TABLE users (
 id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 username VARCHAR(64) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 accessLevel VARCHAR(10) NOT NULL,
 lastLogin VARCHAR(16)
);
````

#### I don't know what port my database is using.
MySQL default is 3306.

## The admin panel

_The admin panel is in very early stage of the development. Many things might be broken._

### Admin panel allows you to...
* See all user accounts, their access level and last login time
* Delete user accounts

More to be added in the future.

### How do I access it?
_/admin_

## General FAQ
### How do I turn on the debug mode?
Read config.php more carefully.

### I'm getting 'Connection error occured' message and I'm sure that my I have entered my database info correctly into **/utils/databaseConnect.php**
That error message means that `PDOException` occured while trying to connect to the database. The most common causes are:
1. Wrong database hostname, port, name or credentials.  
 Double check them.
2. The database is down.  
 Can you access your database by using phpMyAdmin or similar?
3. PHP's PDO interface or `PDO_MYSQL` driver is not available or configured properly.  
 Try updating to the latest version of PHP if possible. Make sure you or your host enable `PDO_MYSQL`.
4. Your host's firewall is blocking the connection.  
 Make sure that your or your host's firewall is not blocking the connection.  
5. Something else.  
 Please open issue here on GitHub i and I try to figure it out.
 
 I'm planning to introduce more verbose error messages sometime in the future to help with the situations like this.

### Dates and/or are wrong!
Notice that on default settings the dates are saved in European format. (Day before month) You can change that behavior with a config option in **config.php**.
Also, the saved time is the **server's time, not your/user computer's**. If your host is in different time zone than you then the the times will be offset. I'm planning to add $timeOffset config option to help with this problem.
