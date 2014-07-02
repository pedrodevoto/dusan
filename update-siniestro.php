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
	if (!empty($_POST["box-siniestro_id"])) {
		
		$siniestro_id = mysql_real_escape_string($_POST['box-siniestro_id']);
		
		$sql = sprintf('DELETE FROM siniestros_data WHERE siniestro_id = "%s"', $siniestro_id);
		mysql_query($sql, $connection) or die(mysql_error());
				
		if ($siniestro_id) {
			$values = array();
			foreach ($_POST as $k=>$v) {
				if (!preg_match('/^box-/', $k) or $k=='box-poliza_id' or $k=='box-siniestro_id') {
					continue;
				}
				$key = mysql_real_escape_string(substr($k, 4));
				$val = mysql_real_escape_string(trim($v));
				if ($val=='') continue;
			
				$values[] = sprintf('(%s, "%s", UPPER(TRIM("%s")))', $siniestro_id, $key, $val);
			}
		
			$sql = sprintf('INSERT INTO siniestros_data (`siniestro_id`, `key`, `value`) VALUES %s', implode(', ', $values));
			mysql_query($sql, $connection) or die(mysql_error());
			
			echo "El registro ha sido actualizado.";
		}
	} else {
		die("Error: Acceso denegado.");
	}
?>