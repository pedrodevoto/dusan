<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	require_once('inc/process-archivo.php');	
?>
<?php
if (isset($_POST['siniestro_id'])) {
	$siniestro_id = intval($_POST['siniestro_id']);
	$types = array('denuncia_policial');
	foreach ($types as $type) {
	    if(isset($_FILES['box-'.$type.'_archivo']['tmp_name'])){
			if ($_FILES['box-'.$type.'_archivo']['error'] == 0) {
				if ($file = processArchivo($_FILES['box-'.$type.'_archivo'])){
					$sql = sprintf('INSERT INTO siniestros_%1$s_archivo (siniestro_id, siniestros_%1$s_archivo_url, siniestros_%1$s_archivo_nombre) VALUES (%2$s, \'%3$s\', \'%4$s\')', $type, $siniestro_id, $file['filename'], $file['name']);
					mysql_query($sql, $connection) or die(mysql_error());
				}
			}
		}
	}
}
?>