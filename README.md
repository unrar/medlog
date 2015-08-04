# MedLog

MedLog is a PHP application that, alongside a MySQL database, allows several users
to register and have their own, private drug journal.

This can be used for logging all ingested drugs, both legal or illegal. It has
many purposes, the main ones being:

* Help people who might not remember if they have already taken their medication.

* Harm reduction - elaborate trip reports, etc.

You can easily set-up your own instance of MedLog by modifying the MySQLi
connection lines in `include/usercontrol.php` and `include/entries.php`.

**(NEW)** Now you can generate a trip report! Then, you can export it to an HTML or TXT file.

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
* Set MedLog up in a public hosting.

* Improve CSS.

* ~~Add option to create a trip report (all entries from a given date)~~ (**Added on v0.2!**).
