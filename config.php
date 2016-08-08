<?php
define('DEBUG', true);

$path = 'http://localhost/allpref/authentication';

define('URL_BASE', $path);
define('URL_PUBLIC', URL_BASE . '/public');
define('URL_AUTH', URL_BASE . '/auth');

define('HASHID_SALT', '+U}cNHDZ-*63N$5wL\=5w@u5q');
define('HASHID_LEVEL', 16);

define('EMAILHOST', 'smtp.gmail.com');
define('EMAILSMTPAUTH', true);
define('EMAILSMTPSECURE', 'ssl');
define('EMAILPORT', 465);
define('EMAIL', 'email@gmail.com');
define('EMAILPASS', '');
define('EMAILFROM', 'allPref');
