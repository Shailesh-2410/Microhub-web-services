<?php
session_start();
include 'db.php'; // Database connection

// Ensure only students can view marks
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    die("Access denied. Only students can view marks.");
}

$student_id = $_SESSION["user_id"];

// Fetch marks for the logged-in student
$stmt = $pdo->prepare("SELECT subject, marks FROM marks WHERE student_id = ?");
$stmt->execute([$student_id]);
$marks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Marks</title>
</head>
<body>
    <h2>Your Marks</h2>

    <?php if (empty($marks)): ?>
        <p>No marks found.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>
            <?php foreach ($marks as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row["subject"]) ?></td>
                    <td><?= htmlspecialchars($row["marks"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
