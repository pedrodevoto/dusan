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
	if ((isset($_POST["box-productor_seguro_id"])) && ($_POST["box-productor_seguro_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE productor_seguro SET productor_seguro_codigo=UPPER(TRIM(%s)), zona_riesgo_id=%s WHERE productor_seguro_id=%s LIMIT 1",
								GetSQLValueString($_POST['box-productor_seguro_codigo'], "text"),
								GetSQLValueString($_POST['box-zona_riesgo_id'], "int"),
								GetSQLValueString($_POST['box-productor_seguro_id'], "int"));
										
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
			case 0:									
				echo "El registro ha sido actualizado.";							
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