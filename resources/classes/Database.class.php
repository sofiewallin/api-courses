<?php
/*
* MariaDB database class with one connection.
*
* Code by Sofie Wallin (sowa2002), student at MIUN, 2021.
*/
require_once('./resources/config.php');

class Database {

    /*------ Properties ------*/

    private $connection = NULL;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $name = DB_NAME;
    private $charset = 'utf8mb4';

    /*------ Methods ------*/

    // Constructor: Connect to database
    public function __construct() { 
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->name);
        $this->connection->set_charset($this->charset);
        if($this->connection->connect_errno > 0) {
            die('Fel vid anslutning: ' . $this->connection->connect_error);
        }
    }

    // Get connection
    public function getConnection() {
        return $this->connection;
    }

    // Destructor: Close database
    public function __destruct() {
        $this->connection->close();
    }
}