<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();
$projectCount = (int) $db->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$photoCount = (int) $db->query('SELECT COUNT(*) FROM gallery_photos')->fetchColumn();
$byOffice = $db->query('SELECT office, COUNT(*) AS n FROM gallery_photos GROUP BY office')->fetchAll();
$officeCounts = ['canada' => 0, 'oman' => 0, 'india' => 0];
foreach ($byOffice as $row) {
	$officeCounts[$row['office']] = (int) $row['n'];
}

$page_title = 'Dashboard';
require __DIR__ . '/includes/layout-top.php';
?>

<h1>Dashboard</h1>
<p class="de-admin-sub">Manage the Projects list and the three gallery pages (Canada, Oman, India) without touching code.</p>

<div class="de-admin-stats">
	<div class="de-admin-stat"><div class="n"><?= $projectCount ?></div><div class="l">Project Entries</div></div>
	<div class="de-admin-stat"><div class="n"><?= $photoCount ?></div><div class="l">Gallery Photos</div></div>
	<div class="de-admin-stat"><div class="n"><?= $officeCounts['canada'] ?></div><div class="l">Canada Photos</div></div>
	<div class="de-admin-stat"><div class="n"><?= $officeCounts['oman'] ?></div><div class="l">Oman Photos</div></div>
	<div class="de-admin-stat"><div class="n"><?= $officeCounts['india'] ?></div><div class="l">India Photos</div></div>
</div>

<div class="de-admin-card">
	<h2 style="margin-top:0">Projects</h2>
	<p class="de-admin-sub" style="margin-bottom:16px">Line items shown in the accordion on the public Projects page, grouped by category.</p>
	<a href="projects.php" class="de-btn secondary">Manage Projects</a>
	<a href="project-edit.php" class="de-btn">Add a Project</a>
</div>

<div class="de-admin-card">
	<h2 style="margin-top:0">Gallery Photos</h2>
	<p class="de-admin-sub" style="margin-bottom:16px">Photos shown on the Canada Projects gallery and the Oman / India sections of the International gallery.</p>
	<a href="gallery.php" class="de-btn secondary">Manage Gallery</a>
	<a href="gallery-edit.php" class="de-btn">Add a Photo</a>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
