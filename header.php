	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image">

	<!-- Structured data -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "ProfessionalService",
		"name": "Delta Engineering Services",
		"description": "Structural, civil, and infrastructure engineering firm serving the Greater Toronto Area since 1985.",
		"url": "https://www.delta-engineering.ca/",
		"logo": "https://www.delta-engineering.ca/assets/images/logo.png",
		"image": "https://www.delta-engineering.ca/assets/images/logo.png",
		"telephone": "+1-416-573-1573",
		"email": "palak@deltaengineering.ca",
		"foundingDate": "1985",
		"address": {
			"@type": "PostalAddress",
			"streetAddress": "204-4211 Sheppard Ave. E.",
			"addressLocality": "Scarborough",
			"addressRegion": "ON",
			"postalCode": "M1S 5H5",
			"addressCountry": "CA"
		},
		"areaServed": "Greater Toronto Area"
	}
	</script>

	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="assets/images/favicon-large.ico">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<link
		href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat+Alternates:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Playfair+Display:400,400i,700,700i,900,900i|Poppins:300,400,500,600,700|Quattrocento:400,700|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i|Roboto:100,100i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
		rel="stylesheet">

	<link
		href="https://fonts.googleapis.com/css2?family=Archivo+Expanded:wght@600;700;800&family=Public+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap"
		rel="stylesheet">

	<!-- Revolution Slider -->
	<link rel="stylesheet" type="text/css" href="assets/revolution/css/settings.css">
	<link rel="stylesheet" type="text/css" href="assets/revolution/fonts/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="assets/revolution/fonts/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/revolution/css/layers.css">
	<link rel="stylesheet" type="text/css" href="assets/revolution/css/navigation.css">

	<!-- Bootstrap -->
	<link href="assets/css/lib.css" rel="stylesheet">

	<!-- Plugins -->
	<link href="assets/js/slick/slick.css" rel="stylesheet">
	<link href="assets/js/slick/slick-theme.css" rel="stylesheet">
	<link href="assets/css/plugins.css" rel="stylesheet">
	<link href="assets/css/elements.css" rel="stylesheet">
	<link href="assets/css/header.css" rel="stylesheet">
	<link href="assets/css/rtl.css" rel="stylesheet">
	<link id="color" href="assets/css/color-schemes/default.css" rel="stylesheet">

	<!-- Theme -->
	<link rel="stylesheet" type="text/css" href="style.css">

	<!-- Blueprint redesign -->
	<link rel="stylesheet" type="text/css" href="assets/css/redesign.css">

	<!-- Header overrides -->
	<style>
		@media only screen and (max-width: 768px) {
			#headerrr {
				margin-top: 45px;
			}
		}

		@media only screen and (min-width: 769px) {
			#headerrr {
				margin-top: -20px;
			}
		}

		/* WhatsApp floating button */
		.float {
			position: fixed;
			width: 60px;
			height: 60px;
			bottom: 40px;
			right: 20px;
			background-color: #25d366;
			color: #fff;
			border-radius: 50px;
			text-align: center;
			font-size: 30px;
			box-shadow: 2px 2px 3px #999;
			z-index: 100;
		}

		.my-float {
			margin-top: 16px;
		}

		/*
		 * Header phone CTA
		 * Overrides the older theme styles so the phone number
		 * remains clearly readable on the dark navy button.
		 */
		.de-nav-cta {
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 9px !important;
			min-width: 196px;
			height: 48px;
			padding: 0 20px !important;
			background: #101a30 !important;
			color: #fff !important;
			border: 1px solid #101a30 !important;
			border-radius: 4px;
			text-decoration: none !important;
			box-sizing: border-box;
		}

		.de-nav-cta svg {
			width: 18px !important;
			height: 18px !important;
			flex: 0 0 18px;
			color: #fff !important;
			stroke: #fff !important;
		}

		.de-nav-cta-text {
			color: #fff !important;
			font-weight: 600 !important;
			letter-spacing: 0.2px;
			white-space: nowrap;
		}

		.de-nav-cta:hover,
		.de-nav-cta:focus,
		.de-nav-cta:active {
			background: #1a2945 !important;
			color: #fff !important;
		}

		.de-nav-cta:hover svg,
		.de-nav-cta:focus svg,
		.de-nav-cta:active svg {
			color: #fff !important;
			stroke: #fff !important;
		}

		.de-nav-cta:hover .de-nav-cta-text,
		.de-nav-cta:focus .de-nav-cta-text,
		.de-nav-cta:active .de-nav-cta-text {
			color: #fff !important;
		}

		/* Keep the CTA aligned with the navigation on desktop */
		@media only screen and (min-width: 769px) {
			.de-nav-cta {
				margin-left: 10px;
				vertical-align: middle;
			}
		}

		/* Mobile navigation */
		@media only screen and (max-width: 767px) {
			.de-nav-cta {
				min-width: 0;
				width: 100%;
				height: 46px;
				margin-top: 12px;
			}
		}

		/* =========================================
   DESKTOP DROPDOWN — OPEN ON HOVER
   ========================================= */
		@media only screen and (min-width: 768px) {

			.de-nav-dropdown {
				position: relative;
			}

			/* Hide dropdown by default */
			.de-nav-dropdown-menu {
				display: none;
				position: absolute;
				top: 100%;
				left: 0;
				z-index: 9999;
			}

			/* Show dropdown when hovering over Services/Gallery */
			.de-nav-dropdown:hover .de-nav-dropdown-menu {
				display: block;
			}

			/* Keep dropdown open while moving mouse
	   from the parent link into the submenu */
			.de-nav-dropdown-menu:hover {
				display: block;
			}

			/* Dropdown links */
			.de-nav-dropdown-menu a {
				display: block;
				white-space: nowrap;
			}

			/* Prevent the dropdown toggle itself from changing appearance */
			.de-nav-dropdown:hover>.de-nav-dropdown-toggle {
				color: inherit;
			}
		}


		/* =========================================
		MOBILE DROPDOWN — CLICK TO OPEN
		========================================= */
		@media only screen and (max-width: 767px) {

			.de-nav-dropdown-menu {
				display: none;
			}

			.de-nav-dropdown.is-open .de-nav-dropdown-menu {
				display: block;
			}
		}
	</style>

	<!--[if lt IE 9]>
		<script src="js/html5/respond.min.js"></script>
	<![endif]-->
