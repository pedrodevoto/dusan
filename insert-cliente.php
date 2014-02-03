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
		$insertSQL = sprintf("INSERT INTO cliente (cliente_tipo_persona, cliente_nombre, cliente_apellido, cliente_razon_social, cliente_tipo_sociedad_id, cliente_nacimiento, cliente_sexo, cliente_tipo_doc, cliente_nro_doc, cliente_nacionalidad_id, cliente_cf_id, cliente_registro, cliente_reg_vencimiento, cliente_cuit_0, cliente_cuit_1, cliente_cuit_2, cliente_email, cliente_email_alt) VALUES (%s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, TRIM(%s), %s, %s, TRIM(%s), %s, TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s))",
						GetSQLValueString($_POST['box-cliente_tipo_persona'], "int"),
						GetSQLValueString($_POST['box-cliente_nombre'], "text"),
						GetSQLValueString($_POST['box-cliente_apellido'], "text"),
						GetSQLValueString($_POST['box-cliente_razon_social'], "text"),
						GetSQLValueString($_POST['box-cliente_tipo_sociedad_id'], "int"),
						GetSQLValueString($_POST['box-cliente_nacimiento'], "date"),
						GetSQLValueString($_POST['box-cliente_sexo'], "text"),
						GetSQLValueString($_POST['box-cliente_tipo_doc'], "text"),
						GetSQLValueString($_POST['box-cliente_nro_doc'], "text"),
						GetSQLValueString($_POST['box-cliente_nacionalidad_id'], "int"),
						GetSQLValueString($_POST['box-cliente_cf_id'], "int"),						
						GetSQLValueString($_POST['box-cliente_registro'], "text"),
						GetSQLValueString($_POST['box-cliente_reg_vencimiento'], "date"),
						GetSQLValueString($_POST['box-cliente_cuit_0'], "text"),
						GetSQLValueString($_POST['box-cliente_cuit_1'], "text"),
						GetSQLValueString($_POST['box-cliente_cuit_2'], "text"),
						GetSQLValueString($_POST['box-cliente_email'], "text"),
						GetSQLValueString($_POST['box-cliente_email_alt'], "text"));								
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				$cliente_id = mysql_insert_id();
				foreach ($_POST['box-cliente_reg_tipo_id'] as $cliente_reg_tipo_id) {
					$sql = 'INSERT INTO cliente_cliente_reg_tipo (cliente_id, cliente_reg_tipo_id) VALUES ('.$cliente_id.', '.intval($cliente_reg_tipo_id).')';
					mysql_query($sql, $connection) or die(mysql_error());
				}
				echo 'El registro ha sido insertado con éxito | <a href="javascript:openBoxContacto('.$cliente_id.')" class="lnkBox">ALTA DE CONTACTOS</a>';
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