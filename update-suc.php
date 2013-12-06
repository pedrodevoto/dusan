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
	if ((isset($_POST["box-sucursal_id"])) && ($_POST["box-sucursal_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE sucursal SET sucursal_nombre=UPPER(TRIM(%s)), sucursal_direccion=UPPER(TRIM(%s)), sucursal_telefono=TRIM(%s), sucursal_email=TRIM(%s), sucursal_num_factura=%s, sucursal_pfc=%s, sucursal_pfc_default=%s WHERE sucursal.sucursal_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-sucursal_nombre'], "text"),
						GetSQLValueString($_POST['box-sucursal_direccion'], "text"),
						GetSQLValueString($_POST['box-sucursal_telefono'], "text"),
						GetSQLValueString($_POST['box-sucursal_email'], "text"),
						GetSQLValueString($_POST['box-sucursal_num_factura'], "int"),
						GetSQLValueString(isset($_POST['box-sucursal_pfc']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-sucursal_pfc_default'], "int"),
						GetSQLValueString($_POST['box-sucursal_id'], "int"));			
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