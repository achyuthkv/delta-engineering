
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

    <meta name="description" content="Get in touch with Delta Engineering Services for structural, civil, and infrastructure engineering inquiries in the Greater Toronto Area.">
    <meta name="author" content="Delta Engineering Services">
    <link rel="canonical" href="https://www.delta-engineering.ca/contact_us.php">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Delta Engineering Services">
    <meta property="og:title" content="Contact Us | Delta Engineering Services">
    <meta property="og:description" content="Get in touch for structural, civil, and infrastructure engineering inquiries in the Greater Toronto Area.">
    <meta property="og:url" content="https://www.delta-engineering.ca/contact_us.php">
    <meta property="og:image" content="https://www.delta-engineering.ca/assets/images/logo.png">

	<title>Contact Us | Delta Engineering Services</title>

	<?php
	include 'header.php';
	?>

	<main>

		<!-- Banner -->
		<div class="de-banner de-blueprint-bg">
			<div class="de-banner-inner">
				<div class="de-crumbs"><a href="index.php">Home</a> / <span class="cur">Contact Us</span></div>
				<h1>Contact Us</h1>
				<p>After receiving your message, our representative will contact you shortly. Please be as specific as possible so we can respond in an expedient manner.</p>
			</div>
		</div>

		<!-- Contact -->
		<div class="de-section">
			<div class="de-contact-grid">

				<div class="de-contact-info">
					<h3>Get in touch</h3>

					<div class="de-contact-info-row">
						<div class="icon"><svg viewBox="0 0 24 24" fill="none"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.4 21 3 13.6 3 4.6c0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.4 0 .8-.2 1.1L6.6 10.8Z" stroke="currentColor" stroke-width="1.5"/></svg></div>
						<div>
							<div class="l">Phone</div>
							<a class="v" href="tel:+14165731573">(416) 573-1573</a>
						</div>
					</div>

					<div class="de-contact-info-row">
						<div class="icon"><svg viewBox="0 0 24 24" fill="none"><path d="M3 6h18v12H3z" stroke="currentColor" stroke-width="1.5"/><path d="M3 6l9 7 9-7" stroke="currentColor" stroke-width="1.5"/></svg></div>
						<div>
							<div class="l">Email</div>
							<a class="v" href="mailto:palak@deltaengineering.ca">palak@deltaengineering.ca</a>
						</div>
					</div>

					<div class="de-contact-info-row">
						<div class="icon"><svg viewBox="0 0 24 24" fill="none"><path d="M12 3s6 6.8 6 11a6 6 0 1 1-12 0c0-4.2 6-11 6-11Z" stroke="currentColor" stroke-width="1.5"/></svg></div>
						<div>
							<div class="l">Canada Office</div>
							<div class="v">204-4211 Sheppard Ave. E.<br>Scarborough, ON M1S 5H5</div>
						</div>
					</div>

					<div class="de-contact-info-row">
						<div class="icon"><svg viewBox="0 0 24 24" fill="none"><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" stroke="currentColor" stroke-width="1.5"/></svg></div>
						<div>
							<div class="l">Oman &amp; India</div>
							<div class="v">In association with Al Hashar Engineering (Oman) and Dev Arced, Ahmedabad (India)</div>
						</div>
					</div>
				</div>

				<div class="de-form-card">
					<h3>Send us a message</h3>
					<p>Tell us about your project and we'll get back to you shortly.</p>
					<?php
					$action=$_REQUEST['action'] ?? '';
					if ($action=="")    /* display the contact form */
						{
						?>
						<form id="frmMain" action="" method="POST">
							<input type="hidden" name="action" value="submit">
							<div class="de-form-field">
								<label for="name">Name*</label>
								<input type="text" name="name" id="name" required>
							</div>
							<div class="de-form-field">
								<label for="email">Email*</label>
								<input type="email" name="email" id="email" required>
							</div>
							<div class="de-form-field">
								<label for="message">Message*</label>
								<textarea rows="6" name="message" id="message" required></textarea>
							</div>
							<button type="submit" class="de-form-submit">Send Message</button>
						</form>
						<?php
						}
					else
						{
						$name=$_REQUEST['name'] ?? '';
						$email=$_REQUEST['email'] ?? '';
						$message=$_REQUEST['message'] ?? '';
						if (($name=="")||($email=="")||($message==""))
							{
							echo '<p class="de-form-result">All fields are required, please fill <a href="contact_us.php">the form</a> again.</p>';
							}
						else{
							$from="From: $name<$email>\r\nReturn-path: $email";
							$subject="Message sent using your contact form";
							mail("palak@deltaengineering.ca", $subject, $message, $from);
							echo '<p class="de-form-result">Email sent! Thank you for reaching out — we\'ll be in touch shortly.</p>';
							}
						}
					?>
				</div>

			</div>
		</div>

	</main>

	<?php
	include 'footer.php';
	?>