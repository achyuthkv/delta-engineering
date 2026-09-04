<?php
require_once __DIR__ . '/includes/auth.php';

if (de_current_admin()) {
	header('Location: index.php');
	exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	de_csrf_check();
	$username = trim($_POST['username'] ?? '');
	$password = $_POST['password'] ?? '';

	if (de_attempt_login($username, $password)) {
		header('Location: index.php');
		exit;
	}
	$error = 'Incorrect username or password.';
}

$page_title = 'Log in';
require __DIR__ . '/includes/layout-top.php';
?>

<div class="de-admin-login">
	<h1>Delta Engineering Admin</h1>
	<div class="de-admin-card">
		<?php if ($error): ?>
			<div class="de-admin-flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
		<?php endif; ?>
		<form class="de-admin-form" method="post" action="login.php">
			<?= de_csrf_field() ?>
			<label for="username">Username</label>
			<input type="text" id="username" name="username" required autofocus>

			<label for="password">Password</label>
			<input type="password" id="password" name="password" required>

			<div class="actions-row">
				<button type="submit" class="de-btn">Log in</button>
			</div>
		</form>
	</div>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
