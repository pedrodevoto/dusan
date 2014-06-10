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
	if ((isset($_POST["box-organizador_id"])) && ($_POST["box-organizador_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE organizador SET organizador_nombre=UPPER(TRIM(%s)), organizador_iva=%s, organizador_cuit=TRIM(%s), organizador_matricula=TRIM(%s), organizador_email=TRIM(%s), organizador_telefono=TRIM(%s) WHERE organizador_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-organizador_nombre'], "text"),
						GetSQLValueString($_POST['box-organizador_iva'], "text"),
						GetSQLValueString($_POST['box-organizador_cuit'], "text"),
						GetSQLValueString($_POST['box-organizador_matricula'], "text"),
						GetSQLValueString($_POST['box-organizador_email'], "text"),
						GetSQLValueString($_POST['box-organizador_telefono'], "text"),
						GetSQLValueString($_POST['box-organizador_id'], "int"));			
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
			case 0:									
				echo "El registro ha sido actualizado.";							
				break;								
			case 1062:
				echo "Error: Registro duplicado (CUIT).";
				break;
			default:
				mysql_die();
				break;
		}
		
	} else {
		die("Error: Acceso denegado.");
	}
?>