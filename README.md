zf1-zf2-book-2
==============

The Book module of the ZF 2 application that holds the conversion results of the ZF 1 -> ZF 2 migration described in the book and some additional examples.

Installation
========
1. Download or clone the ZendSkeletonApplication
2. Clone this project into the module directory, clone as 'Book'
3. Enable the 'Book' module in config/application.config.php
4. Disable the 'Application' module in config/application.config.php (same place as step 3)
5. Use composer to install ZfcUser (once you get to that section in the book)
6. Download the FirePHPCore library and put it in the 'vendor' directory
 
Database setup
==============
Create a database called 'book'.

Create a table person:

CREATE TABLE `person` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8

Later, you might install ZfcUser and add a 'user' table following their instructions.

Configuration
=============
Put below contents in config/autoload/local.php

    <?php
    /**
     * Local Configuration Override
     *
     * This configuration override file is for overriding environment-specific and
     * security-sensitive configuration information. Copy this file without the
     * .dist extension at the end and populate values as needed.
     *
     * @NOTE: This file is ignored from Git by default with the .gitignore included
     * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
     * credentials from accidentally being committed into version control.
     */

    return array(
        'view_manager' => array(
            'display_exceptions'       => true,
            'display_not_found_reason' => true,
        ),
        'db_password' => '*******', // your db password here
    );



// end of local.php
