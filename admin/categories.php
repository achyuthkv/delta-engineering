<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();

// Handle delete -- blocked if any project still references it, so deleting
// a category can't silently orphan projects off the public Projects page.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
	de_csrf_check();
	$catId = (int) $_POST['id'];
	$stmt = $db->prepare('SELECT COUNT(*) FROM projects WHERE category_id = ?');
	$stmt->execute([$catId]);
	if ((int) $stmt->fetchColumn() > 0) {
		header('Location: categories.php?error=in_use');
		exit;
	}
	$stmt = $db->prepare('SELECT icon_path FROM project_categories WHERE id = ?');
	$stmt->execute([$catId]);
	$row = $stmt->fetch();
	$stmt = $db->prepare('DELETE FROM project_categories WHERE id = ?');
	$stmt->execute([$catId]);
	if ($row && $row['icon_path'] && str_starts_with($row['icon_path'], 'assets/images/category_icons/')) {
		$full = __DIR__ . '/../' . $row['icon_path'];
		if (is_file($full)) {
			@unlink($full);
		}
	}
	header('Location: categories.php?deleted=1');
	exit;
}

$office = ($_GET['office'] ?? 'canada') === 'india' ? 'india' : 'canada';

$stmt = $db->prepare(
	"SELECT c.*, (SELECT COUNT(*) FROM projects p WHERE p.category_id = c.id) AS project_count
	 FROM project_categories c WHERE c.office = ? ORDER BY c.sort_order, c.name"
);
$stmt->execute([$office]);
$categories = $stmt->fetchAll();

$page_title = 'Categories';
require __DIR__ . '/includes/layout-top.php';
?>

<h1>Categories</h1>
<p class="de-admin-sub">These group the entries on the public <a href="../projects.php" target="_blank">Projects page</a> &mdash; each one can have its own icon, shown next to the category heading and in the jump-nav.</p>

<?php if (isset($_GET['deleted'])): ?>
	<div class="de-admin-flash ok">Category deleted.</div>
<?php elseif (isset($_GET['saved'])): ?>
	<div class="de-admin-flash ok">Category saved.</div>
<?php elseif (($_GET['error'] ?? '') === 'in_use'): ?>
	<div class="de-admin-flash error">Can't delete a category that still has projects in it &mdash; move or delete those projects first.</div>
<?php endif; ?>

<div class="de-admin-toolbar">
	<div class="filters">
		<a href="categories.php?office=canada" class="<?= $office === 'canada' ? 'active' : '' ?>">Canada</a>
		<a href="categories.php?office=india" class="<?= $office === 'india' ? 'active' : '' ?>">India</a>
	</div>
	<a href="category-edit.php?office=<?= $office ?>" class="de-btn">Add a Category</a>
</div>

<div class="de-admin-card" style="padding:0">
	<table class="de-admin-table">
		<thead>
			<tr><th>Icon</th><th>Name</th><th>Sort order</th><th>Projects</th><th class="actions">Actions</th></tr>
		</thead>
		<tbody>
			<?php foreach ($categories as $c): ?>
				<tr>
					<td><?php if ($c['icon_path']): ?><img src="../<?= htmlspecialchars($c['icon_path'], ENT_QUOTES, 'UTF-8') ?>" style="width:40px;height:40px;object-fit:contain;border-radius:50%"><?php else: ?><span style="color:#9aa3b8;font-size:12px">None</span><?php endif; ?></td>
					<td><?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?></td>
					<td><?= (int) $c['sort_order'] ?></td>
					<td><span class="de-admin-tag"><?= (int) $c['project_count'] ?></span></td>
					<td class="actions">
						<a href="category-edit.php?id=<?= $c['id'] ?>">Edit</a>
						<form method="post" action="categories.php" style="display:inline" onsubmit="return confirm('Delete this category?');">
							<?= de_csrf_field() ?>
							<input type="hidden" name="action" value="delete">
							<input type="hidden" name="id" value="<?= $c['id'] ?>">
							<button type="submit" style="background:none;border:none;color:#b3261e;cursor:pointer;padding:0;font-size:13px;">Delete</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if (!$categories): ?>
				<tr><td colspan="5">No categories yet for this office.</td></tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
