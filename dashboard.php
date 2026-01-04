<?php
session_start();
require __DIR__ . '/db.php';

// Redirect if not logged in
if (!($_SESSION['logged_in'] ?? false)) {
    header('Location: login.php');
    exit;
}

// Fetch all students for display
$studentsStmt = $pdo->query("SELECT student_id, full_name FROM students");
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch grades for the logged-in student
$gradesStmt = $pdo->prepare("SELECT subject, grade FROM grades WHERE student_id = :student_id");
$gradesStmt->execute([':student_id' => $_SESSION['student_id']]);
$grades = $gradesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 10px; }
        h2 { color: #2c3e50; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 5px 0; }
        .grades { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h2>

    <nav>
        <a href="dashboard.php">Dashboard</a> |
        <a href="preference.php">Theme Preference</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <h3>Registered Students</h3>
    <ul>
        <?php foreach ($students as $s): ?>
            <li><?php echo htmlspecialchars($s['student_id']) . " - " . htmlspecialchars($s['full_name']); ?></li>
        <?php endforeach; ?>
    </ul>

    <div class="grades">
        <h3>Your Grades</h3>
        <?php if (!$grades): ?>
            <p>No grades available yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($grades as $g): ?>
                    <li><?php echo htmlspecialchars($g['subject']) . ": " . htmlspecialchars($g['grade']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>