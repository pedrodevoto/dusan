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
	if (!empty($_POST["box-automotor_modelo_id"])) {
		
		// Update
		$updateSQL = sprintf("UPDATE automotor_modelo SET automotor_modelo_nombre=UPPER(TRIM(%s)) WHERE automotor_modelo_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-automotor_modelo_nombre'], "text"),
						GetSQLValueString($_POST['box-automotor_modelo_id'], "int"));			
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