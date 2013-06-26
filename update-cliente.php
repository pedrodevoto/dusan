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
	if ((isset($_POST["box-cliente_id"])) && ($_POST["box-cliente_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE cliente SET cliente_nombre=TRIM(%s), cliente_nacimiento=%s, cliente_sexo=%s, cliente_tipo_doc=%s, cliente_nro_doc=TRIM(%s), cliente_nacionalidad=TRIM(%s), cliente_cf=%s, cliente_registro=TRIM(%s), cliente_reg_vencimiento=%s, cliente_reg_tipo=%s, cliente_cuit=TRIM(%s), cliente_telefono1=TRIM(%s), cliente_telefono2=TRIM(%s), cliente_email=TRIM(%s) WHERE cliente.cliente_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-cliente_nombre'], "text"),
						GetSQLValueString($_POST['box-cliente_nacimiento'], "date"),
						GetSQLValueString($_POST['box-cliente_sexo'], "text"),
						GetSQLValueString($_POST['box-cliente_tipo_doc'], "text"),
						GetSQLValueString($_POST['box-cliente_nro_doc'], "text"),
						GetSQLValueString($_POST['box-cliente_nacionalidad'], "text"),
						GetSQLValueString($_POST['box-cliente_cf'], "text"),						
						GetSQLValueString($_POST['box-cliente_registro'], "text"),
						GetSQLValueString($_POST['box-cliente_reg_vencimiento'], "date"),
						GetSQLValueString($_POST['box-cliente_reg_tipo'], "text"),
						GetSQLValueString($_POST['box-cliente_cuit'], "text"),
						GetSQLValueString($_POST['box-cliente_telefono1'], "text"),
						GetSQLValueString($_POST['box-cliente_telefono2'], "text"),
						GetSQLValueString($_POST['box-cliente_email'], "text"),
						GetSQLValueString($_POST['box-cliente_id'], "int"));			
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