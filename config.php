<?php
//config.php file contains all DB connection details and will be used and included automatically in other files

// Check if DB_SERVER is not already defined, if not then define it
// This constant represents the hostname of your database server
defined('DB_SERVER') ? null : define('DB_SERVER', 'localhost');

// Check if DB_NAME is not already defined, if not then define it
// This constant represents the name of your database
defined('DB_NAME') ? null : define('DB_NAME', 'Your_DB_NAME');

// Check if DB_USER is not already defined, if not then define it
// This constant represents the username for accessing your database
defined('DB_USER') ? null : define('DB_USER', 'Your_DB_USER');

// Check if DB_PASS is not already defined, if not then define it
// This constant represents the password for accessing your database
defined('DB_PASS') ? null : define('DB_PASS', 'Your_DB_PASS');

// Set the default timezone to use. Available since PHP 5.1
// This is important for any date or time function calls
date_default_timezone_set('America/Chicago');

// End of config.php
