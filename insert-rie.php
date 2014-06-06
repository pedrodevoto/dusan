<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	if ((isset($_POST["box-insert"])) && ($_POST["box-insert"] == "1") && !empty($_POST['box-seguro_id'])) {	
		
		$sql = sprintf("SELECT COALESCE(MAX(seguro_zona_riesgo_default), 0) FROM seguro_zona_riesgo WHERE seguro_id = %s", GetSQLValueString($_POST['box-seguro_id'], "int"));
		$res = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($res);
		$default = $row[0];
		
		// Insert
		$insertSQL = sprintf("INSERT INTO seguro_zona_riesgo (seguro_id, seguro_zona_riesgo_nombre, seguro_zona_riesgo_default) VALUES (%s, UPPER(TRIM(%s)), %s)",
						GetSQLValueString($_POST['box-seguro_id'], "int"),
						GetSQLValueString($_POST['box-zona_riesgo_nombre'], "text"),
						$default==1?0:1);
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
				break;
			case 1062:
				echo "Error: Registro duplicado.";
				break;
			default:
				mysql_die();
				break;
		}		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>