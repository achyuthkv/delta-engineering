<?php
header('Access-Control-Allow-Origin: *');
function sendMail($name, $email, $number, $msg) {

	require 'mailer/PHPMailerAutoload.php';
	require file_exists(__DIR__ . '/mailer/mailCorporate-credentials.php')
		? __DIR__ . '/mailer/mailCorporate-credentials.php'
		: __DIR__ . '/mailer/mailCorporate-credentials.example.php';

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = MAILCORP_SMTP_HOST;
	$mail->SMTPAuth = true;
	$mail->Username = MAILCORP_SMTP_USERNAME;
	$mail->Password = MAILCORP_SMTP_PASSWORD;
	$mail->SMTPSecure = MAILCORP_SMTP_SECURE;
	$mail->Port = MAILCORP_SMTP_PORT;

	$mail->setFrom(MAILCORP_FROM_EMAIL, MAILCORP_FROM_NAME);
	$mail->addAddress(MAILCORP_FROM_EMAIL, '');
	$mail->addCC($email);
	$mail->addBCC(MAILCORP_BCC);
	$mail->isHTML(true);

	$mail->Subject = 'Delta Engineering: Thank you for contacting us.';
	$mail->Body    = "We have received your message.<br/>Our representative will contact you shortly.<br/><br/>If you need more help or would like to contact us<br> please email us at admin@deltaengineering.ca<br/><br/><br/>Details:<br/>Name: ".$name."<br/>Email: ".$email."<br/>Contact Number: ".$number."<br/>Message: ".$msg;
	
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		?><script type="text/javascript" charset="utf-8" async defer>
       window.location.href = 'message_success.php';
       </script><?php
		
	}
}

	?>
