<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
	require_once('inc/mail_functions.php');
?>
<?php
	if ((isset($_GET["id"])) && ($_GET["id"] != "")) {		
		$poliza_id = intval(mysql_real_escape_string($_GET['id']));
		$sql = sprintf('SELECT automotor_foto.* FROM automotor_foto JOIN automotor ON automotor.automotor_id = automotor_foto.automotor_id WHERE automotor.poliza_id = %s', $poliza_id);
		$res = mysql_query($sql) or die(mysql_error());
		$sql = sprintf('select seguro_email_fotos from poliza join (productor_seguro, seguro) on productor_seguro.productor_seguro_id = poliza.productor_seguro_id and seguro.seguro_id = productor_seguro.seguro_id where poliza_id = %s', $poliza_id);
		$email = mysql_query($sql);
		list($email) = mysql_fetch_array($email);
		$count = mysql_num_rows($res);
		
		if ($count) {
			$cc = explode(',', urldecode($_GET['email']));
			$to = $email;
			$subject = $_GET['mail-subject'];
			$attachments = array();
			$i = 1;
			while ($foto = mysql_fetch_assoc($res)) {
				$extension = strtolower(strrchr($foto['automotor_foto_url'], '.'));
				$mime = mime_content_type($foto['automotor_foto_url']);
				$attachments[] = array('file'=>$foto['automotor_foto_url'], 'name'=>'FOTO '.$i.$extension, 'type'=>$mime);
				$i++;
			}
			echo send_mail(7, $poliza_id, $to, $subject, FALSE, $attachments, $cc);
		}
		else {
			echo 'La póliza no contiene fotos.';
		}
		
	} else {
		die("Error.");
	}
?>