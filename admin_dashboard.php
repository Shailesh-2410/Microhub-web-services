<?php
session_start();
include 'db.php'; // Database connection

// Ensure only admin can access this page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Access denied. Only admin can manage users.");
}

// Fetch all users
$stmt = $pdo->prepare("SELECT id, username, role FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard - Manage Users</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user["id"] ?></td>
                <td><?= htmlspecialchars($user["username"]) ?></td>
                <td>
                    <form method="POST" action="update_role.php">
                        <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
                        <select name="new_role">
                            <option value="student" <?= $user["role"] == "student" ? "selected" : "" ?>>Student</option>
                            <option value="staff" <?= $user["role"] == "staff" ? "selected" : "" ?>>Staff</option>
                            <option value="admin" <?= $user["role"] == "admin" ? "selected" : "" ?>>Admin</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td>
                    <?php if ($user["role"] === "student"): ?>
                        <form method="POST" action="delete_user.php">
                            <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this student?');">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
