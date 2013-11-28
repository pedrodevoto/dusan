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
	if ((isset($_POST["box-insert"])) && ($_POST["box-insert"] == "1")) {	
		
		// Insert
		$insertSQL = sprintf("INSERT INTO sucursal (sucursal_nombre, sucursal_direccion, sucursal_telefono, sucursal_email, sucursal_num_factura) VALUES (UPPER(TRIM(%s)), UPPER(TRIM(%s)), TRIM(%s), TRIM(%s), %s)",
						GetSQLValueString($_POST['box-sucursal_nombre'], "text"),
						GetSQLValueString($_POST['box-sucursal_direccion'], "text"),
						GetSQLValueString($_POST['box-sucursal_telefono'], "text"),
						GetSQLValueString($_POST['box-sucursal_email'], "text"),
						GetSQLValueString($_POST['box-sucursal_num_factura'], "int"));								
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