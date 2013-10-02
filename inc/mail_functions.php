<?php
require_once 'Classes/PHPMailer/class.phpmailer.php';
require_once('Connections/connection.php');

function send_mail($type, $id, $to, $subject, $body, $attachments, $cc, $from=array('name'=>'Default From', 'email'=>'default@email.com')) {
	global $connection;
	$mail = new PHPMailer(true); 
	$recipients = array();
	$recipients[] = $to;
	
	try {
		$mail->IsSMTP();
		$mail->SMTPAuth = true; 
		$mail->SMTPSecure = "ssl"; 
		$mail->Host = "smtp.googlemail.com";
		$mail->Port = 465;
		$mail->Username = "";
		$mail->Password = '';
		
		$mail->SMTPDebug = 1;
		
		$mail->AddAddress($to);
		// $mail->AddAddress('pedro.devoto@gmail.com');
		
		foreach ($cc as $addr) {
			if (preg_match('/^[a-zA-Z0-9\._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $addr)) {
				$mail->AddCC($addr);
				$recipients[] = $addr;
			}
		}
		
		$mail->SetFrom($from['email'], $from['name']);
		// $mail->SetFrom('pedrodevoto@gmail.com', 'Pedro Devoto');
		
		$mail->Subject = $subject;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; 
		$mail->MsgHTML($body);
		foreach ($attachments as $attachment) {
			$mail->AddAttachment($attachment['file'], $attachment['name'], 'base64', $attachment['type']);
		}
		
		// $mail->AddAttachment('images/phpmailer.gif');      // default image
		// $mail->Send();
		$sql = sprintf('INSERT INTO email_log (email_type_id, object_id, usuario_id, email_log_to, email_log_timestamp) VALUES (%s, %s, %s, \'%s\', NOW())', $type, $id, $_SESSION['ADM_UserId'], implode(', ', $recipients));
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