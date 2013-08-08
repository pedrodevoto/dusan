<?php
function showCobertura($id) {
	$query_Recordset1 = sprintf("SELECT seguro_cobertura_tipo.cobertura_tipo_id, cobertura_tipo_nombre FROM seguro_cobertura_tipo JOIN cobertura_tipo on cobertura_tipo.cobertura_tipo_id = seguro_cobertura_tipo.cobertura_tipo_id WHERE seguro_id=%s", 
		GetSQLValueString($id, "int"));

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}

function showCarroceria($id) {
	$query_Recordset1 = sprintf("SELECT automotor_carroceria.automotor_carroceria_id, automotor_carroceria_nombre FROM automotor_tipo_carroceria JOIN automotor_carroceria ON automotor_carroceria.automotor_carroceria_id = automotor_tipo_carroceria.automotor_carroceria_id JOIN automotor ON automotor.automotor_tipo_id = automotor_tipo_carroceria.automotor_tipo_id WHERE poliza_id=%s", 
		GetSQLValueString($id, "int"));

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}

function showAutomotorTipo() {
	$query_Recordset1 = sprintf("SELECT automotor_tipo_id, automotor_tipo_nombre FROM automotor_tipo");

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}
?>
