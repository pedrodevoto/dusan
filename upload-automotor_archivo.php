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
if (isset($_POST['automotor_id'])) {
	$automotor_id = intval($_POST['automotor_id']);
	$types = array('cert_rodamiento', 'general');
	foreach ($types as $type) {
		if(isset($_FILES['box-'.$type.'_archivo']['tmp_name'])){
			if ($_FILES['box-'.$type.'_archivo']['error'] == 0) {
				if ($file = processArchivo($_FILES['box-'.$type.'_archivo'])){
					$sql = sprintf('INSERT INTO automotor_%1$s_archivo (automotor_id, automotor_%1$s_archivo_url, automotor_%1$s_archivo_nombre) VALUES (%2$s, \'%3$s\', \'%4$s\')', $type, $automotor_id, $file['filename'], $file['name']);
					mysql_query($sql, $connection) or die(mysql_error());
				}
			}
		}
	}
}
?>