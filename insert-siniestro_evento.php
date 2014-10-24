<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
if (!empty($_POST['siniestro_id']) && !empty($_POST['fecha']) && !empty($_POST['comentario'])) {
	$sql = sprintf('INSERT INTO siniestro_evento (siniestro_id, usuario_id, siniestro_evento_fecha, siniestro_evento_comentario) VALUES (%s, %s, %s, UPPER(TRIM(%s)))', 
		GetSQLValueString($_POST['siniestro_id'], 'int'),
		$_SESSION['ADM_UserId'],
		GetSQLValueString($_POST['fecha'], 'date'),
		GetSQLValueString($_POST['comentario'], 'text'));
	mysql_query($sql) or die(mysql_error());
}
?>