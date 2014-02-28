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
	if ((isset($_POST["box-seguro_id"])) && ($_POST["box-seguro_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE seguro SET seguro_nombre=UPPER(TRIM(%s)), seguro_email_siniestro=TRIM(%s), seguro_email_emision=TRIM(%s), seguro_email_emision_vida=TRIM(%s), seguro_email_patrimoniales_otras=TRIM(%s), seguro_email_endosos=TRIM(%s), seguro_email_rastreador=TRIM(%s), seguro_email_fotos=TRIM(%s), seguro_email_inspeccion=TRIM(%s), seguro_cuit=UPPER(TRIM(%s)), seguro_direccion=UPPER(TRIM(%s)), seguro_localidad=UPPER(TRIM(%s)), seguro_cp=UPPER(TRIM(%s)), seguro_flota=%s WHERE seguro.seguro_id=%s LIMIT 1",
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
						GetSQLValueString($_POST['box-seguro_cp'], "text"),
						GetSQLValueString(isset($_POST['box-seguro_flota']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-seguro_id'], "int"));			
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