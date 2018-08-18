# Just a another PHP login system

_Please note that this is is still WIP._

This project started as a simple school assignment for some PHP course I was attending. Then I needed this kind of system for some website I was making and decided to start expanding. Word of warning: This project is/will be very opinionated, meaning that I will only add features that I myself find useful or interesting enough to develop.

## Some basic info

* It should be secure enough for general use. I take no responsibility tho.
* I has an automatic installer.
* It uses PHP's `password_hash()` function to hash and salt the passwords. Usernames are saved as a plain text.
  * I'm using `PASSWORD_DEFAULT` which at time of the writing uses BCRYPT.
* It requires MySQL database. More info about setting up your database below.
* I'm using the PDO interface. You also need PDO_MYSQL driver installed.
* It requires PHP 7.0 or newer.
  * You can use PHP versions all the way down to 5.5.0 if you use [random_compat](https://github.com/paragonie/random_compat) (or similar) library.
* Users can change their username (if allowed via config) and password.

## File structure

_AKA 'Why there are so many files?'_

#### /admin
The admin panel will be here. Similarly to many other web applications (like WordPress) the admin panel can be easily accessed by typing _[host]/admin_. 
#### /config
This folder includes two files that are used to set some config options. More info below.
#### /css
Just some CSS goodies for the UI.
#### /install
The automatic installer resides here.
#### /js
This folder has all .js files needed for the user interface to work.
#### /utils
This folder includes the backbone of the system. It has .php files for connecting to the database, login in and out, registering/creating a new account and modifying existing accounts. There's also scripts.js that has some general JavaScript goodies for the user interface. You need to insert your database info to credentials.php, check the setup section down below for help.
#### LICENSE
You should read this before using this. It's just a normal MIT license tho.
#### README.md
This file.
#### account.php
This is the account management page. Users can change their username and password here.
#### index.php
This file is here just for the demo. The users can only access this page if they are logged in. Otherwise they are redirected to the login page.
#### login.php
My take on creating a simple login form with Bootstrap. Feel free to modify it to fit your needs.
#### register.php
My take on creating a simple registration form with Bootstrap. Feel free to modify it to fit your needs.
#### removeInstall.php
This is used by the automatic installer to remove itself after the installation is complete.
#### wordlist.txt
This is the list of the most common English words. It's used by the username suggestor. You can replace the list with your own .txt file. Every word on the list needs to be followed by line break.

### Config options

_Please note that config options are subject to change. Check back often._

If you check the /config folder, you'll notice that there are two different files there. What gives? The main configuration file is **config.php**. It includes settings that are enforced on the server level. You should be mainly editing this file.

If you are also using the front-end user interface I provide, then you can/must also edit **config.js** There you can manage how things look for the avarage user. **Please remember that these settings are client-side only and _not_ enforced in any way so they can be edited by users.**

#### Config options in config.php
| Option name                  | Description                                                | Default value| Supported values  |
|------------------------------|------------------------------------------------------------|--------------|-------------------|
| $disableUserSelfRegistration | Prevent users from registering                             | false        | Boolean           |
| $usernameMinLength           | Shortest allowed username                                  | 3            | 1 ->              |
| $usernameMaxLength           | Longest allowed username                                   | 30           | 1 ->              |
| $passwordMinLength           | Minimum length of passwords                                | 8            | 1 ->              |
| $usernameRegExp              | All usernames must match this regular expression           |              | any regExp        |
| $passwordRegExp              | All passwords must match this regular expression           |              | any regExp        |
| $newAccountAccessLevel       | Useful for creating your first admin account               | "user"       | "user", "admin"   |
| $debugMode                   | Allows you to disable dabase connection (for debuging only)| "no"         | "no"              |
| $debugAdminUsername          | Allows you to log in while in debug mode                   | "admin"      | any string        |
| $debugAdminPassword          | Allows you to log in while in debug mode                   | ""           | any string        |
| $debugSkipInstall            | This is for debug purposes only                            | false        | false             |
| $dateSeperator               | The seperator between numbers in dates. (eg. "/" or ".")   | "."          | any string        |
| $timeSeperator               | The seperator between numbers in times.                    | ":"          | any string        |
| $mmddyyyy                    | Save dates in MMDDYYYY format instead of DDMMYYYY          | false        | Boolean           |
| $timeout                     | Time of inactivity (in seconds) required to log user out   | 900          | any integer       |
| $adminPanelTimeout           |Time of inactivity required to log user out from admin panel| 450          | any integer       |
| $errorMessages               | Show more verbose error messages.Might leak sensitive info!| default      |"default","verbose"|
| $allowUsernameChange         | Should user's be able to change their username             | true         | Boolean           |
| $forceHTTPS                  |Redirects all non-HTTPS connections to HTTPS and sends HSTS | false        | Boolean           |

##### About $forceHTTPS
In 2018 it's considered a huge security risk to not use HTTPS when dealing with any kind of sensitive information (like passwords). That's why it's very highly recommended to only use hosting solutions that support it and change this option to true. Nowadays you can even get the SSL certificate completely free from [Let's Encrypt](https://letsencrypt.org/), so there's no reasons to not use it. However, on some (badly-configured) environments SERVER\["HTTPS"] superglobal is not defined even when HTTPS is in fact used. That results in a endless loop of redirecting. I myself learned that the hard way. That's why this setting is not enabled by default.


#### Config options in config.js
| Option name                 | Description                                                 | Default value | Supported values |
|-----------------------------|-------------------------------------------------------------|---------------|------------------|
| disableUserSelfRegistration | Disables any UI elements related to registration            | false         | true, false      |
| usernameMinLength           | Shortest username that UI accepts                           | 3             | 1 ->             |
| usernameMaxLength           | Longest username that UI accepts                            | 30            | 1 ->             |
| passwordMinLength           | Shortest password that UI accepts                           | 8             | 1 ->             |
| usernameRules               | This string is shown if username didn't match the regExp    |               | any string       |
| passwordRules               | This string is shown if password didn't match the regExp    |               | any string       |
| enableUsernameSuggestions   | Allows you to disable or enable username suggestions        | true          | Boolean          |
| allowUsernameChange         | Should user's be able to change their username (UI only)    | true          | Boolean          |
| enableLoginMessage          | Display any message on the login page                       | false         | Boolean          |
| loginMessage                | Define the message to show when enableLoginMessage is true  | ""            | any string       |

## Setup

As stated earlier, you'll need a MySQL database. The database does not require a lot if space (unless you have LOTS of users) and any fairly recent version of MySQl should work. You can set up your database and create admin account manually, or you can use my automatic installer.

### Using the automatic installer
0. Have a MySQL database that you have access to.
1. Navigate (using browser) to the root of the file structure. You will be redirected to the install wizard.  
    * You can can also directly navigate to **/install/**.
2. Follow on-screen instructions.

During the first step of the installation the installer needs to write a file to your host's drive. If you don't have proper permissions for that, you must do the install manually. See the instructions below.

### Setting up the database manually
0. Have a MySQL database that you have access to.
1. Create table `users` with five colums: `username` , `password` , `accessLevel` , `lastLogin` and `rememberMeToken`. Use a string data type like CHAR. I personally like to use VARCHAR. I would also add a auto incrementing id field but that is not strictly required.
2. Insert your database hostname, port, name and credentials into **/utils/credentials.php**. I recommend using a dedicated account with restricted permissions.

#### Field lengths
If you are using VARCHAR or other data type with varying maximum string length, then the table below will be useful.

| Field          | Required length (minimum)                                                        |
|----------------|----------------------------------------------------------------------------------|
| username       | Same as $usernameMaxLength in config.php                                         |
| password       | I recommend using 255 to be safe (As PHP's default crypting method might change) |
| accessLevel    | 5                                                                                |
| lastLogin      | 16                                                                               |
| rememberMeToken| 255                                                                              |

#### Don't know your SQL?
Don't worry, something like this should get you covered:
````SQL
CREATE TABLE IF NOT EXISTS users (
 id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 username VARCHAR(64) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 accessLevel VARCHAR(10) NOT NULL,
 lastLogin VARCHAR(16),
 rememberMeToken VARCHAR(255)
);
````

#### Creating the first admin account
After setting up the database, you'll need to create your first admin account. There's two ways to do that:
* Navigate to **/install/createAdmin.php**. That will create a new account with admin rights using username ````admin```` and a  randomly generated password. (If you followed my SQL-sample above and set UNIQUE constraint to the username field, then the account won't be created if it already exists. You won't get error message) After you have created the admin account, you must remove (or rename) the /install folder. Finally, log into to the newly created account and change the password using the account management page.

**OR**
* If createAdmin.php does not work for some reason, you can also temporarily change ````$newAccountAccessLevel```` in **config/config.php** to "admin" and then create a new account using the normal registration form. You need to remove (or rename the /install folder to be able to access the login page. Remember to change the value back to "user" afterwards.

**IMPORTANT!**
I remind you again that you **MUST** delete the /install folder before using this in live production environment. **Otherwise anyone can see your database credentials!**

After setup you can create new accounts (admin or normal) using the admin panel.

### Setup FAQ

#### "Your random_bytes function is not working properly"
This means that installer noticed that the function `random_bytes(int)` does not exist or work properly. If you are using PHP version older than 7.0 you must use 3rd party library that implements that function. For PHP 5.x I recommend [this one](https://github.com/paragonie/random_compat).

#### I don't know what port my database is using.
MySQL default is 3306.

#### Installer wizard starts from the beginning after completing it.
This happens when the wizard fails to remove itself. That is usually caused by some restrictive permissions on the host. Fix the problem by manually removing the /install folder.

#### My database connection does not work.
Check troubleshooting tips in general FAQ below.

## The admin panel

_The admin panel is in very early stage of the development. Many things might be broken._

### Admin panel allows admins to...
* See all user accounts, their access level and last login time
* Modify user's access level and reset their last login time
* Create new accounts
* Delete user accounts

More to be added in the future.

### How do I access it?
_/admin_

## General FAQ
### How do I turn on the debug mode?
Read config.php more carefully.

### I'm getting 'Connection error occured' message or the automatic installer throws error(s) when trying to connect to my database.
That means that `PDOException` occured while trying to connect to the database. You can turn more verbose error messages on in **/config/config.php**. The most common causes are:
1. Wrong database hostname, port, name or credentials.  
 Double check them.
2. The database is down.  
 Can you access your database by using phpMyAdmin or similar?
3. PHP's PDO interface or `PDO_MYSQL` driver is not available or configured properly.  
 Try updating to the latest version of PHP if possible. Make sure you or your host enable `PDO_MYSQL`.
4. Your host's firewall is blocking the connection.  
 Make sure that your or your host's firewall is not blocking the connection. Please note that some hosts disallow MySQL connections to outside their infrastructure.  
5. Something else.  
 Please open issue here on GitHub i and I try to figure it out.
 
### Does the "Remember me" option on the login page even work?
~~No, it doesn't and it's been that way far too long, I know. I'm planning to get to it, soon™.~~
Update: It's working now. Please note that it only remembers the login for 30 days (For security reasons).

### Isn't that Remember me option a huge security risk?
Yes, I know that implementing something like that always opens new security holes. However, I'm not forcing users to use it or anything. If the user doesn't check the checkbox, no access token is created so there's no security risk for that user.
#### This is how the remember me option works:
0. User logs in with their username and password and they check to checkbox.
1. New token is created for that user using cryptographically secure `random_bytes()` function. That token is saved to the database and two cookies are sent to the browser. The value of the first cookie is the username of the user as a plain text. The second cookie is much more important. It's value is the created token.
2. User who has these two cookies arrives to the login page. If the token in the users cookie matches the token in the database the user is automatically logged in.
3. The token is invalidated if user manually logs out or 30 days has passed.
#### What are the risks?
The main problem is that if "the bad guy" is somehow able to get access to the user's token they can easily forge a cookie and log in as that user. There are two ways for the bad guy to get access to user's token: By somehow (e.g. SQL injection) getting it from the database or by stealing the cookie and/or it's value from the user.
#### What I'm doing to mimimize these risks?
* No token is created if user does not check the checkbox.
* Tokens are invalidated if user manually logs out or their session timeouts.
* If HTTPS is used, secure flag is set to the cookies.
* All tokens can be easily invalidated by the admin.
* Updates coming soon...

### Dates and/or times are wrong!
Notice that on default settings the dates are saved in European format. (Day before month) You can change that behavior with a config option in **config.php**.
Also, the saved time is the **server's time, not your/user computer's**. If your host is in different time zone than you then the the times will be offset. I'm planning to add $timeOffset config option to help with this problem.

### Why I can't set my own password when creating a account via admin panel?
I consider it a security risk as some people would be inclined to use too simple or same passwords. However, I might allow this in future versions via a config option.
