# allPREF Basic Authentication

Basic Authentication using PHP 7, Silex, Twig and Doctrine ORM.

**See online: https://www.allpref.com.br/basicauth/ **

Created by [Guga Zimmermann](https://github.com/allprefsistemas/)

---

### Public area:
  - Basic login with encrypted password in the database
  - Recover Password: Send a new password to the user email
  - Simples Sign In form

### Restricted area:
  - Dashboard with a list of users
  - Profile page to change user details and password
  - Change user avatar by file or webcam

---

### Configuration
* PHP dependencies: **composer install**
* Javascript dependencies: **bower install**
* Gulp dependencies: **npm install**

* Change **bootstrap.php** for the database access.
* Change **config.php**
    - **path**, **email configuration** and define DEBUG to false.
* Create the database and generate the tables with Doctrine _orm:schema-tool:create_

License
----
WTFYW

**Free, Hell Yeah!**

----
For questions or do not know what to do with the files: **search on google!**

Or if you prefer, send email to contato@allpref.com.br and hire me.
