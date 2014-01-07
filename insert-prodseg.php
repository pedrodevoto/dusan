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
	if (isset($_POST["box-productor_id"]) && $_POST["box-productor_id"]!="") {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO productor_seguro (productor_id, seguro_id, sucursal_id, productor_seguro_codigo, zona_riesgo_id, productor_seguro_organizacion_flag, productor_seguro_organizacion_nombre, productor_seguro_organizacion_tipo_persona, productor_seguro_organizacion_matricula, productor_seguro_organizacion_cuit) VALUES (%s, %s, %s, UPPER(TRIM(%s)), %s, %s, UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)))",
						GetSQLValueString($_POST['box-productor_id'], "int"),
						GetSQLValueString($_POST['box-seguro_id'], "int"),						
						GetSQLValueString($_POST['box-sucursal_id'], "int"),						
						GetSQLValueString($_POST['box-productor_seguro_codigo'], "text"),
						GetSQLValueString($_POST['box-zona_riesgo_id'], "int"),
						GetSQLValueString(isset($_POST['box-productor_seguro_organizacion_flag']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-productor_seguro_organizacion_nombre'], "text"),
						GetSQLValueString($_POST['box-productor_seguro_organizacion_tipo_persona'], "int"),
						GetSQLValueString($_POST['box-productor_seguro_organizacion_matricula'], "text"),
						GetSQLValueString($_POST['box-productor_seguro_organizacion_cuit'], "text"));						
		$Result1 = mysql_query($insertSQL, $connection);

		switch (mysql_errno()) {
			case 0:
				$productor_seguro_id = mysql_insert_id($connection);
				foreach ($_POST['box-seguro_cobertura_tipo_id'] as $cobertura_id) {
					$insertSQL = sprintf("INSERT INTO productor_seguro_cobertura_tipo (productor_seguro_id, seguro_cobertura_tipo_id) VALUES (%s, %s)",
						GetSQLValueString($productor_seguro_id, "int"),
						GetSQLValueString($cobertura_id, "int"));
						error_log($insertSQL);
					mysql_query($insertSQL, $connection);
				}
				echo "El registro ha sido insertado con éxito.";
				break;
			case 1062:
				echo "Error: El Seguro ya se encuentra asignado.";
				break;
			default:
				mysql_die();
				break;
		}							
		
	} else {
		die("Error: Acceso denegado.");
	}
?>