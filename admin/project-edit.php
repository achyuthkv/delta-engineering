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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	de_csrf_check();
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
		} else {
			$stmt = $db->prepare('INSERT INTO projects (category, description, office, discipline, sort_order, is_published) VALUES (?, ?, ?, ?, ?, ?)');
			$stmt->execute([$project['category'], $project['description'], $project['office'], $project['discipline'], $project['sort_order'], $project['is_published']]);
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

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
