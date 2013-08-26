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
	if (isset($_POST["box-poliza_id"]) && $_POST["box-poliza_id"]!="") {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO accidentes_asegurado (poliza_id, accidentes_asegurado_nombre, accidentes_asegurado_documento, accidentes_asegurado_nacimiento, accidentes_asegurado_domicilio, accidentes_asegurado_actividad, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, accidentes_asegurado_beneficiario, accidentes_asegurado_beneficiario_nombre, accidentes_asegurado_beneficiario_documento, accidentes_asegurado_beneficiario_nacimiento, accidentes_asegurado_beneficiario_tomador) VALUES (%s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s)",
						GetSQLValueString($_POST['box-poliza_id'], "int"),
						GetSQLValueString($_POST['box-accidentes_asegurado_nombre'], "text"),
						GetSQLValueString($_POST['box-accidentes_asegurado_documento'], "text"),
						GetSQLValueString($_POST['box-accidentes_asegurado_nacimiento'], "date"),
						GetSQLValueString($_POST['box-accidentes_asegurado_domicilio'], "text"),
						GetSQLValueString($_POST['box-accidentes_asegurado_actividad'], "int"),
						GetSQLValueString($_POST['box-accidentes_asegurado_suma_asegurada'], "double"),
						GetSQLValueString($_POST['box-accidentes_asegurado_gastos_medicos'], "double"),
						
						GetSQLValueString(isset($_POST['box-accidentes_asegurado_beneficiario']) ? 'true' : '', 'defined','1','0'),
						isset($_POST['box-accidentes_asegurado_beneficiario'])?
							'UPPER(TRIM('.GetSQLValueString($_POST['box-accidentes_asegurado_beneficiario_nombre'], "text").'))':'NULL',
						isset($_POST['box-accidentes_asegurado_beneficiario'])?
							'UPPER(TRIM('.GetSQLValueString($_POST['box-accidentes_asegurado_beneficiario_documento'], "text").'))':'NULL',
						isset($_POST['box-accidentes_asegurado_beneficiario'])?
							GetSQLValueString($_POST['box-accidentes_asegurado_beneficiario_nacimiento'], "date"):'NULL',
						GetSQLValueString(isset($_POST['box-accidentes_asegurado_beneficiario_tomador']) ? 'true' : '', 'defined','1','0'));						
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
				break;
			default:
				mysql_die();
				break;
		}							
		
	} else {
		die("Error: Acceso denegado.");
	}
?>