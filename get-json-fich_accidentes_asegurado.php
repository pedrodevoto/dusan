<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	// Main Query
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}
	$query_Recordset1 = sprintf("SELECT accidentes_asegurado_id, accidentes_asegurado_nombre, accidentes_asegurado_documento, accidentes_asegurado_nacimiento, accidentes_asegurado_domicilio, accidentes_asegurado_actividad, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, accidentes_asegurado_beneficiario, accidentes_asegurado_beneficiario_nombre, accidentes_asegurado_beneficiario_documento, accidentes_asegurado_beneficiario_nacimiento, accidentes_asegurado_beneficiario_tomador FROM accidentes_asegurado WHERE accidentes_asegurado_id = %s", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Asegurado
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Asegurado)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Usuario
	mysql_free_result($Recordset1);
	
?>