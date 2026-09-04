<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
	de_csrf_check();
	$stmt = $db->prepare('DELETE FROM projects WHERE id = ?');
	$stmt->execute([(int) $_POST['id']]);
	header('Location: projects.php?deleted=1');
	exit;
}

$categories = $db->query('SELECT DISTINCT category FROM projects ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);

$filter = $_GET['category'] ?? '';
if ($filter !== '' && in_array($filter, $categories, true)) {
	$stmt = $db->prepare('SELECT * FROM projects WHERE category = ? ORDER BY sort_order, id');
	$stmt->execute([$filter]);
} else {
	$filter = '';
	$stmt = $db->query('SELECT * FROM projects ORDER BY category, sort_order, id');
}
$projects = $stmt->fetchAll();

$page_title = 'Projects';
require __DIR__ . '/includes/layout-top.php';
?>

<h1>Projects</h1>
<p class="de-admin-sub">These are the line items shown in the accordion on the public <a href="../projects.php" target="_blank">Projects page</a>, grouped by category.</p>

<?php if (isset($_GET['deleted'])): ?>
	<div class="de-admin-flash ok">Project deleted.</div>
<?php elseif (isset($_GET['saved'])): ?>
	<div class="de-admin-flash ok">Project saved.</div>
<?php endif; ?>

<div class="de-admin-toolbar">
	<div class="filters">
		<a href="projects.php" class="<?= $filter === '' ? 'active' : '' ?>">All (<?= count($projects) ?><?= $filter === '' ? '' : '' ?>)</a>
		<?php foreach ($categories as $cat): ?>
			<a href="projects.php?category=<?= urlencode($cat) ?>" class="<?= $filter === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?></a>
		<?php endforeach; ?>
	</div>
	<a href="project-edit.php" class="de-btn">Add a Project</a>
</div>

<div class="de-admin-card" style="padding:0">
	<table class="de-admin-table">
		<thead>
			<tr><th>Category</th><th>Description</th><th></th><th class="actions">Actions</th></tr>
		</thead>
		<tbody>
			<?php foreach ($projects as $p): ?>
				<tr class="<?= $p['is_published'] ? '' : 'unpublished' ?>">
					<td><span class="de-admin-tag"><?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></span></td>
					<td><?= htmlspecialchars(mb_strimwidth($p['description'], 0, 140, '…'), ENT_QUOTES, 'UTF-8') ?></td>
					<td><?= $p['is_published'] ? '' : '<span class="de-admin-tag">Hidden</span>' ?></td>
					<td class="actions">
						<a href="project-edit.php?id=<?= $p['id'] ?>">Edit</a>
						<form method="post" action="projects.php" style="display:inline" onsubmit="return confirm('Delete this project entry?');">
							<?= de_csrf_field() ?>
							<input type="hidden" name="action" value="delete">
							<input type="hidden" name="id" value="<?= $p['id'] ?>">
							<button type="submit" style="background:none;border:none;color:#b3261e;cursor:pointer;padding:0;font-size:13px;">Delete</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if (!$projects): ?>
				<tr><td colspan="4">No projects yet.</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
