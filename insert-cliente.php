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
	if ((isset($_POST["box-insert"])) && ($_POST["box-insert"] == "1")) {	
		
		// Insert
		$insertSQL = sprintf("INSERT INTO cliente (cliente_nombre, cliente_nacimiento, cliente_sexo, cliente_tipo_doc, cliente_nro_doc, cliente_nacionalidad, cliente_cf, cliente_registro, cliente_reg_vencimiento, cliente_reg_tipo, cliente_cuit, cliente_telefono1, cliente_telefono2, cliente_email) VALUES (TRIM(%s), %s, %s, %s, TRIM(%s), TRIM(%s), %s, TRIM(%s), %s, %s, TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s))",
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
						GetSQLValueString($_POST['box-cliente_email'], "text"));								
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo 'El registro ha sido insertado con éxito | <a href="javascript:openBoxContacto('.mysql_insert_id().')" class="lnkBox">ALTA DE CONTACTOS</a>';
				break;
			case 1062:
				echo 'Error: Registro duplicado.';
				break;
			default:
				mysql_die();
				break;
		}		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>