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
if (isset($_POST['poliza_id'])) {
	$poliza_id = intval($_POST['poliza_id']);
	$types = array('micrograbado', 'gnc', 'cedula_verde', 'cert_rodamiento');
	foreach ($types as $type) {
	    if(isset($_FILES['box-'.$type.'_foto']['tmp_name'])){
			if ($_FILES['box-'.$type.'_foto']['error'] == 0) {
				if ($photo = processFoto($_FILES['box-'.$type.'_foto'])){
					$sql = sprintf('INSERT INTO automotor_%1$s_foto (poliza_id, automotor_%1$s_foto_url, automotor_%1$s_foto_thumb_url, automotor_%1$s_foto_width, automotor_%1$s_foto_height) VALUES (%2$s, \'%3$s\', \'%4$s\', %5$s, %6$s)', $type, $poliza_id, $photo['filename'], $photo['thumb_filename'], $photo['width'], $photo['height']);
					mysql_query($sql, $connection) or die(mysql_error());
				}
			}
		}
	}
}
?>