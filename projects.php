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

	<title>Projects | Delta Engineering Services</title>

	<?php
	include 'header.php';

	// Projects are managed through /admin -- grouped by category in the
	// order each category was first added, matching the accordion order
	// that used to be hand-written directly into this file.
	$deProjectGroups = [];
	try {
		require_once __DIR__ . '/admin/includes/db.php';
		$rows = de_db()->query(
			"SELECT category, description, sort_order, id
			 FROM projects
			 WHERE is_published = 1
			 ORDER BY category, sort_order, id"
		)->fetchAll();

		// Preserve first-inserted category order (min id per category)
		// rather than alphabetical, so the accordion reads the same as
		// it always has even though the grouping now happens in PHP.
		$firstSeen = [];
		foreach ($rows as $row) {
			if (!isset($firstSeen[$row['category']])) {
				$firstSeen[$row['category']] = $row['id'];
			}
			$deProjectGroups[$row['category']][] = $row['description'];
		}
		uksort($deProjectGroups, fn($a, $b) => $firstSeen[$a] <=> $firstSeen[$b]);
	} catch (Throwable $e) {
		$deProjectGroups = [];
	}
	?>

	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Projects</span></div>
				<div class="de-eyebrow">Canada &middot; Greater Toronto Area</div>
				<h1>Projects</h1>
				<p>A record of what we've built across the Greater Toronto Area, organized by the kind of work, spanning four decades since 1985. For our Oman portfolio, see the <a href="gallery_international_projects.php">international gallery</a>.</p>
			</div>
		</div>

		<div class="de-section">
			<?php if (!$deProjectGroups): ?>
				<p style="text-align:center;color:#6c7690">Project list is temporarily unavailable.</p>
			<?php else: ?>
			<div class="de-accordion" id="accordion">
				<?php $i = 0; foreach ($deProjectGroups as $category => $items): $i++; $panelId = 'faqcontent' . $i; ?>
				<div class="de-accordion-item panel">
					<h3><a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?= $panelId ?>" aria-expanded="<?= $i === 1 ? 'true' : 'false' ?>" class="de-accordion-toggle<?= $i === 1 ? '' : ' collapsed' ?>"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?> <svg class="chev" viewBox="0 0 12 12" fill="none"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5"/></svg></a></h3>
					<div id="<?= $panelId ?>" class="collapse<?= $i === 1 ? ' in' : '' ?> de-accordion-body" role="tabpanel">
						<div class="de-accordion-body-inner">
							<ul class="de-accordion-list">
								<?php foreach ($items as $description): ?>
									<li><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>

		<!-- CTA band -->
		<div class="de-cta-band">
			<div class="de-cta-band-inner">
				<div>
					<h2>See something like your project?</h2>
					<p>(416) 573-1573 &nbsp;&middot;&nbsp; palak@deltaengineering.ca</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>
