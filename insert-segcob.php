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
	if (isset($_POST["box-seguro_id"]) && $_POST["box-seguro_id"]!="") {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO seguro_cobertura_tipo (seguro_id, seguro_cobertura_tipo_nombre, seguro_cobertura_tipo_limite_rc_id, seguro_cobertura_tipo_gruas, seguro_cobertura_tipo_gruas_km, seguro_cobertura_tipo_gruas_desde, seguro_cobertura_tipo_antiguedad, seguro_cobertura_tipo_todo_riesgo, seguro_cobertura_tipo_franquicia, seguro_cobertura_tipo_ajuste) VALUES (%s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s)",
						GetSQLValueString($_POST['box-seguro_id'], "int"),
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_nombre'], "text"),						
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_limite_rc_id'], "int"),						
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_gruas'], "int"),						
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_gruas_km'], "int"),
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_gruas_desde'], "int"),
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_antiguedad'], "int"),
						GetSQLValueString(isset($_POST['box-seguro_cobertura_tipo_todo_riesgo']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_franquicia'], "int"),
						GetSQLValueString($_POST['box-seguro_cobertura_tipo_ajuste'], "int"));						
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
				break;
			default:
				mysql_die();
				break;
		}							
		
	} else {
		die("Error: Acceso denegado.");
	}
?>