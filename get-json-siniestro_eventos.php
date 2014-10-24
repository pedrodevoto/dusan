<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
$output = array();
if (!empty($_GET['siniestro_id'])) {
	$sql = sprintf("SELECT DATE_FORMAT(siniestro_evento_fecha, '%%d/%%m/%%Y'), siniestro_evento_comentario, usuario_usuario FROM siniestro_evento JOIN usuario USING (usuario_id) WHERE siniestro_id = %s ORDER BY siniestro_evento_fecha DESC, siniestro_evento_id DESC",
							GetSQLValueString($_GET['siniestro_id'], "int"));
	// Recordset: Main
	$res = mysql_query($sql, $connection) or die(mysql_die());
	while ($row = mysql_fetch_array($res)) {
		$output[] = array('fecha'=>$row[0], 'usuario'=>$row[2], 'comentario'=>$row[1]);
	}
}
echo json_encode($output);

?>