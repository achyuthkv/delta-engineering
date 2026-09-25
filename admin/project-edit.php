<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/sanitize.php';
de_require_login();

$db = de_db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$project = ['category' => '', 'description' => '', 'office' => 'canada', 'discipline' => 'structural', 'sort_order' => 0, 'is_published' => 1];
$error = '';

$offices = ['canada' => 'Canada', 'india' => 'India'];
$disciplines = [
	'architectural' => 'Architectural Services',
	'structural' => 'Structural Engineering',
	'civil' => 'Civil Engineering',
	'infrastructure' => 'Infrastructure & Municipal Engineering',
	'bim_mep' => 'BIM, Mechanical & Electrical Engineering',
	'project_management' => 'Project Management',
];

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

const PHOTO_ALLOWED_TYPES = [
	IMAGETYPE_JPEG => 'jpg',
	IMAGETYPE_PNG => 'png',
	IMAGETYPE_WEBP => 'webp',
];
const PHOTO_MAX_UPLOAD_BYTES = 10 * 1024 * 1024; // 10 MB
$photoUploadDir = __DIR__ . '/../assets/images/gallery_uploads/';
$photoError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	de_csrf_check();
	$formAction = $_POST['form_action'] ?? 'save_project';

	if ($formAction === 'add_photo' && $id) {
		// Uploaded here rather than through /admin/gallery.php gets linked
		// back to this project (see schema.sql) and inherits its office,
		// so it shows up on that office's gallery page automatically --
		// that's the whole point of attaching photos from the project
		// screen instead of just the standalone gallery one.
		$newPhoto = [
			'title' => trim($_POST['photo_title'] ?? ''),
			'location' => trim($_POST['photo_location'] ?? ''),
			'alt_text' => trim($_POST['photo_alt_text'] ?? ''),
			'sort_order' => (int) ($_POST['photo_sort_order'] ?? 0),
		];

		if ($newPhoto['title'] === '' || $newPhoto['location'] === '') {
			$photoError = 'Photo title and location are both required.';
		} elseif (empty($_FILES['photo_image']['name'])) {
			$photoError = 'Choose a photo to upload.';
		} else {
			$file = $_FILES['photo_image'];
			if ($file['error'] !== UPLOAD_ERR_OK) {
				$photoError = 'Upload failed (error code ' . $file['error'] . '). Try again.';
			} elseif ($file['size'] > PHOTO_MAX_UPLOAD_BYTES) {
				$photoError = 'Image is too large -- 10MB max.';
			} else {
				$imageInfo = @getimagesize($file['tmp_name']);
				if (!$imageInfo || !isset(PHOTO_ALLOWED_TYPES[$imageInfo[2]])) {
					$photoError = 'That file isn\'t a supported image (use JPG, PNG, or WebP).';
				} else {
					if (!is_dir($photoUploadDir)) {
						mkdir($photoUploadDir, 0755, true);
					}
					$ext = PHOTO_ALLOWED_TYPES[$imageInfo[2]];
					$filename = bin2hex(random_bytes(10)) . '.' . $ext;
					if (!move_uploaded_file($file['tmp_name'], $photoUploadDir . $filename)) {
						$photoError = 'Could not save the uploaded file. Check folder permissions on assets/images/gallery_uploads/.';
					} else {
						$imagePath = 'assets/images/gallery_uploads/' . $filename;
						$altText = $newPhoto['alt_text'] !== '' ? $newPhoto['alt_text'] : $newPhoto['title'];
						$stmt = $db->prepare('INSERT INTO gallery_photos (office, project_id, image_path, alt_text, title, location, sort_order, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, 1)');
						$stmt->execute([$project['office'], $id, $imagePath, $altText, $newPhoto['title'], $newPhoto['location'], $newPhoto['sort_order']]);
						header('Location: project-edit.php?id=' . $id . '&photo_saved=1');
						exit;
					}
				}
			}
		}
	} elseif ($formAction === 'delete_photo' && $id) {
		// Scoped to this project's id, not just the photo id, so this form
		// can't be used to delete an arbitrary gallery photo elsewhere on
		// the site by guessing/tampering with the id.
		$photoId = (int) ($_POST['photo_id'] ?? 0);
		$stmt = $db->prepare('SELECT image_path FROM gallery_photos WHERE id = ? AND project_id = ?');
		$stmt->execute([$photoId, $id]);
		$row = $stmt->fetch();
		if ($row) {
			$stmt = $db->prepare('DELETE FROM gallery_photos WHERE id = ? AND project_id = ?');
			$stmt->execute([$photoId, $id]);
			if (str_starts_with($row['image_path'], 'assets/images/gallery_uploads/')) {
				$full = __DIR__ . '/../' . $row['image_path'];
				if (is_file($full)) {
					@unlink($full);
				}
			}
		}
		header('Location: project-edit.php?id=' . $id . '&photo_deleted=1');
		exit;
	} else {
		$project['category'] = trim($_POST['category'] ?? '');
		// Sanitized here regardless of what the rich text editor's JS sent --
		// that JS is just a convenience, not a security boundary. A request
		// could always POST straight to this endpoint bypassing it entirely,
		// so this allowlist pass is what actually keeps stored XSS out before
		// the description is echoed unescaped on the public site.
		$project['description'] = de_sanitize_html(trim($_POST['description'] ?? ''));
		$project['office'] = array_key_exists($_POST['office'] ?? '', $offices) ? $_POST['office'] : 'canada';
		$project['discipline'] = array_key_exists($_POST['discipline'] ?? '', $disciplines) ? $_POST['discipline'] : 'structural';
		$project['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
		$project['is_published'] = isset($_POST['is_published']) ? 1 : 0;

		$descriptionIsBlank = trim(strip_tags($project['description'])) === '';
		if ($project['category'] === '' || $descriptionIsBlank) {
			$error = 'Category and description are both required.';
		} else {
			if ($id) {
				$stmt = $db->prepare('UPDATE projects SET category = ?, description = ?, office = ?, discipline = ?, sort_order = ?, is_published = ? WHERE id = ?');
				$stmt->execute([$project['category'], $project['description'], $project['office'], $project['discipline'], $project['sort_order'], $project['is_published'], $id]);
				header('Location: project-edit.php?id=' . $id . '&saved=1');
			} else {
				$stmt = $db->prepare('INSERT INTO projects (category, description, office, discipline, sort_order, is_published) VALUES (?, ?, ?, ?, ?, ?)');
				$stmt->execute([$project['category'], $project['description'], $project['office'], $project['discipline'], $project['sort_order'], $project['is_published']]);
				header('Location: project-edit.php?id=' . $db->lastInsertId() . '&saved=1');
			}
			exit;
		}
	}
}

