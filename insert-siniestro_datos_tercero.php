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
		$sql = sprintf('INSERT INTO siniestros_datos_terceros (siniestro_id) VALUES ("%s")', mysql_real_escape_string($_POST['box-siniestro_id']));
		mysql_query($sql, $connection) or die(mysql_error());
		
		$siniestros_datos_terceros_id = mysql_insert_id();
		
		if ($siniestros_datos_terceros_id) {
			$values = array();
			foreach ($_POST as $k=>$v) {
				if (!preg_match('/^box-/', $k) or $k=='box-siniestro_id') {
					continue;
				}
				$key = mysql_real_escape_string(substr($k, 4));
				$val = mysql_real_escape_string(trim($v));
				if ($val=='') continue;
			
				$values[] = sprintf('(%s, "%s", UPPER(TRIM("%s")))', $siniestros_datos_terceros_id, $key, $val);
			}
		
			$sql = sprintf('INSERT INTO siniestros_datos_terceros_data (`siniestros_datos_terceros_id`, `key`, `value`) VALUES %s', implode(', ', $values));
			mysql_query($sql, $connection) or die(mysql_error());
			
			switch (mysql_errno()) {
				case 0:
					echo "El registro ha sido insertado con éxito.";
					break;
				default:
					mysql_die();
					break;
			}
		}
	} else {
		die("Error: Acceso denegado.");
	}
?>