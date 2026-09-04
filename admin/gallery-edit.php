<?php
require_once __DIR__ . '/includes/auth.php';
de_require_login();

$db = de_db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$photo = ['office' => 'canada', 'image_path' => '', 'alt_text' => '', 'title' => '', 'location' => '', 'sort_order' => 0, 'is_published' => 1];
$error = '';

if ($id) {
	$stmt = $db->prepare('SELECT * FROM gallery_photos WHERE id = ?');
	$stmt->execute([$id]);
	$found = $stmt->fetch();
	if (!$found) {
		header('Location: gallery.php');
		exit;
	}
	$photo = $found;
}

const ALLOWED_TYPES = [
	IMAGETYPE_JPEG => 'jpg',
	IMAGETYPE_PNG => 'png',
	IMAGETYPE_WEBP => 'webp',
];
const MAX_UPLOAD_BYTES = 10 * 1024 * 1024; // 10 MB
$uploadDir = __DIR__ . '/../assets/images/gallery_uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	de_csrf_check();
	$photo['office'] = $_POST['office'] ?? 'canada';
	$photo['title'] = trim($_POST['title'] ?? '');
	$photo['location'] = trim($_POST['location'] ?? '');
	$photo['alt_text'] = trim($_POST['alt_text'] ?? '');
	$photo['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
	$photo['is_published'] = isset($_POST['is_published']) ? 1 : 0;

	if (!in_array($photo['office'], ['canada', 'oman', 'india'], true)) {
		$error = 'Choose a valid office.';
	} elseif ($photo['title'] === '' || $photo['location'] === '') {
		$error = 'Title and location are both required.';
	} elseif (!$id && (empty($_FILES['image']['name']))) {
		$error = 'Choose a photo to upload.';
	}

	// Handle a new file upload (required when adding, optional when editing)
	if (!$error && !empty($_FILES['image']['name'])) {
		$file = $_FILES['image'];
		if ($file['error'] !== UPLOAD_ERR_OK) {
			$error = 'Upload failed (error code ' . $file['error'] . '). Try again.';
		} elseif ($file['size'] > MAX_UPLOAD_BYTES) {
			$error = 'Image is too large -- 10MB max.';
		} else {
			$imageInfo = @getimagesize($file['tmp_name']);
			if (!$imageInfo || !isset(ALLOWED_TYPES[$imageInfo[2]])) {
				$error = 'That file isn\'t a supported image (use JPG, PNG, or WebP).';
			} else {
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0755, true);
				}
				$ext = ALLOWED_TYPES[$imageInfo[2]];
				$filename = bin2hex(random_bytes(10)) . '.' . $ext;
				if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
					$error = 'Could not save the uploaded file. Check folder permissions on assets/images/gallery_uploads/.';
				} else {
					// If this is replacing a previously admin-uploaded file, clean up the old one.
					if ($id && str_starts_with($photo['image_path'], 'assets/images/gallery_uploads/')) {
						$old = __DIR__ . '/../' . $photo['image_path'];
						if (is_file($old)) {
							@unlink($old);
						}
					}
					$photo['image_path'] = 'assets/images/gallery_uploads/' . $filename;
				}
			}
		}
	}

	if (!$error && $photo['alt_text'] === '') {
		$photo['alt_text'] = $photo['title'];
	}

	if (!$error) {
		if ($id) {
			$stmt = $db->prepare('UPDATE gallery_photos SET office = ?, image_path = ?, alt_text = ?, title = ?, location = ?, sort_order = ?, is_published = ? WHERE id = ?');
			$stmt->execute([$photo['office'], $photo['image_path'], $photo['alt_text'], $photo['title'], $photo['location'], $photo['sort_order'], $photo['is_published'], $id]);
		} else {
			$stmt = $db->prepare('INSERT INTO gallery_photos (office, image_path, alt_text, title, location, sort_order, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)');
			$stmt->execute([$photo['office'], $photo['image_path'], $photo['alt_text'], $photo['title'], $photo['location'], $photo['sort_order'], $photo['is_published']]);
		}
		header('Location: gallery.php?saved=1');
		exit;
	}
}

$page_title = $id ? 'Edit Photo' : 'Add Photo';
require __DIR__ . '/includes/layout-top.php';
?>

<h1><?= $id ? 'Edit Photo' : 'Add Photo' ?></h1>
<p class="de-admin-sub">Appears on the Canada gallery, or the Oman / India section of the international gallery, depending on the office chosen below.</p>

<?php if ($error): ?>
	<div class="de-admin-flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="de-admin-card">
	<form class="de-admin-form" method="post" action="gallery-edit.php<?= $id ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
		<?= de_csrf_field() ?>

		<label for="office">Office</label>
		<select id="office" name="office">
			<option value="canada" <?= $photo['office'] === 'canada' ? 'selected' : '' ?>>Canada</option>
			<option value="oman" <?= $photo['office'] === 'oman' ? 'selected' : '' ?>>Oman</option>
			<option value="india" <?= $photo['office'] === 'india' ? 'selected' : '' ?>>India</option>
		</select>

		<label for="image">Photo <?= $id ? '(leave blank to keep the current photo)' : '' ?></label>
		<input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
		<?php if ($photo['image_path']): ?>
			<img class="preview" src="../<?= htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Current photo">
		<?php endif; ?>
		<div class="hint">JPG, PNG, or WebP, up to 10MB.</div>

		<label for="title">Title</label>
		<input type="text" id="title" name="title" required value="<?= htmlspecialchars($photo['title'], ENT_QUOTES, 'UTF-8') ?>">
		<div class="hint">e.g. "Water Storage Tank" or "Mall For Izz Galleria"</div>

		<label for="location">Location caption</label>
		<input type="text" id="location" name="location" required value="<?= htmlspecialchars($photo['location'], ENT_QUOTES, 'UTF-8') ?>">
		<div class="hint">e.g. "Ontario, Canada" or "Salalah, Oman" -- shown under the title.</div>

		<label for="alt_text">Alt text (for accessibility &amp; SEO)</label>
		<input type="text" id="alt_text" name="alt_text" value="<?= htmlspecialchars($photo['alt_text'], ENT_QUOTES, 'UTF-8') ?>">
		<div class="hint">Leave blank to reuse the title.</div>

		<div class="row">
			<div>
				<label for="sort_order">Sort order</label>
				<input type="number" id="sort_order" name="sort_order" value="<?= (int) $photo['sort_order'] ?>">
				<div class="hint">Lower numbers appear first.</div>
			</div>
			<div>
				<label>
					<input type="checkbox" name="is_published" value="1" <?= $photo['is_published'] ? 'checked' : '' ?> style="width:auto;display:inline-block;margin-right:6px;">
					Published (visible on the live site)
				</label>
			</div>
		</div>

		<div class="actions-row">
			<button type="submit" class="de-btn">Save</button>
			<a href="gallery.php" class="de-btn secondary">Cancel</a>
		</div>
	</form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
