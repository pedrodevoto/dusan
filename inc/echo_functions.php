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

function showEquipoRastreo() {
	$query_Recordset1 = sprintf("SELECT equipo_rastreo_id, equipo_rastreo_nombre FROM equipo_rastreo");

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}

function showYears($future = 0, $start = 1974) {
	foreach(range($start, date('Y') + $future) as $year) {
		echo '<option value="'.$year.'">'.$year.'</option>';
	}
}

function showMarcas() {
	$query_Recordset1 = sprintf("SELECT automotor_marca_id, automotor_marca_nombre FROM automotor_marca");

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}
?>
