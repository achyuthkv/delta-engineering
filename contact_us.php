
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
		
	<div class="main-container">
		<main>
			<!-- Page Banner -->
			<div class="page-banner container-fluid no-left-padding no-right-padding" style="background-image: url('assets/images/contact/header.jpg');background-position: center;">
				<!-- Container -->
				<div class="container">
					<div class="banner-content">
						<ol class="breadcrumb">
							<li class="active">Contact Us</li>
						</ol>
					</div>
				</div><!-- Container /- -->
			</div><!-- Page Banner -->
			
			<!-- Contact Us -->
			<div class="contact-us container-fluid no-left-padding no-right-padding" style="padding-bottom:30px">
				<!-- Container -->
				<div class="container">

					<div class="contact-header">
						<h5>Get in touch with us</h5>
						<p>After receiving your message, our representative will contact you shortly.</p>
						<p>We request you to be as specific as possible so that we can respond to you in an expedient manner.</p>
					</div>

					<div class="contact-form">
						<?php
						$action=$_REQUEST['action'] ?? '';
						if ($action=="")    /* display the contact form */
							{
							?>
							<form id="frmMain" action="" method="POST">
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
								<input type="hidden" name="action" value="submit">
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
								<input type="text" class="form-control" placeholder="Name*" name="name" id="name" required="">
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
								<input type="email" class="form-control" placeholder="Email*" name="email" id="email" required="">
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12 form-group">
								<textarea class="form-control" placeholder="Message" rows="6" name="message" id="message" required=""></textarea>
								</div>
								<div class="form-group col-md-12 col-sm-12 col-xs-12">
								<input type="submit" value="Send email"/>
								</div>
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
								echo "All fields are required, please fill <a href=\"\">the form</a> again.";
								}
							else{        
								$from="From: $name<$email>\r\nReturn-path: $email";
								$subject="Message sent using your contact form";
								mail("palak@deltaengineering.ca", $subject, $message, $from);
								echo "Email sent!"."<br>"."<br>";
								}
							}  
						?>
					</div>
				</div><!-- Container /- -->
			</div><!-- Contact Us /- -->
			
			
			
			
			
		</main>
	</div>
	
	<?php
	include 'footer.php';
	?>