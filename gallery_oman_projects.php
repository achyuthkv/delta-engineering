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

    <meta name="description" content="Photo gallery of Delta Engineering Services' past international project work in Oman, delivered with Al Hashar Engineering.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/gallery_oman_projects.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Gallery - Oman Projects | Delta Engineering Services">
    <meta property="og:description" content="Past international project work in Oman, delivered with Al Hashar Engineering across Muscat, Salalah, Sohar, and Nizwa.">
    <meta property="og:url" content="https://www.delta-engineering.ca/gallery_oman_projects.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "BreadcrumbList",
		"itemListElement": [
			{ "@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.delta-engineering.ca/" },
			{ "@type": "ListItem", "position": 2, "name": "Oman Projects", "item": "https://www.delta-engineering.ca/gallery_oman_projects.php" }
		]
	}
	</script>

	<title>Gallery - Oman Projects | Delta Engineering Services</title>

	<?php
	include 'header.php';

	// Photos are managed through /admin/gallery.php (office = oman).
	require_once __DIR__ . '/includes/gallery-grid.php';
	$deOmanPhotos = [];
	try {
		require_once __DIR__ . '/admin/includes/db.php';
		$stmt = de_db()->prepare(
			"SELECT image_path, alt_text, title, location, project_id FROM gallery_photos
			 WHERE office = 'oman' AND is_published = 1
			 ORDER BY sort_order, id"
		);
		$stmt->execute();
		$deOmanPhotos = $stmt->fetchAll();
	} catch (Throwable $e) {
		$deOmanPhotos = [];
	}
	?>
	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Oman Projects</span></div>
				<div class="de-eyebrow">Oman &mdash; Past International Work</div>
				<h1>Delivered with Al Hashar Engineering.</h1>
				<p>Commercial, hospitality, residential, and master-planning work completed across Muscat, Salalah, Sohar, and Nizwa before we consolidated to Canada and India. See the <a href="oman_engineering_services.php">Oman services overview</a> for more on this work.</p>
			</div>
		</div>

		<div class="de-section">
			<?php if (!$deOmanPhotos): ?>
				<p style="text-align:center;color:#6c7690">Gallery is temporarily unavailable.</p>
			<?php else: ?>
			<div class="gallery-section de-photo-grid">
				<?php de_render_gallery_grid($deOmanPhotos); ?>
			</div>
			<?php endif; ?>
		</div>

		<!-- CTA band -->
		<div class="de-cta-band">
			<div class="de-cta-band-inner">
				<div>
					<h2>Have a project like this?</h2>
					<p>We now work out of Canada and India &mdash; <a href="contact_us.php" style="color:inherit;text-decoration:underline">get in touch</a> and we'll point you to the right office.</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>
