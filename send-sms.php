<?php
	$MM_authorizedUsers = "master";
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
if (!empty($_POST['mensaje'])) {
	$sql = 'SELECT contacto_telefono2 from contacto join cliente using (cliente_id) where contacto_default = 1 and cliente_sms_newsletter=1 and contacto_telefono2 is not null';
	$res = mysql_query($sql) or die(mysql_error());
	$mensajes = array();
	while ($row=mysql_fetch_array($sql)) {
		$mensajes[] = sprintf("11%s\t11%s\t%s", $row[0], $row[0], $_POST['mensaje']);
	}
	
	$fields = array(
		'USUARIO'			=> 'DUSANASEGURADOR',
		'CLAVE'				=> 'DUSANASEGURADOR979',
		'SEPARADORCAMPOS'	=> 'tab',
		'BLOQUE'			=> urlencode(implode("\n", $mensajes)),
	);
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	// echo $fields_string;
	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,"http://servicio.smsmasivos.com.ar/enviar_sms_bloque.asp");
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

	$result = curl_exec($ch);
	echo sprintf('Respuesta del servidor: %s', $result);
	curl_close($ch);
}
?>