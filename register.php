<?php
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]); // Trim spaces
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encrypt password
    $role = $_POST["role"]; // Role: 'student', 'staff', or 'admin'

    // Check if the username already exists
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check_stmt->execute([$username]);
    $userExists = $check_stmt->fetchColumn();

    if ($userExists > 0) {
        echo "<p style='color:red;'>Error: Username already exists! Choose a different username.</p>";
    } else {
        // Insert the new user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $password, $role])) {
            echo "<p style='color:green;'>User registered successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error registering user.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
</head>
<body>
    <h2>Register a New User</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Role:</label>
        <select name="role">
            <option value="student">Student</option>
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
