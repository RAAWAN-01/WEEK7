<?php
session_start();
require __DIR__ . '/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $full_name  = trim($_POST['full_name'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($student_id === '' || $full_name === '' || $password === '') {
        $errors[] = 'All fields are required.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO students (student_id, full_name, password_hash)
                 VALUES (:student_id, :full_name, :password_hash)"
            );
            $stmt->execute([
                ':student_id'   => $student_id,
                ':full_name'    => $full_name,
                ':password_hash'=> $hash
            ]);

            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'Student ID already exists.';
            } else {
                $errors[] = 'Registration failed.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Student Registration</h2>
<?php if ($errors): ?>
<div class="error"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
<?php endif; ?>
<form method="post">
    <label>Student ID</label>
    <input type="text" name="student_id" required>
    <label>Full Name</label>
    <input type="text" name="full_name" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Register</button>
</form>
<p><a href="login.php">Already registered? Login</a></p>
</body>
</html>