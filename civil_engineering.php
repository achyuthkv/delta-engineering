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

    <meta name="description" content="Civil engineering services in Toronto and the Greater Toronto Area — planning, design, site development, and transportation systems for construction projects.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/civil_engineering.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Civil Engineering Services in Toronto &amp; the GTA | Delta Engineering">
    <meta property="og:description" content="Planning, design, and transportation systems for construction projects across Toronto and the Greater Toronto Area.">
    <meta property="og:url" content="https://www.delta-engineering.ca/civil_engineering.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Service",
		"serviceType": "Civil Engineering",
		"name": "Civil Engineering Services",
		"description": "Planning, design, and transportation systems for construction projects across Toronto and the Greater Toronto Area.",
		"provider": { "@type": "ProfessionalService", "name": "Delta Engineering Services", "url": "https://www.delta-engineering.ca/" },
		"areaServed": "Greater Toronto Area",
		"url": "https://www.delta-engineering.ca/civil_engineering.php"
	}
	</script>

	<title>Civil Engineering Services in Toronto &amp; the GTA | Delta Engineering</title>

	<?php
	include 'header.php';

	// Each service page supports ?loc=india alongside the Canada default,
	// so a single URL can be shared (WhatsApp, campaigns) that lands a
	// visitor on this discipline's India-specific proof and contact context.
	$deLoc = (($_GET['loc'] ?? '') === 'india') ? 'india' : 'canada';
	$deDiscipline = 'civil';
	$deFeatured = [];
	try {
		require_once __DIR__ . '/admin/includes/db.php';
		$stmt = de_db()->prepare(
			"SELECT category, description FROM projects
			 WHERE discipline = ? AND office = ? AND is_published = 1
			 ORDER BY sort_order, id LIMIT 4"
		);
		$stmt->execute([$deDiscipline, $deLoc]);
		$deFeatured = $stmt->fetchAll();
	} catch (Throwable $e) {
		$deFeatured = [];
	}
	?>

	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Civil Engineering</span></div>
				<div class="de-loc-switch" role="tablist" aria-label="Choose office location">
					<a href="?loc=canada" class="<?= $deLoc === 'canada' ? 'active' : '' ?>">Canada</a>
					<a href="?loc=india" class="<?= $deLoc === 'india' ? 'active' : '' ?>">India</a>
				</div>
				<h1>Civil Engineering Services in Toronto &amp; the GTA</h1>
				<p>Planning, design, and distribution systems for construction projects — from site layout to transportation, delivered with the same rigour behind 1,000+ GTA buildings since 1985.</p>
			</div>
		</div>

		<!-- Intro -->
		<div class="de-intro">
			<div class="de-intro-grid">
				<div class="de-intro-copy">
					<p>Delta Engineering Services provides full-fledged civil engineering services to clients across the Greater Toronto Area and globally, from initial planning through to final construction documentation.</p>
					<p>Our civil engineering solutions cover planning, design, transportation systems, and distribution systems — every aspect required to move a project from concept to construction.</p>
					<div class="de-intro-stats">
					<?php if ($deLoc === 'canada'): ?>
						<div class="de-stat"><div class="n">1,000+</div><div class="l">Buildings Engineered</div></div>
						<div class="de-stat"><div class="n">10M+</div><div class="l">Sq. Ft. Delivered</div></div>
						<div class="de-stat"><div class="n">40+</div><div class="l">Years in the GTA</div></div>
					<?php else: ?>
						<div class="de-stat"><div class="n"><?= count($deFeatured) ?></div><div class="l">Documented India Project<?= count($deFeatured) === 1 ? '' : 's' ?></div></div>
						<div class="de-stat"><div class="n">2</div><div class="l">India Offices &mdash; Ahmedabad &amp; Bangalore</div></div>
					<?php endif; ?>
					</div>
				</div>
				<img src="assets/images/slider/02_02.jpg" alt="Civil engineering site works, GTA">
			</div>
		</div>

		<!-- Spec list -->
		<div class="de-spec">
			<div class="de-spec-inner">
				<div class="de-section-eyebrow" style="color:#7fa0d9">Civil Engineering Services</div>
				<h2>What we design and engineer.</h2>
				<div class="de-spec-grid">
					<div>
						<div class="de-spec-row"><span class="de-spec-tick">01</span><p>Industrial Buildings</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">02</span><p>Commercial Buildings &amp; Plazas</p></div>
					</div>
					<div>
						<div class="de-spec-row"><span class="de-spec-tick">03</span><p>Restaurants</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">04</span><p>Renovations</p></div>
					</div>
				</div>
			</div>
		</div>

		<!-- Featured projects (location-scoped proof of work) -->
		<?php if ($deFeatured): ?>
		<div class="de-proj-feat">
			<div class="de-proj-feat-inner">
				<div class="de-section-eyebrow">Proof of Work</div>
				<h2><?= $deLoc === 'india' ? 'Recent India projects.' : 'Recent Canada projects.' ?></h2>
				<div class="de-proj-feat-grid">
					<?php foreach ($deFeatured as $p): ?>
						<div class="de-proj-card">
							<div class="cat"><?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></div>
							<p><?= htmlspecialchars($p['description'], ENT_QUOTES, 'UTF-8') ?></p>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="de-proj-feat-foot">
					<a href="<?= $deLoc === 'india' ? 'gallery_international_projects.php' : 'gallery_canada_projects.php' ?>" class="de-about-link">View <?= $deLoc === 'india' ? 'India' : 'Canada' ?> project gallery <svg viewBox="0 0 12 12" fill="none"><path d="M2 6h8m0 0L6 2m4 4L6 10" stroke="currentColor" stroke-width="1.4"/></svg></a>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<!-- CTA band -->
		<div class="de-cta-band">
			<div class="de-cta-band-inner">
				<div>
					<h2><?= $deLoc === 'india' ? 'Have a project in India?' : 'Have a site to plan?' ?></h2>
					<p>(416) 573-1573 &nbsp;&middot;&nbsp; (437) 986-3858 &nbsp;&middot;&nbsp; info@delta-engineering.ca</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>