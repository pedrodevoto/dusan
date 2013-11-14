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
	if ((isset($_POST["box-seguro_cobertura_tipo_id"])) && ($_POST["box-seguro_cobertura_tipo_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE seguro_cobertura_tipo SET seguro_cobertura_tipo_nombre=UPPER(TRIM(%s)), seguro_cobertura_tipo_limite_rc=%s, seguro_cobertura_tipo_gruas=%s, seguro_cobertura_tipo_anios_de=%s, seguro_cobertura_tipo_anios_a=%s WHERE seguro_cobertura_tipo_id=%s LIMIT 1",
								GetSQLValueString($_POST['box-seguro_cobertura_tipo_nombre'], "text"),
								GetSQLValueString($_POST['box-seguro_cobertura_tipo_limite_rc'], "int"),
								GetSQLValueString($_POST['box-seguro_cobertura_tipo_gruas'], "int"),
								GetSQLValueString($_POST['box-seguro_cobertura_tipo_anios_de'], "int"),
								GetSQLValueString($_POST['box-seguro_cobertura_tipo_anios_a'], "int"),
								GetSQLValueString($_POST['box-seguro_cobertura_tipo_id'], "int"));
										
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