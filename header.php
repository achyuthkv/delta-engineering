	<!-- Favicon (Delta Engineering "E" mark) -->
	<link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">

	<!-- For iPhone 4 Retina display: -->
	<link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-114x114-precomposed.png">

	<!-- For iPad: -->
	<link rel="apple-touch-icon-precomposed" href="assets/images//apple-touch-icon-72x72-precomposed.png">

	<!-- For iPhone: -->
	<link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57x57-precomposed.png">

	<!-- Library - Google Font Familys -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Montserrat+Alternates:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Playfair+Display:400,400i,700,700i,900,900i|Poppins:300,400,500,600,700|Quattrocento:400,700|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Archivo+Expanded:wght@600;700;800&family=Public+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="assets/revolution/css/settings.css">
	
	<link rel="stylesheet" type="text/css" href="assets/revolution/fonts/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="assets/revolution/fonts/font-awesome/css/font-awesome.min.css">
	
	<!-- RS5.0 Layers and Navigation Styles -->
	<link rel="stylesheet" type="text/css" href="assets/revolution/css/layers.css">
	<link rel="stylesheet" type="text/css" href="assets/revolution/css/navigation.css">
	
	<!-- Library - Bootstrap v3.3.5 -->
    <link href="assets/css/lib.css" rel="stylesheet">
	
	<link href="assets/js/slick/slick.css" rel="stylesheet">	
    <link href="assets/js/slick/slick-theme.css" rel="stylesheet">
	
	<!-- Custom - Common CSS -->
	<link href="assets/css/plugins.css" rel="stylesheet">
	<link href="assets/css/elements.css" rel="stylesheet">
	<link href="assets/css/header.css" rel="stylesheet">
	<link href="assets/css/rtl.css" rel="stylesheet">
	<link id="color" href="assets/css/color-schemes/default.css" rel="stylesheet">
	<!-- Custom - Theme CSS -->
	<link rel="stylesheet" type="text/css" href="style.css">
	<!-- Redesign - Blueprint Technical design system -->
	<link rel="stylesheet" type="text/css" href="assets/css/redesign.css">
	<!--[if lt IE 9]>
		<script src="js/html5/respond.min.js"></script>
    <![endif]-->



<style>

@media only screen and (max-width:768px)
{
#headerrr { margin-top:45px; }
}
@media only screen and (min-width:769px)
{
#headerrr { margin-top:-20px; }
}

.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	font-size:30px;
	box-shadow: 2px 2px 3px #999;
	z-index:100;
}

.my-float{
	margin-top:16px;
}

</style>

</head>

<body data-offset="200" data-spy="scroll" data-target=".ow-navigation">

<a onclick="location.href='https://wa.me/+14165799787';" class="float" target="_blank">
<i class="fa fa-whatsapp my-float"></i>
</a>

<div class="de-root">
	<header class="de-nav de-blueprint-bg">
		<a href="index.php" class="de-nav-logo"><img src="assets/images/logo.png" alt="Delta Engineering Services" width="170"></a>
		<button type="button" class="de-nav-toggle" id="deNavToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="deNavLinks">
			<span></span><span></span><span></span>
		</button>
		<nav class="de-nav-links" id="deNavLinks">
			<a href="index.php" title="Home">Home</a>
			<div class="de-nav-dropdown" id="deServicesDropdown">
				<a href="#" class="de-nav-dropdown-toggle" title="Services">Services <svg viewBox="0 0 10 10" fill="none"><path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.4"/></svg></a>
				<div class="de-nav-dropdown-menu">
					<a href="structural_engineering.php" title="Structural Engineering">Structural Engineering</a>
					<a href="civil_engineering.php" title="Civil Engineering">Civil Engineering</a>
					<a href="infrastructure_and_municipal_engineering.php" title="Infrastructure &amp; Municipal Engineering">Infrastructure &amp; Municipal Engineering</a>
					<a href="bim_mechanical_electrical_engineering.php" title="BIM, Mechanical &amp; Electrical Engineering">BIM, Mechanical &amp; Electrical Engineering</a>
					<a href="project_management.php" title="Project Management">Project Management</a>
				</div>
			</div>
			<a href="projects.php" title="Projects">Projects</a>
			<div class="de-nav-dropdown" id="deGalleryDropdown">
				<a href="#" class="de-nav-dropdown-toggle" title="Gallery">Gallery <svg viewBox="0 0 10 10" fill="none"><path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.4"/></svg></a>
				<div class="de-nav-dropdown-menu">
					<a href="gallery_canada_projects.php" title="Canada Projects">Canada Projects</a>
					<a href="gallery_international_projects.php" title="International Projects">International Projects</a>
				</div>
			</div>
			<a href="about_us.php" title="About">About</a>
			<a href="contact_us.php" title="Contact">Contact</a>
		</nav>
		<a href="tel:+14165731573" title="(416) 573-1573" class="de-nav-cta">
			<svg viewBox="0 0 24 24" fill="none"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.4 21 3 13.6 3 4.6c0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.4 0 .8-.2 1.1L6.6 10.8Z" stroke="currentColor" stroke-width="1.5"/></svg>
			(416) 573-1573
		</a>
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
			['deServicesDropdown', 'deGalleryDropdown'].forEach(function (id) {
				var dd = document.getElementById(id);
				if (!dd) return;
				var link = dd.querySelector('.de-nav-dropdown-toggle');
				link.addEventListener('click', function (e) {
					if (window.innerWidth <= 767) {
						e.preventDefault();
						dd.classList.toggle('is-open');
					}
				});
			});
		})();
	</script>