$linkedPhotos = [];
if ($id) {
	$stmt = $db->prepare('SELECT * FROM gallery_photos WHERE project_id = ? ORDER BY sort_order, id');
	$stmt->execute([$id]);
	$linkedPhotos = $stmt->fetchAll();
}

$categories = $db->query('SELECT DISTINCT category FROM projects ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);

$page_title = $id ? 'Edit Project' : 'Add Project';
require __DIR__ . '/includes/layout-top.php';
?>

<h1><?= $id ? 'Edit Project' : 'Add Project' ?></h1>
<p class="de-admin-sub">This becomes one bullet point under a category on the public <a href="../projects.php" target="_blank">Projects page</a>.</p>

<?php if (isset($_GET['saved'])): ?>
	<div class="de-admin-flash ok">Project saved.</div>
<?php elseif (isset($_GET['photo_saved'])): ?>
	<div class="de-admin-flash ok">Photo added.</div>
<?php elseif (isset($_GET['photo_deleted'])): ?>
	<div class="de-admin-flash ok">Photo removed.</div>
<?php endif; ?>

<?php if ($error): ?>
	<div class="de-admin-flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="de-admin-card">
	<form class="de-admin-form" method="post" action="project-edit.php<?= $id ? '?id=' . $id : '' ?>">
		<?= de_csrf_field() ?>
		<input type="hidden" name="form_action" value="save_project">

		<label for="category">Category</label>
		<input type="text" id="category" name="category" list="category-list" required value="<?= htmlspecialchars($project['category'], ENT_QUOTES, 'UTF-8') ?>">
		<datalist id="category-list">
			<?php foreach ($categories as $cat): ?>
				<option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>">
			<?php endforeach; ?>
		</datalist>
		<div class="hint">Pick an existing category from the list, or type a new one to start a new accordion section.</div>

		<label for="descriptionEditor">Description</label>
		<div class="de-rte">
			<div class="de-rte-toolbar" role="toolbar" aria-label="Formatting">
				<button type="button" data-cmd="bold" title="Bold"><b>B</b></button>
				<button type="button" data-cmd="italic" title="Italic"><i>I</i></button>
				<button type="button" data-cmd="underline" title="Underline"><u>U</u></button>
				<span class="de-rte-sep"></span>
				<button type="button" data-cmd="formatBlock" data-value="H2" title="Heading">H2</button>
				<button type="button" data-cmd="formatBlock" data-value="H3" title="Subheading">H3</button>
				<button type="button" data-cmd="formatBlock" data-value="P" title="Paragraph">&para;</button>
				<span class="de-rte-sep"></span>
				<button type="button" data-cmd="insertUnorderedList" title="Bullet list">&bull; List</button>
				<button type="button" data-cmd="insertOrderedList" title="Numbered list">1. List</button>
				<button type="button" data-cmd="formatBlock" data-value="BLOCKQUOTE" title="Quote">&ldquo; Quote</button>
				<span class="de-rte-sep"></span>
				<button type="button" data-cmd="createLink" title="Add link">Link</button>
				<button type="button" data-cmd="unlink" title="Remove link">Unlink</button>
			</div>
			<div id="descriptionEditor" class="de-rte-editor" contenteditable="true"><?= $project['description'] ?></div>
		</div>
		<textarea id="description" name="description" style="display:none"></textarea>
		<div class="hint">Formatted text shown as one entry, e.g. "50,000 sqft Warehouse for JVC in Scarborough, Ont., Canada." Bold, links, headings, and lists are supported.</div>

		<script>
			(function () {
				var editor = document.getElementById('descriptionEditor');
				var hidden = document.getElementById('description');
				var toolbar = document.querySelector('.de-rte-toolbar');

				function sync() { hidden.value = editor.innerHTML; }
				sync();
				editor.addEventListener('input', sync);

				toolbar.addEventListener('click', function (e) {
					var btn = e.target.closest('button[data-cmd]');
					if (!btn) return;
					e.preventDefault();
					editor.focus();
					var cmd = btn.dataset.cmd;
					if (cmd === 'createLink') {
						var url = window.prompt('Link URL (e.g. https://... or /gallery_canada_projects.php):');
						if (!url) return;
						document.execCommand(cmd, false, url);
					} else if (cmd === 'formatBlock') {
						document.execCommand(cmd, false, btn.dataset.value);
					} else {
						document.execCommand(cmd, false, null);
					}
					sync();
				});

				editor.closest('form').addEventListener('submit', sync);
			})();
		</script>

		<div class="row">
			<div>
				<label for="office">Office</label>
				<select id="office" name="office">
					<?php foreach ($offices as $value => $label): ?>
						<option value="<?= $value ?>" <?= $project['office'] === $value ? 'selected' : '' ?>><?= $label ?></option>
					<?php endforeach; ?>
				</select>
				<div class="hint">Which office this project belongs to.</div>
			</div>
			<div>
				<label for="discipline">Discipline</label>
				<select id="discipline" name="discipline">
					<?php foreach ($disciplines as $value => $label): ?>
						<option value="<?= $value ?>" <?= $project['discipline'] === $value ? 'selected' : '' ?>><?= $label ?></option>
					<?php endforeach; ?>
				</select>
				<div class="hint">Powers the "Featured Projects" block on that discipline's service page.</div>
			</div>
		</div>

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

<div class="de-admin-card">
	<h2 style="margin-top:0">Photos</h2>
	<?php if (!$id): ?>
		<p class="de-admin-sub">Save this project first — then you can attach photos to it here.</p>
	<?php else: ?>
		<p class="de-admin-sub">Shown on the <?= $project['office'] === 'india' ? '<a href="../gallery_international_projects.php" target="_blank">India gallery</a>' : '<a href="../gallery_canada_projects.php" target="_blank">Canada gallery</a>' ?>, alongside every other photo for that office.</p>

		<?php if ($photoError): ?>
			<div class="de-admin-flash error"><?= htmlspecialchars($photoError, ENT_QUOTES, 'UTF-8') ?></div>
		<?php endif; ?>

		<?php if ($linkedPhotos): ?>
		<div class="de-admin-photo-grid">
			<?php foreach ($linkedPhotos as $photo): ?>
			<div class="de-admin-photo-item">
				<img src="../<?= htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
				<div class="cap"><?= htmlspecialchars($photo['title'], ENT_QUOTES, 'UTF-8') ?><?= $photo['is_published'] ? '' : ' <span class="de-admin-tag">Hidden</span>' ?></div>
				<div class="actions">
					<a href="gallery-edit.php?id=<?= $photo['id'] ?>">Edit</a>
					<form method="post" action="project-edit.php?id=<?= $id ?>" style="display:inline" onsubmit="return confirm('Remove this photo?');">
						<?= de_csrf_field() ?>
						<input type="hidden" name="form_action" value="delete_photo">
						<input type="hidden" name="photo_id" value="<?= $photo['id'] ?>">
						<button type="submit" style="background:none;border:none;color:#b3261e;cursor:pointer;padding:0;font-size:13px;">Remove</button>
					</form>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php else: ?>
			<p style="color:#6c7690">No photos attached yet.</p>
		<?php endif; ?>

		<form class="de-admin-form" method="post" action="project-edit.php?id=<?= $id ?>" enctype="multipart/form-data" style="margin-top:20px">
			<?= de_csrf_field() ?>
			<input type="hidden" name="form_action" value="add_photo">

			<label for="photo_image">Add a photo</label>
			<input type="file" id="photo_image" name="photo_image" accept="image/jpeg,image/png,image/webp">
			<div class="hint">JPG, PNG, or WebP, up to 10MB.</div>

			<label for="photo_title">Title</label>
			<input type="text" id="photo_title" name="photo_title" required>
			<div class="hint">e.g. "50,000 sq ft Warehouse" — shown as the photo's caption.</div>

			<label for="photo_location">Location caption</label>
			<input type="text" id="photo_location" name="photo_location" required>
			<div class="hint">e.g. "Scarborough, ON" or "Ahmedabad, Gujarat" — shown under the title.</div>

			<div class="actions-row">
				<button type="submit" class="de-btn">Upload Photo</button>
			</div>
		</form>
	<?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
