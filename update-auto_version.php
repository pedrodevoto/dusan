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
	if (!empty($_POST["box-automotor_version_id"])) {
		
		// Update
		$updateSQL = sprintf("UPDATE automotor_version SET automotor_version_nombre=UPPER(TRIM(%s)) WHERE automotor_version_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-automotor_version_nombre'], "text"),
						GetSQLValueString($_POST['box-automotor_version_id'], "int"));
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
			case 0:								
				$deleteSQL = sprintf("DELETE FROM automotor_version_ano WHERE automotor_version_id = %s",
					GetSQLValueString($_POST['box-automotor_version_id'], "int"));
				mysql_query($deleteSQL, $connection);
				foreach ($_POST['box-automotor_anos'] as $automotor_ano) {
					$insertSQL = sprintf("INSERT INTO automotor_version_ano (automotor_version_id, automotor_ano) VALUES (%s, %s)",
						GetSQLValueString($_POST['box-automotor_version_id'], "int"),
						GetSQLValueString($automotor_ano, "int"));
					mysql_query($insertSQL, $connection);
				}
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