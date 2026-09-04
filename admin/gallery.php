<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
	de_csrf_check();
	$stmt = $db->prepare('SELECT image_path FROM gallery_photos WHERE id = ?');
	$stmt->execute([(int) $_POST['id']]);
	$row = $stmt->fetch();

	$stmt = $db->prepare('DELETE FROM gallery_photos WHERE id = ?');
	$stmt->execute([(int) $_POST['id']]);

	// Only remove the file from disk if it was uploaded through this admin
	// (lives under gallery_uploads/) -- never delete the original seeded
	// site images, since other pages may still reference them by path.
	if ($row && str_starts_with($row['image_path'], 'assets/images/gallery_uploads/')) {
		$full = __DIR__ . '/../' . $row['image_path'];
		if (is_file($full)) {
			@unlink($full);
		}
	}

	header('Location: gallery.php?deleted=1');
	exit;
}

$office = $_GET['office'] ?? '';
if (in_array($office, ['canada', 'oman', 'india'], true)) {
	$stmt = $db->prepare('SELECT * FROM gallery_photos WHERE office = ? ORDER BY sort_order, id');
	$stmt->execute([$office]);
} else {
	$office = '';
	$stmt = $db->query('SELECT * FROM gallery_photos ORDER BY office, sort_order, id');
}
$photos = $stmt->fetchAll();

$page_title = 'Gallery';
require __DIR__ . '/includes/layout-top.php';
?>

<h1>Gallery Photos</h1>
<p class="de-admin-sub">Feeds the <a href="../gallery_canada_projects.php" target="_blank">Canada gallery</a> and the Oman / India sections of the <a href="../gallery_international_projects.php" target="_blank">international gallery</a>.</p>

<?php if (isset($_GET['deleted'])): ?>
	<div class="de-admin-flash ok">Photo deleted.</div>
<?php elseif (isset($_GET['saved'])): ?>
	<div class="de-admin-flash ok">Photo saved.</div>
<?php endif; ?>

<div class="de-admin-toolbar">
	<div class="filters">
		<a href="gallery.php" class="<?= $office === '' ? 'active' : '' ?>">All</a>
		<a href="gallery.php?office=canada" class="<?= $office === 'canada' ? 'active' : '' ?>">Canada</a>
		<a href="gallery.php?office=oman" class="<?= $office === 'oman' ? 'active' : '' ?>">Oman</a>
		<a href="gallery.php?office=india" class="<?= $office === 'india' ? 'active' : '' ?>">India</a>
	</div>
	<a href="gallery-edit.php" class="de-btn">Add a Photo</a>
</div>

<div class="de-admin-card" style="padding:0">
	<table class="de-admin-table">
		<thead>
			<tr><th></th><th>Office</th><th>Title</th><th>Location</th><th class="actions">Actions</th></tr>
		</thead>
		<tbody>
			<?php foreach ($photos as $p): ?>
				<tr class="<?= $p['is_published'] ? '' : 'unpublished' ?>">
					<td><img src="../<?= htmlspecialchars($p['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt=""></td>
					<td><span class="de-admin-tag"><?= htmlspecialchars(ucfirst($p['office']), ENT_QUOTES, 'UTF-8') ?></span></td>
					<td><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?><?= $p['is_published'] ? '' : ' <span class="de-admin-tag">Hidden</span>' ?></td>
					<td><?= htmlspecialchars($p['location'], ENT_QUOTES, 'UTF-8') ?></td>
					<td class="actions">
						<a href="gallery-edit.php?id=<?= $p['id'] ?>">Edit</a>
						<form method="post" action="gallery.php" style="display:inline" onsubmit="return confirm('Delete this photo?');">
							<?= de_csrf_field() ?>
							<input type="hidden" name="action" value="delete">
							<input type="hidden" name="id" value="<?= $p['id'] ?>">
							<button type="submit" style="background:none;border:none;color:#b3261e;cursor:pointer;padding:0;font-size:13px;">Delete</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if (!$photos): ?>
				<tr><td colspan="5">No photos yet.</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
