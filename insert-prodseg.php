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
	if (isset($_POST["box-productor_id"]) && $_POST["box-productor_id"]!="") {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO productor_seguro (productor_id, seguro_id, sucursal_id, productor_seguro_codigo, zona_riesgo_id) VALUES (%s, %s, %s, UPPER(TRIM(%s)), %s)",
						GetSQLValueString($_POST['box-productor_id'], "int"),
						GetSQLValueString($_POST['box-seguro_id'], "int"),						
						GetSQLValueString($_POST['box-sucursal_id'], "int"),						
						GetSQLValueString($_POST['box-productor_seguro_codigo'], "text"),
						GetSQLValueString($_POST['box-zona_riesgo_id'], "int"));						
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
				break;
			case 1062:
				echo "Error: El Seguro ya se encuentra asignado.";
				break;
			default:
				mysql_die();
				break;
		}							
		
	} else {
		die("Error: Acceso denegado.");
	}
?>