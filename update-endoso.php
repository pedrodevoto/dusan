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
	if ((isset($_POST["box-endoso_id"])) && ($_POST["box-endoso_id"] != "")) {	
	
		// Update
		$updateSQL = sprintf("UPDATE endoso SET endoso_fecha_pedido=%s, endoso_tipo_id=%s, endoso_cuerpo=TRIM(%s), endoso_premio=%s, endoso_numero=TRIM(%s), endoso_fecha_compania=%s, endoso_completo=%s WHERE endoso_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-endoso_fecha_pedido'], "date"),						
						GetSQLValueString($_POST['box-endoso_tipo_id'], "int"),						
						GetSQLValueString($_POST['box-endoso_cuerpo'], "text"),
						GetSQLValueString($_POST['box-endoso_premio'], "double"),
						GetSQLValueString($_POST['box-endoso_numero'], "text"),
						GetSQLValueString($_POST['box-endoso_fecha_compania'], "date"),
						GetSQLValueString(isset($_POST['box-endoso_completo']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-endoso_id'], "int"));			
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