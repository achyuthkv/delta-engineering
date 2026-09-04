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

    <meta name="description" content="Photo gallery of Delta Engineering Services' completed engineering projects across Canada.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/gallery_canada_projects.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Gallery - Canada Projects | Delta Engineering Services">
    <meta property="og:description" content="Completed engineering projects across Canada.">
    <meta property="og:url" content="https://www.delta-engineering.ca/gallery_canada_projects.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<title>Gallery - Canada Projects | Delta Engineering Services</title>

	<?php
	include 'header.php';

	// Photos are managed through /admin/gallery.php (office = canada).
	$dePhotos = [];
	try {
		require_once __DIR__ . '/admin/includes/db.php';
		$dePhotos = de_db()->query(
			"SELECT image_path, alt_text, title, location FROM gallery_photos
			 WHERE office = 'canada' AND is_published = 1
			 ORDER BY sort_order, id"
		)->fetchAll();
	} catch (Throwable $e) {
		$dePhotos = [];
	}
	?>
	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Canada Projects</span></div>
				<div class="de-eyebrow">Canada &middot; Greater Toronto Area</div>
				<h1>Canada Projects</h1>
				<p>A photo record of completed structural and civil engineering work across Canada, spanning industrial buildings, temples, and custom homes.</p>
			</div>
		</div>

		<div class="de-section">
			<?php if (!$dePhotos): ?>
				<p style="text-align:center;color:#6c7690">Gallery is temporarily unavailable.</p>
			<?php else: ?>
			<div class="gallery-section de-photo-grid">
				<?php foreach ($dePhotos as $photo): ?>
				<div class="de-photo-item">
					<div class="content-image-block">
						<img src="<?= htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($photo['alt_text'], ENT_QUOTES, 'UTF-8') ?>">
						<div class="content-block-hover">
							<a class="zoom-in" href="<?= htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8') ?>"><i class="fa fa-search" style="font-size:30px;margin-left:25px"></i></a>
						</div>
					</div>
					<div class="de-photo-cap"><?= htmlspecialchars($photo['title'], ENT_QUOTES, 'UTF-8') ?><span><?= htmlspecialchars($photo['location'], ENT_QUOTES, 'UTF-8') ?></span></div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>

		<!-- CTA band -->
		<div class="de-cta-band">
			<div class="de-cta-band-inner">
				<div>
					<h2>Have a project like these?</h2>
					<p>(416) 573-1573 &nbsp;&middot;&nbsp; palak@deltaengineering.ca</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>
