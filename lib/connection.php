<?php

class Connection {
    private $servername = "localhost";
    private $username  = "root";
    private $password = "";
    private $dbname = "micro";
    public $conn;

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_STRICT); // Enable exceptions for mysqli errors
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        } catch (\mysqli_sql_exception $e) { // Use global namespace for the exception
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>