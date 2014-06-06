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
		$updateSQL = sprintf("UPDATE productor_seguro SET productor_seguro_codigo=UPPER(TRIM(%s)), productor_seguro_organizacion_flag=%s, productor_seguro_organizacion_nombre=UPPER(TRIM(%s)), productor_seguro_organizacion_tipo_persona=%s, productor_seguro_organizacion_matricula=UPPER(TRIM(%s)), productor_seguro_organizacion_cuit=UPPER(TRIM(%s)) WHERE productor_seguro_id=%s LIMIT 1",
								GetSQLValueString($_POST['box-productor_seguro_codigo'], "text"),
								GetSQLValueString(isset($_POST['box-productor_seguro_organizacion_flag']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString($_POST['box-productor_seguro_organizacion_nombre'], "text"),
								GetSQLValueString($_POST['box-productor_seguro_organizacion_tipo_persona'], "int"),
								GetSQLValueString($_POST['box-productor_seguro_organizacion_matricula'], "text"),
								GetSQLValueString($_POST['box-productor_seguro_organizacion_cuit'], "text"),
								GetSQLValueString($_POST['box-productor_seguro_id'], "int"));
								
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
				case 0:
				$deleteSQL = sprintf("DELETE FROM productor_seguro_sucursal WHERE productor_seguro_id = %s",
					GetSQLValueString($_POST['box-productor_seguro_id'], "int"));
				mysql_query($deleteSQL, $connection);
				foreach ($_POST['box-sucursal_id'] as $sucursal_id) {
					$insertSQL = sprintf("INSERT INTO productor_seguro_sucursal (productor_seguro_id, sucursal_id) VALUES (%s, %s)",
						GetSQLValueString($_POST['box-productor_seguro_id'], "int"),
						GetSQLValueString($sucursal_id, "int"));
						error_log($insertSQL);
					mysql_query($insertSQL, $connection) or die(mysql_error());
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