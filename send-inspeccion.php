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
	
	$sql = 'SELECT pedido_instalacion_direccion, pedido_instalacion_horario, pedido_instalacion_telefono, pedido_instalacion_observaciones FROM automotor WHERE poliza_id = '.$poliza_id;
	$res = mysql_query($sql, $connection);
	list($pedido_instalacion_direccion, $pedido_instalacion_horario, $pedido_instalacion_telefono, $pedido_instalacion_observaciones) = mysql_fetch_array($res);
	
	$sql = sprintf('SELECT seguro_email_inspeccion, CONCAT(IF(automotor_carroceria_id=17, '101', ''), patente_0, patente_1) as patente from poliza join (productor_seguro, seguro, automotor) on productor_seguro.productor_seguro_id = poliza.productor_seguro_id and seguro.seguro_id = productor_seguro.seguro_id and poliza.poliza_id = automotor.poliza_id where poliza.poliza_id = %s', $poliza_id);
	$email = mysql_query($sql, $connection) or die(mysql_error());
	list($email, $patente) = mysql_fetch_array($email);
	
	$cc = explode(',', urldecode($_GET['email']));
	$to = $email;
	$subject = $_GET['mail-subject'];
	$body = 'Se solicita pedido de inspección al vehiculo con dominio '.$patente.'. Muchas gracias';
	$body.= "\n<br/>";
	$body.= 'Dirección: '.$pedido_instalacion_direccion."\n<br/>";
	$body.= 'Horario: '.$pedido_instalacion_horario."\n<br/>";
	$body.= 'Teléfono: '.$pedido_instalacion_telefono."\n<br/>";
	$body.= 'Observaciones: '.$pedido_instalacion_observaciones."\n<br/>";

	echo send_mail(9, $poliza_id, $to, $subject, FALSE, NULL, $cc, $body);
}	
?>