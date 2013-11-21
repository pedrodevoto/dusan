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
	if ((isset($_POST["box-productor_seguro_id"])) && ($_POST["box-productor_seguro_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE productor_seguro SET productor_seguro_codigo=UPPER(TRIM(%s)), zona_riesgo_id=%s WHERE productor_seguro_id=%s LIMIT 1",
								GetSQLValueString($_POST['box-productor_seguro_codigo'], "text"),
								GetSQLValueString($_POST['box-zona_riesgo_id'], "int"),
								GetSQLValueString($_POST['box-productor_seguro_id'], "int"));
										
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
				case 0:
				$deleteSQL = sprintf("DELETE FROM productor_seguro_cobertura_tipo WHERE productor_seguro_id = %s",
					GetSQLValueString($_POST['box-productor_seguro_id'], "int"));
				mysql_query($deleteSQL, $connection);
				foreach ($_POST['box-seguro_cobertura_tipo_id'] as $cobertura_id) {
					$insertSQL = sprintf("INSERT INTO productor_seguro_cobertura_tipo (productor_seguro_id, seguro_cobertura_tipo_id) VALUES (%s, %s)",
						GetSQLValueString($_POST['box-productor_seguro_id'], "int"),
						GetSQLValueString($cobertura_id, "int"));
						error_log($insertSQL);
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