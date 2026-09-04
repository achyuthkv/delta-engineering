<?php
// Expects $page_title to be set before including this file.
require_once __DIR__ . '/auth.php';
$admin = de_current_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?= htmlspecialchars($page_title ?? 'Admin', ENT_QUOTES, 'UTF-8') ?> | Delta Engineering Admin</title>
	<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<?php if ($admin): ?>
	<div class="de-admin-topbar">
		<a href="index.php">Delta Engineering &mdash; Admin</a>
		<nav>
			<a href="index.php"<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? ' class="active"' : '' ?>>Dashboard</a>
			<a href="projects.php"<?= in_array(basename($_SERVER['PHP_SELF']), ['projects.php', 'project-edit.php']) ? ' class="active"' : '' ?>>Projects</a>
			<a href="gallery.php"<?= in_array(basename($_SERVER['PHP_SELF']), ['gallery.php', 'gallery-edit.php']) ? ' class="active"' : '' ?>>Gallery</a>
			<span>Signed in as <?= htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8') ?></span>
			<a href="logout.php">Log out</a>
		</nav>
	</div>
<?php endif; ?>
<div class="de-admin-main">
