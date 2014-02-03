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
	if ((isset($_POST["box-contacto_id"])) && ($_POST["box-contacto_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE contacto SET contacto_tipo=%s, contacto_domicilio=UPPER(TRIM(%s)), contacto_nro=UPPER(TRIM(%s)), contacto_piso=UPPER(TRIM(%s)), contacto_dpto=UPPER(TRIM(%s)), contacto_localidad=UPPER(TRIM(%s)), contacto_cp=UPPER(TRIM(%s)), contacto_country=UPPER(TRIM(%s)), contacto_lote=UPPER(TRIM(%s)), contacto_telefono1=UPPER(TRIM(%s)), contacto_telefono2=UPPER(TRIM(%s)), contacto_observaciones=UPPER(TRIM(%s)) WHERE contacto_id=%s LIMIT 1",
								GetSQLValueString($_POST['box-contacto_tipo'], "text"),
								GetSQLValueString($_POST['box-contacto_domicilio'], "text"),
								GetSQLValueString($_POST['box-contacto_nro'], "text"),
								GetSQLValueString($_POST['box-contacto_piso'], "text"),
								GetSQLValueString($_POST['box-contacto_dpto'], "text"),						
								GetSQLValueString($_POST['box-contacto_localidad'], "text"),
								GetSQLValueString($_POST['box-contacto_cp'], "text"),
								GetSQLValueString($_POST['box-contacto_country'], "text"),
								GetSQLValueString($_POST['box-contacto_lote'], "text"),
								GetSQLValueString($_POST['box-contacto_telefono1'], "text"),
								GetSQLValueString($_POST['box-contacto_telefono2'], "text"),
								GetSQLValueString($_POST['box-contacto_observaciones'], "text"),
								GetSQLValueString($_POST['box-contacto_id'], "int"));
										
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