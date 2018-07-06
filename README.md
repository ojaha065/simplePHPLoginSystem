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

#### /config
This folder includes two files that are used to set some config options. More info below.
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

If you checked the /config folder, you probably noticed that there is two different files there. What gives? The main configuration file is **config.php**. It includes settings that are enforced on server level. You should be mainly editing this file.

If you are also using front-end demo/sample I provide, then you can/must also edit **config.js** There you can manage how things look for the avarage user. **Please remember that these settings are client-side only and _not_ enforced in any way so they can be edited by users.**

#### Config options in config.php
| Option name                  | Description                                                | Default value | Supported values |
|------------------------------|------------------------------------------------------------|---------------|------------------|
| $disableUserSelfRegistration | Prevent users from registering                             | false         | true, false      |
| $usernameMinLength           | Shortest allowed username                                  | 3             | 1 ->             |
| $usernameMaxLength           | Longest allowed username                                   | 30            | 1 ->             |
| $passwordMinLength           | Minimum length of passwords                                | 8             | 1 ->             |
| $usernameRegExp              | All usernames must match this regular expression           |               | any regExp       |
| $passwordRegExp              | All usernames must match this regular expression           |               | any regExp       |


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
1. Create table `users` with at least two colums: `username` and `password`. I would also add auto incrementing id field but that is not strictly required.
2. Insert your database hostname, port, name and credentials into **/utils/databaseConnect.php**. I recommend creating dedicated account with restricted permissions.

### FAQ
#### Why don't you have a automatic wizard for inserting database info and doing all this SQL stuff?
I'm planning to create something like that sometime in near future. Stay posted.

#### I have a database but don't what any of that jargon about tables and colums mean. Help?
Don't know your SQL? Don't worry, just wait a little while. I'm planning to create a automatic wizard that can do most of this stuff for you.

#### I don't know what port my database is using.
MySQL default is 3306.

If you are able to run SQL queries inside you database (it's easy to do in phpMyAdmin, for example) then something like this should get you covered:
````SQL
CREATE TABLE users (
 id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 username VARCHAR(64) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL
);
