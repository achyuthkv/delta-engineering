<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
	<meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Browse completed projects by Delta Engineering Services across structural, civil, and infrastructure engineering disciplines.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/projects.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Projects | Delta Engineering Services">
    <meta property="og:description" content="Completed projects across structural, civil, and infrastructure engineering disciplines.">
    <meta property="og:url" content="https://www.delta-engineering.ca/projects.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "BreadcrumbList",
		"itemListElement": [
			{ "@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.delta-engineering.ca/" },
			{ "@type": "ListItem", "position": 2, "name": "Projects", "item": "https://www.delta-engineering.ca/projects.php" }
		]
	}
	</script>

	<title>Projects | Delta Engineering Services</title>

	<?php
	include 'header.php';

	// Categories are managed through /admin/categories.php (name, icon,
	// sort order, scoped to an office) -- projects.category_id points at
	// one of them. $deCategories drives both the grouping below and the
	// icon shown next to each heading / jump-nav chip.
	$deProjectGroups = [];
	$deCategories = [];
	try {
		require_once __DIR__ . '/admin/includes/db.php';
		$db = de_db();

		$catStmt = $db->prepare('SELECT id, name, icon_path FROM project_categories WHERE office = ? ORDER BY sort_order, name');
		$catStmt->execute([$deLoc]);
		foreach ($catStmt->fetchAll() as $cat) {
			$deCategories[$cat['id']] = $cat;
		}

		$stmt = $db->prepare(
			"SELECT category_id, description, sort_order, id
			 FROM projects
			 WHERE is_published = 1 AND office = ? AND category_id IS NOT NULL
			 ORDER BY category_id, sort_order, id"
		);
		$stmt->execute([$deLoc]);
		$rows = $stmt->fetchAll();

		// All published photos per project (not just the first) -- a
		// project with several shows as a card that cycles through them on
		// hover instead of a single static thumbnail.
		$deProjectPhotos = [];
		$projectIds = array_column($rows, 'id');
		if ($projectIds) {
			$placeholders = implode(',', array_fill(0, count($projectIds), '?'));
			$photoStmt = $db->prepare(
				"SELECT project_id, image_path, alt_text FROM gallery_photos
				 WHERE project_id IN ($placeholders) AND is_published = 1
				 ORDER BY project_id, sort_order, id"
			);
			$photoStmt->execute($projectIds);
			foreach ($photoStmt->fetchAll() as $photoRow) {
				$deProjectPhotos[$photoRow['project_id']][] = $photoRow;
			}
		}

		// Within a category, photographed projects are split out into
		// their own "photo" bucket rather than staying inline in the text
		// list -- a building photo needs real size to actually be
		// recognizable, and a thumbnail small enough to sit on a single
		// text line isn't big enough for that. Order within each bucket
		// still follows sort_order/id, same as before.
		foreach ($rows as $row) {
			if (!isset($deCategories[$row['category_id']])) {
				continue; // category since deleted/reassigned elsewhere
			}
			$photos = $deProjectPhotos[$row['id']] ?? [];
			$bucket = $photos ? 'photo' : 'text';
			$deProjectGroups[$row['category_id']][$bucket][] = [
				'description' => $row['description'],
				'photos' => $photos,
			];
		}
	} catch (Throwable $e) {
		$deProjectGroups = [];
		$deCategories = [];
	}

	// A small curated set of real project photography (the same files
	// used on the dedicated gallery pages, with the exact titles/locations
	// already on record in gallery_photos) for a featured rail at the top
	// of the page. Canada only for now -- India's gallery doesn't yet have
	// enough photographed projects on record to feature honestly.
	$deFeatured = [];
	if ($deLoc !== 'india') {
		$deFeatured = [
			['src' => 'assets/images/gallery_canada/002.jpg', 'tag' => 'Infrastructure', 'cap' => 'Water Tank', 'loc' => 'Norwood, Ontario'],
			['src' => 'assets/images/gallery_canada/014.jpg', 'tag' => 'Industrial', 'cap' => 'Industrial Building', 'loc' => 'Alliston, Ontario'],
			['src' => 'assets/images/gallery_canada/016.jpg', 'tag' => 'Community', 'cap' => 'Shikhar Works, Hindu Sabha Temple', 'loc' => 'Hamilton, Ontario'],
		];
	}
	?>

	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Projects</span></div>
				<div class="de-eyebrow"><?= $deLoc === 'india' ? 'India' : 'Canada &middot; Greater Toronto Area' ?></div>
				<h1>Projects</h1>
				<?php if ($deLoc === 'india'): ?>
				<p>A record of what we've built in India, organized by the kind of work. See our <a href="projects.php?loc=canada">Canada projects</a> or past work in <a href="gallery_oman_projects.php">Oman</a>. For photos, see the <a href="gallery_international_projects.php">India gallery</a>.</p>
				<?php else: ?>
				<p>A record of what we've built across the Greater Toronto Area, organized by the kind of work, spanning four decades since 1985. See our <a href="projects.php?loc=india">India projects</a> or past work in <a href="gallery_oman_projects.php">Oman</a>.</p>
				<?php endif; ?>
			</div>
		</div>

		<div class="de-section">
			<?php if (!$deProjectGroups): ?>
				<p style="text-align:center;color:#6c7690">Project list is temporarily unavailable.</p>
			<?php else: ?>

			<?php $deActiveCatCount = count($deProjectGroups); ?>
			<div class="de-proj-stat-line">
				<span class="n"><?= count($rows) ?>+</span>
				<span class="l">documented projects on record<?= $deActiveCatCount > 1 ? ', across ' . $deActiveCatCount . ' categories' : '' ?><?= $deLoc === 'india' ? '' : ' spanning four decades' ?></span>
			</div>

			<?php if ($deFeatured): ?>
			<div class="de-proj-grid de-proj-grid-featured">
				<?php foreach ($deFeatured as $f): ?>
				<div class="de-proj-card">
					<img src="<?= htmlspecialchars($f['src'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($f['cap'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
					<span class="de-proj-tag"><?= htmlspecialchars($f['tag'], ENT_QUOTES, 'UTF-8') ?></span>
					<div class="de-proj-cap"><?= htmlspecialchars($f['cap'], ENT_QUOTES, 'UTF-8') ?><span><?= htmlspecialchars($f['loc'], ENT_QUOTES, 'UTF-8') ?></span></div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<?php if ($deActiveCatCount > 1): ?>
			<nav class="de-proj-jump" aria-label="Jump to category">
				<?php foreach ($deCategories as $catId => $cat):
					$group = $deProjectGroups[$catId] ?? null;
					if (!$group) continue;
					$slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($cat['name'])), '-');
					$count = count($group['photo'] ?? []) + count($group['text'] ?? []);
				?>
				<a href="#cat-<?= $slug ?>">
					<?php if ($cat['icon_path']): ?><img src="<?= htmlspecialchars($cat['icon_path'], ENT_QUOTES, 'UTF-8') ?>" alt="" class="de-proj-jump-icon"><?php endif; ?>
					<?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?> <span><?= $count ?></span>
				</a>
				<?php endforeach; ?>
			</nav>
			<?php endif; ?>

			<div class="de-proj-register">
				<?php foreach ($deCategories as $catId => $cat):
					$group = $deProjectGroups[$catId] ?? null;
					if (!$group) continue;
					$slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($cat['name'])), '-');
					$photoItems = $group['photo'] ?? [];
					$textItems = $group['text'] ?? [];
					$count = count($photoItems) + count($textItems);
				?>
				<section class="de-proj-cat" id="cat-<?= $slug ?>">
					<div class="de-proj-cat-head">
						<?php if ($cat['icon_path']): ?><img src="<?= htmlspecialchars($cat['icon_path'], ENT_QUOTES, 'UTF-8') ?>" alt="" class="de-proj-cat-icon"><?php endif; ?>
						<h2><?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?></h2>
						<span class="de-proj-cat-count"><?= $count ?> project<?= $count === 1 ? '' : 's' ?></span>
					</div>
					<div class="de-proj-cat-body<?= ($photoItems && $textItems) ? ' de-proj-cat-body-split' : '' ?>">
					<?php if ($photoItems): ?>
					<div class="de-proj-photo-grid">
						<?php foreach ($photoItems as $item):
							$photos = $item['photos'];
							$isMulti = count($photos) > 1;
						?>
						<figure class="de-proj-photo-card"<?= $isMulti ? ' data-multi-photo' : '' ?>>
							<div class="de-proj-photo-stack content-image-block">
								<?php foreach ($photos as $pi => $photo): ?>
								<img src="<?= htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($photo['alt_text'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy" class="<?= $pi === 0 ? 'is-active' : '' ?>">
								<?php endforeach; ?>
								<div class="content-block-hover">
									<a class="zoom-in" href="<?= htmlspecialchars($photos[0]['image_path'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-search" style="font-size:30px;margin-left:25px"></i></a>
								</div>
							</div>
							<?php if ($isMulti): ?><span class="de-proj-photo-count"><?= count($photos) ?> photos</span><?php endif; ?>
							<figcaption><div class="de-proj-desc"><?= $item['description'] ?></div></figcaption>
						</figure>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<?php if ($textItems): ?>
					<div class="de-reg-list">
						<?php foreach ($textItems as $ti => $item): ?>
						<div class="de-reg-row">
							<span class="de-reg-num"><?= str_pad($ti + 1, 2, '0', STR_PAD_LEFT) ?></span>
							<div class="de-reg-desc"><?= $item['description'] ?></div>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					</div>
				</section>
				<?php endforeach; ?>
			</div>

			<?php endif; ?>
		</div>

		<!-- CTA band -->
		<div class="de-cta-band">
			<div class="de-cta-band-inner">
				<div>
					<h2><?= $deLoc === 'india' ? 'Have a project in India?' : 'See something like your project?' ?></h2>
					<p><?php if ($deLoc === 'india'): ?>+91 72594 05511<?php else: ?>(416) 573-1573 &nbsp;&middot;&nbsp; (437) 986-3858<?php endif; ?> &nbsp;&middot;&nbsp; info@delta-engineering.ca</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>
