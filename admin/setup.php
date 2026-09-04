<?php
// One-time bootstrap: creates the first admin account. Once any row exists
// in admin_users, this script refuses to run again -- there's no shell
// access on shared cPanel hosting, so this is how the very first login
// gets created after importing schema.sql.
require_once __DIR__ . '/includes/db.php';

$db = de_db();
$existing = (int) $db->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();

if ($existing > 0) {
	http_response_code(403);
	exit('An admin account already exists. Go to login.php. (If you\'ve lost access, create a new password hash with PHP\'s password_hash() and update the admin_users table directly via phpMyAdmin.)');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = $_POST['password'] ?? '';
	$confirm = $_POST['confirm'] ?? '';

	if (strlen($username) < 3) {
		$error = 'Username must be at least 3 characters.';
	} elseif (strlen($password) < 10) {
		$error = 'Password must be at least 10 characters.';
	} elseif ($password !== $confirm) {
		$error = 'Passwords do not match.';
	} else {
		$stmt = $db->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
		$stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
		header('Location: login.php?created=1');
		exit;
	}
}

$page_title = 'Setup';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="de-admin-login">
	<h1>Create Admin Account</h1>
	<p class="de-admin-sub" style="text-align:center">This runs once -- it locks itself after the first account is created.</p>
	<div class="de-admin-card">
		<?php if ($error): ?>
			<div class="de-admin-flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
		<?php endif; ?>
		<form class="de-admin-form" method="post" action="setup.php">
			<label for="username">Username</label>
			<input type="text" id="username" name="username" required minlength="3" autofocus>

			<label for="password">Password</label>
			<input type="password" id="password" name="password" required minlength="10">
			<div class="hint">At least 10 characters.</div>

			<label for="confirm">Confirm password</label>
			<input type="password" id="confirm" name="confirm" required minlength="10">

			<div class="actions-row">
				<button type="submit" class="de-btn">Create Account</button>
			</div>
		</form>
	</div>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
