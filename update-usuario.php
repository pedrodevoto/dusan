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
	if ((isset($_POST["box-usuario_id"])) && ($_POST["box-usuario_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE usuario SET usuario_acceso=%s, usuario_usuario=LCASE(TRIM(%s)), usuario_clave=IF(ISNULL(%s),usuario_clave,MD5(TRIM(%s))), usuario_email=TRIM(%s), usuario_nombre=TRIM(%s), usuario_cambioclave=IF(ISNULL(%s),usuario_cambioclave,NOW()), usuario_reseteado=IF(ISNULL(%s),usuario_reseteado,1) WHERE usuario.usuario_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-usuario_acceso'], "text"),												
						GetSQLValueString($_POST['box-usuario_usuario'], "text"),						
						GetSQLValueString($_POST['box-usuario_clave'], "text"),													
						GetSQLValueString($_POST['box-usuario_clave'], "text"),																								
						GetSQLValueString($_POST['box-usuario_email'], "text"),	
						GetSQLValueString($_POST['box-usuario_nombre'], "text"),																																																				
						GetSQLValueString($_POST['box-usuario_clave'], "text"),																								
						GetSQLValueString($_POST['box-usuario_clave'], "text"),
						GetSQLValueString($_POST['box-usuario_id'], "int"));			
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				if ($_POST['box-usuario_acceso'] == "administrativo") {
					$deleteSQL = sprintf("DELETE FROM usuario_sucursal WHERE usuario_id = %s",
						GetSQLValueString($_POST['box-usuario_id'], "int"));
					mysql_query($deleteSQL, $connection);
					foreach ($_POST['box-usuario_sucursal'] as $sucursal_id) {
						$updateSQL = sprintf("INSERT INTO usuario_sucursal (usuario_id, sucursal_id) VALUES (%s, %s)",
							GetSQLValueString($_POST['box-usuario_id'], "int"),
							GetSQLValueString($sucursal_id, "int"));
						mysql_query($updateSQL, $connection);
					}
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