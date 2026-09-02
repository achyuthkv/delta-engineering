<?php

date_default_timezone_set('Etc/UTC');

	require 'mailer/PHPMailerAutoload.php';
	require file_exists(__DIR__ . '/config-credentials.php')
		? __DIR__ . '/config-credentials.php'
		: __DIR__ . '/config-credentials.example.php';

	//Create a new PHPMailer instance
		$mail = new PHPMailer;
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = MAILER_CONFIG_SMTP_HOST;                // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = MAILER_CONFIG_SMTP_USERNAME;        // SMTP username
		$mail->Password = MAILER_CONFIG_SMTP_PASSWORD;        // SMTP password
		$mail->SMTPSecure = MAILER_CONFIG_SMTP_SECURE;        // Enable TLS encryption, `ssl` also accepted
		$mail->Port = MAILER_CONFIG_SMTP_PORT;                // TCP port to connect to

		$mail->setFrom(MAILER_CONFIG_FROM_EMAIL, MAILER_CONFIG_FROM_NAME);
		$mail->addAddress($emailIdStr, '');     // Add a recipient
		// $mail->addAddress('ellen@example.com');               // Name is optional
		// $mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC(MAILER_CONFIG_CC);
		// $mail->addBCC('bcc@example.com');

		// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);  
	                              // Set email format to HTML
                              
?>