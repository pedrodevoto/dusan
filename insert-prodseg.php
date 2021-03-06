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
		$insertSQL = sprintf("INSERT INTO productor_seguro (productor_id, seguro_id, productor_seguro_codigo, organizador_id) VALUES (%s, %s, UPPER(TRIM(%s)), %s)",
						GetSQLValueString($_POST['box-productor_id'], "int"),
						GetSQLValueString($_POST['box-seguro_id'], "int"),						
						GetSQLValueString($_POST['box-productor_seguro_codigo'], "text"),
						GetSQLValueString($_POST['box-organizador_id'], "int"));						
		$Result1 = mysql_query($insertSQL, $connection);

		switch (mysql_errno()) {
			case 0:
				$productor_seguro_id = mysql_insert_id($connection);
				foreach ($_POST['box-sucursal_id'] as $sucursal_id) {
					$insertSQL = sprintf("INSERT INTO productor_seguro_sucursal (productor_seguro_id, sucursal_id) VALUES (%s, %s)",
						GetSQLValueString($productor_seguro_id, "int"),
						GetSQLValueString($sucursal_id, "int"));
					mysql_query($insertSQL, $connection);
				}
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