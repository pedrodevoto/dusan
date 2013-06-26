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
	if ((isset($_POST["box-seguro_id"])) && ($_POST["box-seguro_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE seguro SET seguro_nombre=TRIM(%s), seguro_email_siniestro=TRIM(%s), seguro_email_emision=TRIM(%s) WHERE seguro.seguro_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-seguro_nombre'], "text"),																																																				
						GetSQLValueString($_POST['box-seguro_email_siniestro'], "text"),																								
						GetSQLValueString($_POST['box-seguro_email_emision'], "text"),
						GetSQLValueString($_POST['box-seguro_id'], "int"));			
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