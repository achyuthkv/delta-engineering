<?php
// Shared PDO connection, used by both the admin panel and the public pages
// that render CMS content (projects.php, gallery_*.php).

function de_db(): PDO {
	static $pdo = null;
	if ($pdo !== null) {
		return $pdo;
	}

	$credentials = __DIR__ . '/../db-credentials.php';
	if (!file_exists($credentials)) {
		throw new RuntimeException('admin/db-credentials.php is missing -- copy db-credentials.example.php and fill in your cPanel MySQL details.');
	}
	require_once $credentials;

	$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
	$pdo = new PDO($dsn, DB_USER, DB_PASS, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);
	return $pdo;
}
