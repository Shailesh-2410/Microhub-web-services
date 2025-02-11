<?php

session_start(); 

require_once 'lib/connection.php';

class AdminController {
    private $db;
    public function __construct() {
        $this->db = new Connection();
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['admin'] = json_encode($user); // Store user info in session
            return true;
        } else {
            return false;
        }
    }

    public function getAdminSession() {
        if (isset($_SESSION['admin'])) {
            return json_decode($_SESSION['admin'], true);
        }
        return null;
    }

    public function getAllAdmins() {
        try {
            $sql = "SELECT * FROM admins";
            $result = $this->db->conn->query($sql);
            return $result;
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
            return false;
        }
    }
}
?>