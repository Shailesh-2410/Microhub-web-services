<?php
session_start();
include 'db.php';

// Ensure only admin can update roles
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Access denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"] ?? null;
    $new_role = $_POST["new_role"] ?? null;

    if (!$user_id || !$new_role) {
        die("Invalid request.");
    }

    // Update the role in the database
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    if ($stmt->execute([$new_role, $user_id])) {
        echo "<script>alert('User role updated successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating role.'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>
