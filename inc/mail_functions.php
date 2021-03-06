<?php
require_once 'Classes/PHPMailer/class.phpmailer.php';
require_once('Connections/connection.php');
require_once('inc/credentials.php');

function send_mail($type, $id, $to, $subject, $body, $attachments, $cc, $custombody = NULL, $desc = NULL, $from=array('name'=>'Dusan Asesor de Seguros', 'email'=>'info@dusanasegurador.com.ar')) {
	global $connection, $mail_username, $mail_password;
	$mail = new PHPMailer(true); 
	$recipients = array();

	try {
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Host = "smtp.googlemail.com";
		$mail->Port = 465;
		$mail->Username = $from['email'];
		$mail->Password = $mail_password;
		 
		$mail->SMTPDebug = 1;
		
		// $mail->AddAddress('juanignacio@dusanasegurador.com.ar');
		
		foreach (explode(',', $to) as $addr) {
			if (preg_match('/^[a-zA-Z0-9\._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', trim($addr))) {
				$mail->AddAddress(trim($addr));
				$recipients[] = trim($addr);
			}
		}
		
		foreach ($cc as $addr) {
			if (preg_match('/^[a-zA-Z0-9\._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $addr)) {
				$mail->AddCC($addr);
				$recipients[] = $addr;
			}
		}
		
		$mail->SetFrom($from['email'], $from['name']);
		
		$mail->Subject = $subject;
		foreach ($attachments as $attachment) {
			$mail->AddAttachment($attachment['file'], $attachment['name'], 'base64', $attachment['type']);
		}
		if ($custombody) {
			$mail->AltBody = $custombody; 
			$mail->MsgHTML($custombody);
		}
		else {
			if ($body) {
				$mail->AltBody = 'Estimado asegurado, le agradecemos por elegir DUSAN ASESORES DE SEGUROS. De forma adjunta le enviamos la informacion solicitada. Muchas gracias.'; 
				$mail->MsgHTML('<img src="cid:saludo_dusan" />');
				$mail->AddEmbeddedImage('media/images/mail-body.jpg', 'saludo_dusan', '', 'base64', 'image/jpeg');
			}
			else {
				$mail->AltBody = ' '; 
				$mail->MsgHTML(' ');
			}
		}
		$mail->Send();
		$sql = sprintf('INSERT INTO email_log (email_type_id, object_id, usuario_id, email_log_to, email_log_desc, email_log_timestamp) VALUES (%s, %s, %s, \'%s\', %s, NOW())', $type, $id, $_SESSION['ADM_UserId'], implode(', ', $recipients), (empty($desc)?'NULL':'"'.$desc.'"'));
		mysql_query($sql, $connection) or die(mysql_error());
		return "Email enviado satisfactoriamente.";
	} 
	catch (phpmailerException $e) {
		return $e->errorMessage();
	} 
	catch (Exception $e) {
		return $e->getMessage();
	}
}
?>
