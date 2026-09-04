<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$project = ['category' => '', 'description' => '', 'sort_order' => 0, 'is_published' => 1];
$error = '';

if ($id) {
	$stmt = $db->prepare('SELECT * FROM projects WHERE id = ?');
	$stmt->execute([$id]);
	$found = $stmt->fetch();
	if (!$found) {
		header('Location: projects.php');
		exit;
	}
	$project = $found;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	de_csrf_check();
	$project['category'] = trim($_POST['category'] ?? '');
	$project['description'] = trim($_POST['description'] ?? '');
	$project['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
	$project['is_published'] = isset($_POST['is_published']) ? 1 : 0;

	if ($project['category'] === '' || $project['description'] === '') {
		$error = 'Category and description are both required.';
	} else {
		if ($id) {
			$stmt = $db->prepare('UPDATE projects SET category = ?, description = ?, sort_order = ?, is_published = ? WHERE id = ?');
			$stmt->execute([$project['category'], $project['description'], $project['sort_order'], $project['is_published'], $id]);
		} else {
			$stmt = $db->prepare('INSERT INTO projects (category, description, sort_order, is_published) VALUES (?, ?, ?, ?)');
			$stmt->execute([$project['category'], $project['description'], $project['sort_order'], $project['is_published']]);
		}
		header('Location: projects.php?saved=1');
		exit;
	}
}

$categories = $db->query('SELECT DISTINCT category FROM projects ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);

$page_title = $id ? 'Edit Project' : 'Add Project';
require __DIR__ . '/includes/layout-top.php';
?>

<h1><?= $id ? 'Edit Project' : 'Add Project' ?></h1>
<p class="de-admin-sub">This becomes one bullet point under a category on the public <a href="../projects.php" target="_blank">Projects page</a>.</p>

<?php if ($error): ?>
	<div class="de-admin-flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="de-admin-card">
	<form class="de-admin-form" method="post" action="project-edit.php<?= $id ? '?id=' . $id : '' ?>">
		<?= de_csrf_field() ?>

		<label for="category">Category</label>
		<input type="text" id="category" name="category" list="category-list" required value="<?= htmlspecialchars($project['category'], ENT_QUOTES, 'UTF-8') ?>">
		<datalist id="category-list">
			<?php foreach ($categories as $cat): ?>
				<option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>">
			<?php endforeach; ?>
		</datalist>
		<div class="hint">Pick an existing category from the list, or type a new one to start a new accordion section.</div>

		<label for="description">Description</label>
		<textarea id="description" name="description" required rows="4"><?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
		<div class="hint">The full sentence shown as one bullet, e.g. "50,000 sqft Warehouse for JVC in Scarborough, Ont., Canada."</div>

		<div class="row">
			<div>
				<label for="sort_order">Sort order</label>
				<input type="number" id="sort_order" name="sort_order" value="<?= (int) $project['sort_order'] ?>">
				<div class="hint">Lower numbers appear first within the category.</div>
			</div>
			<div>
				<label>
					<input type="checkbox" name="is_published" value="1" <?= $project['is_published'] ? 'checked' : '' ?> style="width:auto;display:inline-block;margin-right:6px;">
					Published (visible on the live site)
				</label>
			</div>
		</div>

		<div class="actions-row">
			<button type="submit" class="de-btn">Save</button>
			<a href="projects.php" class="de-btn secondary">Cancel</a>
		</div>
	</form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
