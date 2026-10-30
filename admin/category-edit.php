<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$category = ['office' => 'canada', 'name' => '', 'icon_path' => null, 'sort_order' => 0];
$error = '';

if ($id) {
	$stmt = $db->prepare('SELECT * FROM project_categories WHERE id = ?');
	$stmt->execute([$id]);
	$found = $stmt->fetch();
	if (!$found) {
		header('Location: categories.php');
		exit;
	}
	$category = $found;
} elseif (isset($_GET['office']) && $_GET['office'] === 'india') {
	$category['office'] = 'india';
}

const ICON_ALLOWED_TYPES = [
	IMAGETYPE_JPEG => 'jpg',
	IMAGETYPE_PNG => 'png',
	IMAGETYPE_WEBP => 'webp',
];
const ICON_MAX_UPLOAD_BYTES = 2 * 1024 * 1024; // 2 MB -- these are small icon graphics, not photos
$iconUploadDir = __DIR__ . '/../assets/images/category_icons/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	de_csrf_check();

	$category['name'] = trim($_POST['name'] ?? '');
	$category['office'] = ($_POST['office'] ?? 'canada') === 'india' ? 'india' : 'canada';
	$category['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
	$removeIcon = isset($_POST['remove_icon']);

	if ($category['name'] === '') {
		$error = 'Name is required.';
	} else {
		// Uniqueness check (office, name) -- mirrors the DB constraint but
		// gives a clean message instead of a raw SQL error.
		$dupStmt = $db->prepare('SELECT id FROM project_categories WHERE office = ? AND name = ? AND id <> ?');
		$dupStmt->execute([$category['office'], $category['name'], $id ?? 0]);
		if ($dupStmt->fetch()) {
			$error = 'A category with that name already exists for this office.';
		}
	}

	$newIconPath = $category['icon_path'];
	if (!$error && $removeIcon) {
		$newIconPath = null;
	}
	if (!$error && !empty($_FILES['icon']['name'])) {
		$file = $_FILES['icon'];
		if ($file['error'] !== UPLOAD_ERR_OK) {
			$error = 'Icon upload failed (error code ' . $file['error'] . '). Try again.';
		} elseif ($file['size'] > ICON_MAX_UPLOAD_BYTES) {
			$error = 'Icon image is too large -- 2MB max.';
		} else {
			$imageInfo = @getimagesize($file['tmp_name']);
			if (!$imageInfo || !isset(ICON_ALLOWED_TYPES[$imageInfo[2]])) {
				$error = 'That file isn\'t a supported image (use JPG, PNG, or WebP).';
			} else {
				if (!is_dir($iconUploadDir)) {
					mkdir($iconUploadDir, 0755, true);
				}
				$ext = ICON_ALLOWED_TYPES[$imageInfo[2]];
				$filename = bin2hex(random_bytes(10)) . '.' . $ext;
				if (!move_uploaded_file($file['tmp_name'], $iconUploadDir . $filename)) {
					$error = 'Could not save the uploaded icon. Check folder permissions on assets/images/category_icons/.';
				} else {
					$newIconPath = 'assets/images/category_icons/' . $filename;
				}
			}
		}
	}

	if (!$error) {
		$oldIconPath = $category['icon_path'];
		$category['icon_path'] = $newIconPath;

		if ($id) {
			$stmt = $db->prepare('UPDATE project_categories SET office = ?, name = ?, icon_path = ?, sort_order = ? WHERE id = ?');
			$stmt->execute([$category['office'], $category['name'], $category['icon_path'], $category['sort_order'], $id]);
		} else {
			$stmt = $db->prepare('INSERT INTO project_categories (office, name, icon_path, sort_order) VALUES (?, ?, ?, ?)');
			$stmt->execute([$category['office'], $category['name'], $category['icon_path'], $category['sort_order']]);
			$id = (int) $db->lastInsertId();
		}

		// Clean up the replaced/removed file only after the DB write succeeds.
		if ($oldIconPath && $oldIconPath !== $category['icon_path'] && str_starts_with($oldIconPath, 'assets/images/category_icons/')) {
			$full = __DIR__ . '/../' . $oldIconPath;
			if (is_file($full)) {
				@unlink($full);
			}
		}

		header('Location: category-edit.php?id=' . $id . '&saved=1');
		exit;
	}
}

$page_title = $id ? 'Edit Category' : 'Add Category';
require __DIR__ . '/includes/layout-top.php';
?>

<h1><?= $id ? 'Edit Category' : 'Add Category' ?></h1>
<p class="de-admin-sub">Appears as a heading on the public <a href="../projects.php" target="_blank">Projects page</a>, with its icon next to it.</p>

<?php if (isset($_GET['saved'])): ?>
	<div class="de-admin-flash ok">Category saved.</div>
<?php endif; ?>
<?php if ($error): ?>
	<div class="de-admin-flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="de-admin-card">
	<form class="de-admin-form" method="post" action="category-edit.php<?= $id ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
		<?= de_csrf_field() ?>

		<label for="office">Office</label>
		<select id="office" name="office">
			<option value="canada" <?= $category['office'] === 'canada' ? 'selected' : '' ?>>Canada</option>
			<option value="india" <?= $category['office'] === 'india' ? 'selected' : '' ?>>India</option>
		</select>

		<label for="name">Name</label>
		<input type="text" id="name" name="name" required value="<?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>">

		<label for="icon">Icon</label>
		<?php if ($category['icon_path']): ?>
			<img class="preview" src="../<?= htmlspecialchars($category['icon_path'], ENT_QUOTES, 'UTF-8') ?>" style="width:72px;height:72px;object-fit:contain;border-radius:50%;background:#f5f4f0">
			<label style="font-weight:400;margin-top:8px">
				<input type="checkbox" name="remove_icon" value="1" style="width:auto;display:inline-block;margin-right:6px;">
				Remove current icon
			</label>
		<?php endif; ?>
		<input type="file" id="icon" name="icon" accept="image/jpeg,image/png,image/webp" style="margin-top:8px">
		<div class="hint">JPG, PNG, or WebP, up to 2MB. Square images work best &mdash; shown at a small, circular size.</div>

		<label for="sort_order">Sort order</label>
		<input type="number" id="sort_order" name="sort_order" value="<?= (int) $category['sort_order'] ?>">
		<div class="hint">Lower numbers appear first on the Projects page.</div>

		<div class="actions-row">
			<button type="submit" class="de-btn">Save</button>
			<a href="categories.php?office=<?= $category['office'] ?>" class="de-btn secondary">Cancel</a>
		</div>
	</form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
