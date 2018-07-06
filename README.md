# Just a very barebones PHP login system

_Please note that this is is still WIP._

This project started as a simple school assignment for some PHP course I was attending. Then I needed this kind of system for some website I was making and decided to start expanding. Word of warning: This project is/will be very opinionated, meaning that I will only add features that I myself find useful or interesting enough to develop.

## Some basic info

* It should be secure enough for general use. I take no responsibility tho.
* It uses PHP's `password_hash()` function to hash and salt the passwords. Usernames are saved as a plain text.
  * I'm using `PASSWORD_DEFAULT` which at time of the writing uses BCRYPT.
* It requires MySQL database. More info about setting up your database below.
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
My take on creating a simple login form with Bootstrap. Feel free to modify it to your needs.
#### register.php
My take on creating a simple registration form with Bootstrap. Feel free to modify it to your needs.

### Config options

_Please note that config options are subject to change. Check back often._

If you checked the /config folder, you probably noticed that there is two different files there. What gives? The main configuration file is **config.php**. It includes settings that are enforced on server level. You should be mainly editing this file.

If you are also using front-end demo/sample I provide, then you can/must also edit **config.js** There you can manage how things look for the avarage user. **Please remember that these settings are client only and _not_ enforced in any way so they can be edited by users.**

#### Config options in config.php
* ...
* ...

#### Config options in config.js
* ...
* ...

## Database

Coming soon...
