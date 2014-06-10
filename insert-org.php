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
		$insertSQL = sprintf("INSERT INTO organizador (organizador_nombre, organizador_iva, organizador_cuit, organizador_matricula, organizador_email, organizador_telefono) VALUES (UPPER(TRIM(%s)), %s, TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s))",
						GetSQLValueString($_POST['box-organizador_nombre'], "text"),
						GetSQLValueString($_POST['box-organizador_iva'], "int"),
						GetSQLValueString($_POST['box-organizador_cuit'], "text"),
						GetSQLValueString($_POST['box-organizador_matricula'], "text"),
						GetSQLValueString($_POST['box-organizador_email'], "text"),
						GetSQLValueString($_POST['box-organizador_telefono'], "text"));								
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo 'El registro ha sido insertado con éxito';
				break;
			case 1062:
				echo 'Error: Registro duplicado (CUIT).';
				break;
			default:
				mysql_die();
				break;
		}		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>