</head>

<body data-offset="200" data-spy="scroll" data-target=".ow-navigation">

	<!-- WhatsApp floating button -->
	<a href="https://wa.me/+14165799787?text=Hi%2C%20I%27d%20like%20to%20enquire%20about%20your%20engineering%20services"
		class="float" target="_blank" rel="noopener" aria-label="WhatsApp">
		<i class="fa fa-whatsapp my-float"></i>
	</a>

	<div class="de-root">

		<header class="de-nav de-blueprint-bg">

			<a href="index.php" class="de-nav-logo">
				<img src="assets/images/logo.png" alt="Delta Engineering Services">
			</a>

			<button type="button" class="de-nav-toggle" id="deNavToggle" aria-label="Toggle navigation"
				aria-expanded="false" aria-controls="deNavLinks">
				<span></span>
				<span></span>
				<span></span>
			</button>

			<nav class="de-nav-links" id="deNavLinks">

				<a href="index.php" title="Home">Home</a>

				<div class="de-nav-dropdown" id="deServicesDropdown">
					<a href="#" class="de-nav-dropdown-toggle" title="Services">
						Services
						<svg viewBox="0 0 10 10" fill="none" aria-hidden="true">
							<path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.4" />
						</svg>
					</a>

					<div class="de-nav-dropdown-menu">
						<a href="structural_engineering.php" title="Structural Engineering">Structural Engineering</a>
						<a href="civil_engineering.php" title="Civil Engineering">Civil Engineering</a>
						<a href="infrastructure_and_municipal_engineering.php"
							title="Infrastructure &amp; Municipal Engineering">Infrastructure &amp; Municipal
							Engineering</a>
						<a href="bim_mechanical_electrical_engineering.php"
							title="BIM, Mechanical &amp; Electrical Engineering">BIM, Mechanical &amp; Electrical
							Engineering</a>
						<a href="project_management.php" title="Project Management">Project Management</a>
					</div>
				</div>

				<a href="projects.php" title="Projects">Projects</a>

				<div class="de-nav-dropdown" id="deGalleryDropdown">
					<a href="#" class="de-nav-dropdown-toggle" title="Gallery">
						Gallery
						<svg viewBox="0 0 10 10" fill="none" aria-hidden="true">
							<path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.4" />
						</svg>
					</a>

					<div class="de-nav-dropdown-menu">
						<a href="gallery_canada_projects.php" title="Canada Projects">Canada Projects</a>
						<a href="gallery_international_projects.php" title="International Projects">International
							Projects</a>
					</div>
				</div>

				<div class="de-nav-dropdown" id="deLocationsDropdown">
					<a href="#" class="de-nav-dropdown-toggle" title="Locations">
						Locations
						<svg viewBox="0 0 10 10" fill="none" aria-hidden="true">
							<path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.4" />
						</svg>
					</a>

					<div class="de-nav-dropdown-menu">
						<a href="index.php" title="Canada">Canada</a>
						<a href="oman_engineering_services.php" title="Oman">Oman</a>
						<a href="india_engineering_services.php" title="India">India</a>
					</div>
				</div>

				<a href="about_us.php" title="About">About</a>
				<a href="contact_us.php" title="Contact">Contact</a>

				<a href="tel:+14165731573" title="Call (416) 573-1573" class="de-nav-cta">
					<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<path
							d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.4 21 3 13.6 3 4.6c0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.4 0 .8-.2 1.1L6.6 10.8Z"
							stroke="currentColor" stroke-width="1.5" />
					</svg>
					<span class="de-nav-cta-text">(416) 573-1573</span>
				</a>

			</nav>
		</header>

		<script>
			(function () {
				var toggle = document.getElementById('deNavToggle');
				var links = document.getElementById('deNavLinks');

				if (toggle && links) {
					toggle.addEventListener('click', function () {
						var open = links.classList.toggle('is-open');
						toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
					});
				}

				['deServicesDropdown', 'deGalleryDropdown', 'deLocationsDropdown'].forEach(function (id) {
					var dd = document.getElementById(id);
					if (!dd) return;

					var link = dd.querySelector('.de-nav-dropdown-toggle');

					if (link) {
						link.addEventListener('click', function (e) {
							if (window.innerWidth <= 767) {
								e.preventDefault();
								dd.classList.toggle('is-open');
							}
						});
					}
				});
			})();
		</script>