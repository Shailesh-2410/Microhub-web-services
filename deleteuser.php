<?php
session_start();
include 'db.php';

// Ensure only admin can delete students
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;

    if (!$user_id) {
        die("Invalid request.");
    }

    // Check if the user exists and is a student
    $check = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $check->execute([$user_id]);
    $user = $check->fetch(PDO::FETCH_ASSOC);

    if ($user && $user["role"] === "student") {
        // Delete the student from the database
        $delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($delete->execute([$user_id])) {
            echo "<script>alert('Student deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error deleting student.'); window.location.href='admin_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid student ID.'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>
