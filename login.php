<?php
session_start();
require __DIR__ . '/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $password   = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT full_name, password_hash FROM students WHERE student_id = :student_id");
    $stmt->execute([':student_id' => $student_id]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['student_id'] = $student_id;
        $_SESSION['full_name']  = $user['full_name'];
        session_regenerate_id(true);
        header('Location: dashboard.php');
        exit;
    } else {
        $errors[] = 'Invalid student ID or password.';
    }
}
$registered = isset($_GET['registered']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Login</h2>
<?php if ($registered): ?><div class="success">Registration successful. Please log in.</div><?php endif; ?>
<?php if ($errors): ?><div class="error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div><?php endif; ?>
<form method="post">
    <label>Student ID</label>
    <input type="text" name="student_id" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
<p><a href="register.php">New user? Register</a></p>
</body>
</html>