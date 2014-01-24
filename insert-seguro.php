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
		$insertSQL = sprintf("INSERT INTO seguro (seguro_nombre, seguro_email_siniestro, seguro_email_emision, seguro_email_emision_vida, seguro_email_patrimoniales_otras, seguro_email_endosos, seguro_email_rastreador, seguro_email_fotos, seguro_email_inspeccion, seguro_cuit, seguro_direccion, seguro_localidad, seguro_cp) VALUES (UPPER(TRIM(%s)), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)))",
						GetSQLValueString($_POST['box-seguro_nombre'], "text"),												
						GetSQLValueString($_POST['box-seguro_email_siniestro'], "text"),						
						GetSQLValueString($_POST['box-seguro_email_emision'], "text"),
						GetSQLValueString($_POST['box-seguro_email_emision_vida'], "text"),
						GetSQLValueString($_POST['box-seguro_email_patrimoniales_otras'], "text"),
						GetSQLValueString($_POST['box-seguro_email_endosos'], "text"),
						GetSQLValueString($_POST['box-seguro_email_rastreador'], "text"),
						GetSQLValueString($_POST['box-seguro_email_fotos'], "text"),
						GetSQLValueString($_POST['box-seguro_email_inspeccion'], "text"),
						GetSQLValueString($_POST['box-seguro_cuit'], "text"),
						GetSQLValueString($_POST['box-seguro_direccion'], "text"),
						GetSQLValueString($_POST['box-seguro_localidad'], "text"),
						GetSQLValueString($_POST['box-seguro_cp'], "text"));								
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