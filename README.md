# MedLog

MedLog is a PHP application that, alongside a MySQL database, allows several users
to register and have their own, private drug journal.

MedLog is now up and running [here](http://medlog.byethost12.com)!

This can be used for logging all ingested drugs, both legal or illegal. It has
many purposes, the main ones being:

* Help people who might not remember if they have already taken their medication.

* Harm reduction - elaborate trip reports, etc.

You can easily set-up your own instance of MedLog by modifying the MySQLi
connection lines in `include/usercontrol.php` and `include/entries.php`.

**(NEW)** Now you can generate a trip report! Then, you can export it to an HTML or TXT file.

## How to install?
You'll just need a simple HTTP, PHP and MySQL server - my choice for testing and developing is usually LAMP. 

1. Clone or HTTP download this project from GitHub to your `htdocs` directory: `$ git clone https://github.com/unrar/medlog.git` 

2. Create a `medlog` database and run the queries in `medlog.sql` on it. This can be done via SQL command line or PHPMyAdmin.

3. Edit the `include/entries.php` and `include/usercontrol.php` lines regarding the MySQL connection and modify them to suit your server.

## Screenshots

Home page:

![Home page](http://i.imgur.com/ZGQH9IS.png)

My journal (don't take it too seriously):

![My Journal](http://i.imgur.com/LUvPrCR.png)

Editing an entry:

![Editing an entry](http://i.imgur.com/b0Bar0k.png)

Selecting entries for a trip report:

![Trip selection](http://i.imgur.com/d8dNQ7V.png)

Downloaded HTML trip report:

![HTML Trip](http://i.imgur.com/3taHFuX.png)

Downloaded TXT trip report:

![TXT Trip](http://i.imgur.com/g1PXaHR.png)

## TODO
* ~~Set MedLog up in a public hosting.~~ (**It's up now!**)

* Improve CSS.

* ~~Add option to create a trip report (all entries from a given date)~~ (**Added on v0.2!**).
