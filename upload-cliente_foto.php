<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
	require_once('inc/process-foto.php');
?>
<?php
if (isset($_POST['cliente_id'])) {
	$types = array('cliente');
	foreach ($types as $type) {
	    if(isset($_FILES['box-'.$type.'_foto']['tmp_name'])){
			if ($_FILES['box-'.$type.'_foto']['error'] == 0) {
				if ($photo = processFoto($_FILES['box-'.$type.'_foto'])){
					$sql = sprintf('INSERT INTO %1$s_foto (cliente_id, %1$s_foto_url, %1$s_foto_thumb_url, %1$s_foto_width, %1$s_foto_height) VALUES (%2$s, \'%3$s\', \'%4$s\', %5$s, %6$s)', $type, GetSQLValueString($_POST['cliente_id'], "int"), $photo['filename'], $photo['thumb_filename'], $photo['width'], $photo['height']);
					mysql_query($sql, $connection) or die(mysql_error());
				}
			}
		}
	}
}
?>