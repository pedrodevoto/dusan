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
	if ((isset($_POST["box-accidentes_asegurado_id"])) && ($_POST["box-accidentes_asegurado_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE accidentes_asegurado SET accidentes_asegurado_nombre=UPPER(TRIM(%s)), accidentes_asegurado_documento=UPPER(TRIM(%s)), accidentes_asegurado_nacimiento=%s, accidentes_asegurado_domicilio=UPPER(TRIM(%s)), accidentes_asegurado_actividad=%s, accidentes_asegurado_suma_asegurada=%s, accidentes_asegurado_gastos_medicos=%s, accidentes_asegurado_beneficiario=%s, accidentes_asegurado_beneficiario_nombre=%s, accidentes_asegurado_beneficiario_documento=%s, accidentes_asegurado_beneficiario_nacimiento=%s, accidentes_asegurado_beneficiario_tomador=%s WHERE accidentes_asegurado_id=%s LIMIT 1",
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
								GetSQLValueString(isset($_POST['box-accidentes_asegurado_beneficiario_tomador']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString($_POST['box-accidentes_asegurado_id'], "int"));
										
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