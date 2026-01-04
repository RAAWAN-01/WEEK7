<?php
session_start();
if (!($_SESSION['logged_in'] ?? false)) {
    header('Location: login.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = ($_POST['theme'] ?? 'light') === 'dark' ? 'dark' : 'light';
    setcookie('theme', $value, time() + 86400 * 30, '/', '', false, true);
    $_COOKIE['theme'] = $value;
    $message = "Theme updated to $value.";
}
$theme = $_COOKIE['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Preferences</title>
<link rel="stylesheet" href="styles.css">
</head>
<body style="<?php echo $theme === 'dark' ? 'background:#111;color:#f5f5f5;' : 'background:#fff;color:#222;'; ?>">
<h2>Theme Preference</h2>
<?php if ($message): ?><div class="success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
<form method="post">
  <label>Select Theme</label>
  <select name="theme">
    <option value="light" <?php echo $theme === 'light' ? 'selected' : ''; ?>>Light</option>
    <option value="dark" <?php echo $theme === 'dark' ? 'selected' : ''; ?>>Dark</option>
  </select>
  <button type="submit">Save</button>
</form>
<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>