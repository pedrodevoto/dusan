<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
	require_once('inc/process-foto.php');
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
						GetSQLValueString(preg_replace('/[^0-9]/', '', $_POST['box-cliente_nro_doc']), "int"),
						GetSQLValueString($_POST['box-cliente_nacionalidad_id'], "int"),
						GetSQLValueString($_POST['box-cliente_cf_id'], "int"),						
						GetSQLValueString(preg_replace('/[^0-9]/', '', $_POST['box-cliente_registro']), "int"),
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
				foreach ($_POST['box-sucursal_id'] as $sucursal_id) {
					$insertSQL = sprintf("INSERT INTO cliente_sucursal (cliente_id, sucursal_id) VALUES (%s, %s)",
						GetSQLValueString($cliente_id, "int"),
						GetSQLValueString($sucursal_id, "int"));
					mysql_query($insertSQL, $connection);
				}
				
				// Fotos
				$types = array('cliente');
				foreach ($types as $type) {
				    if(isset($_FILES['box-'.$type.'_foto']['tmp_name'])){
						for ($i=0; $i < count($_FILES['box-'.$type.'_foto']['tmp_name']);$i++) {
							if ($_FILES['box-'.$type.'_foto']['error'][$i] == 0) {
								if ($photo = processFoto($_FILES['box-'.$type.'_foto'], $i)){
									$sql = sprintf('INSERT INTO %1$s_foto (cliente_id, %1$s_foto_url, %1$s_foto_thumb_url, %1$s_foto_width, %1$s_foto_height) VALUES (%2$s, \'%3$s\', \'%4$s\', %5$s, %6$s)', $type, $cliente_id, $photo['filename'], $photo['thumb_filename'], $photo['width'], $photo['height']);
									mysql_query($sql, $connection) or die(mysql_error());
								}
							}
						}
					}
				}
				
				echo $cliente_id;
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