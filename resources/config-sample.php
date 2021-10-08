<?php
/*
* CONFIGURATIONS FILE
*
* Code by Sofie Wallin (sowa2002), student at MIUN, 2021.
*
* --------------------- Instructions ---------------------
*
* To be able to use this file properly make sure to enter
* your own database connection information down below. You
* can enter information for both a local connection and a 
* production connection. Toggle development_mode to decide
* what information is currently being used.
*
* When you have entered all the correct information make 
* sure to rename this file from config-sample.php to 
* config.php. Your ready to go!
*
*/

/*------ Development mode ------*/

$development_mode = true; 

/* Check if development mode */
if($development_mode) {
    // Error reporting
    error_reporting(-1);
    ini_set('display_errors', 1);

    // Local database connection
    define('DB_HOST', 'localhost');
    define('DB_USER', 'your_local_database_user');
    define('DB_PASSWORD', 'your_local_database_password');
    define('DB_NAME', 'your_local_database_name');
} else {
    // Production database connection
    define('DB_HOST', 'your_database_host');
    define('DB_USER', 'your_database_user');
    define('DB_PASSWORD', 'your_database_password');
    define('DB_NAME', 'your_database_name');
}

/*------ Autoloading of class files ------*/

spl_autoload_register(function($class_name) {
    include("classes/$class_name.class.php");
});