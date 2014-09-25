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
	if (!empty($_POST["box-automotor_modelo_id"]) && !empty($_POST["box-automotor_version_nombre"])) {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO automotor_version (automotor_modelo_id, automotor_version_nombre) VALUES (%s, UPPER(TRIM(%s)))",
						GetSQLValueString($_POST['box-automotor_modelo_id'], "int"),
						GetSQLValueString($_POST['box-automotor_version_nombre'], "text"));
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				$automotor_version_id = mysql_insert_id($connection);
				foreach ($_POST['box-automotor_anos'] as $automotor_ano) {
					$insertSQL = sprintf("INSERT INTO automotor_version_ano (automotor_version_id, automotor_ano) VALUES (%s, %s)",
						GetSQLValueString($automotor_version_id, "int"),
						GetSQLValueString($automotor_ano, "int"));
					mysql_query($insertSQL, $connection);
				}
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