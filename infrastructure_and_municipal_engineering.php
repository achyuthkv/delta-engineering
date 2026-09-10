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

    <meta name="description" content="Infrastructure and municipal engineering services for GTA municipalities — roadway design, water and sewer systems, and capital improvement planning.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/infrastructure_and_municipal_engineering.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Infrastructure &amp; Municipal Engineering | Toronto &amp; the GTA | Delta Engineering">
    <meta property="og:description" content="Roadway design, water and sewer systems, and capital improvement planning for municipalities across the Greater Toronto Area.">
    <meta property="og:url" content="https://www.delta-engineering.ca/infrastructure_and_municipal_engineering.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Service",
		"serviceType": "Infrastructure and Municipal Engineering",
		"name": "Infrastructure & Municipal Engineering Services",
		"description": "Roadway design, water and sewer systems, and capital improvement planning for municipalities across the Greater Toronto Area.",
		"provider": { "@type": "ProfessionalService", "name": "Delta Engineering Services", "url": "https://www.delta-engineering.ca/" },
		"areaServed": "Greater Toronto Area",
		"url": "https://www.delta-engineering.ca/infrastructure_and_municipal_engineering.php"
	}
	</script>

	<title>Infrastructure &amp; Municipal Engineering | Toronto &amp; the GTA | Delta Engineering</title>

	<?php
	include 'header.php';

	// $deLoc (canada/india) comes from includes/location.php, resolved
	// inside header.php -- see that file for how ?loc= and the de_loc
	// cookie interact. This page's own contribution is which discipline
	// to pull "Featured Projects" for.
	$deDiscipline = 'infrastructure';
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
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Infrastructure &amp; Municipal Engineering</span></div>
				<h1>Infrastructure &amp; Municipal Engineering in the GTA</h1>
				<p>Working with municipalities and local governments across the GTA — roadway design, water and sewer systems, and capital improvement planning delivered as an extension of your own staff.</p>
			</div>
		</div>

		<!-- Intro -->
		<div class="de-intro">
			<div class="de-intro-grid">
				<div class="de-intro-copy">
					<p>Working with municipalities and local governments across the Greater Toronto Area, Delta Engineering Services understands the complex infrastructure needs of today's communities. We offer comprehensive services to help you meet these needs and accomplish your goals.</p>
					<p>We foster relationships with our municipal clients and consider ourselves as an extension of their staff. Our communication, responsiveness, and attention to detail are what set us apart from other competitors.</p>
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
				<img src="assets/images/slider/03_02.jpg" alt="Municipal infrastructure works, GTA">
			</div>
		</div>

		<!-- Spec list -->
		<div class="de-spec">
			<div class="de-spec-inner">
				<div class="de-section-eyebrow" style="color:#7fa0d9">Municipal Engineering Services</div>
				<h2>What we design and engineer.</h2>
				<div class="de-spec-grid de-spec-grid-3">
					<div>
						<div class="de-spec-row"><span class="de-spec-tick">01</span><p>Industrial Buildings</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">02</span><p>City Engineering Services</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">03</span><p>Roadway Design</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">04</span><p>Infrastructure Assessment</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">05</span><p>Capital Improvement Planning</p></div>
					</div>
					<div>
						<div class="de-spec-row"><span class="de-spec-tick">06</span><p>Water Systems</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">07</span><p>Sanitary Sewer Systems</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">08</span><p>Storm Sewer Systems</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">09</span><p>Detention Ponds</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">10</span><p>Planning Assistance</p></div>
					</div>
					<div>
						<div class="de-spec-row"><span class="de-spec-tick">11</span><p>Plan Submittal and Ordinance Reviews</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">12</span><p>Safe Routes to School</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">13</span><p>Traffic Signals</p></div>
						<div class="de-spec-row"><span class="de-spec-tick">14</span><p>Public Involvement</p></div>
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
					<h2><?= $deLoc === 'india' ? 'Have a project in India?' : 'Planning a municipal project?' ?></h2>
					<p>(416) 573-1573 &nbsp;&middot;&nbsp; (437) 986-3858 &nbsp;&middot;&nbsp; info@delta-engineering.ca</p>
				</div>
				<a href="contact_us.php" class="de-btn-primary">Request a Consultation</a>
			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>