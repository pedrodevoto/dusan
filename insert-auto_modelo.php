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
	if (!empty($_POST["box-automotor_marca_id"]) && !empty($_POST["box-automotor_modelo_nombre"])) {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO automotor_modelo (automotor_marca_id, automotor_modelo_nombre) VALUES (%s, UPPER(TRIM(%s)))",
						GetSQLValueString($_POST['box-automotor_marca_id'], "int"),
						GetSQLValueString($_POST['box-automotor_modelo_nombre'], "text"));								
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