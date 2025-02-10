<?php
session_start();
include 'db.php'; // Include database connection

// Ensure only staff can upload marks
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "staff") {
    die("Access denied. Only staff members can upload marks.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $subject = $_POST["subject"];
    $marks = $_POST["marks"];
    $staff_id = $_SESSION["user_id"]; // Get logged-in staff ID

    // Check if student exists
    $check_student = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'student'");
    $check_student->execute([$student_id]);
    $studentExists = $check_student->fetchColumn();

    if (!$studentExists) {
        echo "<p style='color:red;'>Error: Student ID does not exist!</p>";
    } else {
        // Insert marks with staff ID
        $stmt = $pdo->prepare("INSERT INTO marks (student_id, subject, marks, staff_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$student_id, $subject, $marks, $staff_id])) {
            echo "<p style='color:green;'>Marks uploaded successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error uploading marks.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Marks</title>
</head>
<body>
    <h2>Upload Student Marks</h2>
    <form method="POST">
        <label>Student ID:</label>
        <input type="number" name="student_id" required><br>

        <label>Subject:</label>
        <input type="text" name="subject" required><br>

        <label>Marks:</label>
        <input type="number" name="marks" required><br>

        <button type="submit">Upload</button>
    </form>
</body>
</html>
