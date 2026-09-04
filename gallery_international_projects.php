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

    <meta name="description" content="Photo gallery of Delta Engineering Services' completed engineering projects internationally, including Oman and India.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/gallery_international_projects.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Gallery - International Projects | Delta Engineering Services">
    <meta property="og:description" content="Completed engineering projects internationally, including Oman and India.">
    <meta property="og:url" content="https://www.delta-engineering.ca/gallery_international_projects.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<title>Gallery - International Projects | Delta Engineering Services</title>

	<?php
	include 'header.php';

	// Photos are managed through /admin/gallery.php (office = oman / india).
	$deOmanPhotos = [];
	$deIndiaPhotos = [];
	try {
		require_once __DIR__ . '/admin/includes/db.php';
		$stmt = de_db()->prepare(
			"SELECT image_path, alt_text, title, location FROM gallery_photos
			 WHERE office = ? AND is_published = 1
			 ORDER BY sort_order, id"
		);
		$stmt->execute(['oman']);
		$deOmanPhotos = $stmt->fetchAll();
		$stmt->execute(['india']);
		$deIndiaPhotos = $stmt->fetchAll();
	} catch (Throwable $e) {
		$deOmanPhotos = [];
		$deIndiaPhotos = [];
	}

	function de_render_photo_grid(array $photos): void {
		foreach ($photos as $photo) {
			$path = htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8');
			$alt = htmlspecialchars($photo['alt_text'], ENT_QUOTES, 'UTF-8');
			$title = htmlspecialchars($photo['title'], ENT_QUOTES, 'UTF-8');
			$location = htmlspecialchars($photo['location'], ENT_QUOTES, 'UTF-8');
			echo <<<HTML
			<div class="de-photo-item">
				<div class="content-image-block">
					<img src="{$path}" alt="{$alt}">
					<div class="content-block-hover">
						<a class="zoom-in" href="{$path}"><i class="fa fa-search" style="font-size:30px;margin-left:25px"></i></a>
					</div>
				</div>
				<div class="de-photo-cap">{$title}<span>{$location}</span></div>
			</div>
			HTML;
		}
	}
	?>
	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">International Projects</span></div>
				<h1>International Projects</h1>
				<p>A photo record of completed engineering work internationally, including our <a href="oman_engineering_services.php">Oman</a> and India offices.</p>
			</div>
		</div>

		<div class="de-section">
			<div class="de-section-head">
				<div class="de-section-eyebrow">Oman</div>
				<h2>Delivered with Al Hashar Engineering.</h2>
				<p class="de-section-sub">Commercial, hospitality, residential, and master-planning work across Muscat, Salalah, Sohar, and Nizwa. See the <a href="oman_engineering_services.php">Oman services overview</a>.</p>
			</div>
			<?php if (!$deOmanPhotos): ?>
				<p style="text-align:center;color:#6c7690">Gallery is temporarily unavailable.</p>
			<?php else: ?>
			<div class="gallery-section de-photo-grid">
				<?php de_render_photo_grid($deOmanPhotos); ?>
			</div>
			<?php endif; ?>
		</div>

		<div class="de-section">
			<div class="de-section-head">
				<div class="de-section-eyebrow">India</div>
				<h2>India.</h2>
				<p class="de-section-sub">Our India project archive is still being built out &mdash; see the <a href="india_engineering_services.php">India office overview</a> for more.</p>
			</div>
			<?php if (!$deIndiaPhotos): ?>
				<p style="text-align:center;color:#6c7690">No photos published yet.</p>
			<?php else: ?>
			<div class="gallery-section de-photo-grid">
				<?php de_render_photo_grid($deIndiaPhotos); ?>
			</div>
			<?php endif; ?>
		</div>

		<!-- CTA band -->
		<div class="de-cta-band">
			<div class="de-cta-band-inner">
				<div>
					<h2>Working internationally too?</h2>
					<p>(416) 573-1573 &nbsp;&middot;&nbsp; palak@deltaengineering.ca</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>
