<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

function de_current_admin(): ?array {
	return $_SESSION['admin_user'] ?? null;
}

function de_require_login(): void {
	if (!de_current_admin()) {
		header('Location: login.php');
		exit;
	}
}

function de_attempt_login(string $username, string $password): bool {
	$stmt = de_db()->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ?');
	$stmt->execute([$username]);
	$user = $stmt->fetch();

	if (!$user || !password_verify($password, $user['password_hash'])) {
		return false;
	}

	session_regenerate_id(true);
	$_SESSION['admin_user'] = ['id' => $user['id'], 'username' => $user['username']];
	return true;
}

function de_logout(): void {
	$_SESSION = [];
	session_destroy();
}

function de_csrf_token(): string {
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function de_csrf_field(): string {
	return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(de_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function de_csrf_check(): void {
	$token = $_POST['csrf_token'] ?? '';
	if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
		http_response_code(400);
		exit('Invalid or expired form submission. Go back and try again.');
	}
}
