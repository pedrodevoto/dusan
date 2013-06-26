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
	if ((isset($_POST["box-insert"])) && ($_POST["box-insert"] == "1")) {	
		
		// Insert
		$insertSQL = sprintf("INSERT INTO seguro (seguro_nombre, seguro_email_siniestro, seguro_email_emision) VALUES (TRIM(%s), TRIM(%s), TRIM(%s))",
						GetSQLValueString($_POST['box-seguro_nombre'], "text"),												
						GetSQLValueString($_POST['box-seguro_email_siniestro'], "text"),						
						GetSQLValueString($_POST['box-seguro_email_emision'], "text"));								
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