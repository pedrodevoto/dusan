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
	if ((isset($_POST["box-insert"])) && ($_POST["box-insert"] == "1")) {	
		
		// Insert
		$insertSQL = sprintf("INSERT INTO usuario (usuario_acceso, usuario_usuario, usuario_clave, usuario_email, usuario_nombre) VALUES (%s, LCASE(TRIM(%s)), MD5(TRIM(%s)), TRIM(%s), TRIM(%s))",
						GetSQLValueString($_POST['box-usuario_acceso'], "text"),												
						GetSQLValueString($_POST['box-usuario_usuario'], "text"),						
						GetSQLValueString($_POST['box-usuario_clave'], "text"),																		
						GetSQLValueString($_POST['box-usuario_email'], "text"),																		
						GetSQLValueString($_POST['box-usuario_nombre'], "text"));								
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
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