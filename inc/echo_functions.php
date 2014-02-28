<?php
function showCobertura($id) {
	$query_Recordset1 = sprintf("SELECT seguro_cobertura_tipo.seguro_cobertura_tipo_id, seguro_cobertura_tipo_nombre FROM productor_seguro_cobertura_tipo JOIN seguro_cobertura_tipo ON seguro_cobertura_tipo.seguro_cobertura_tipo_id = productor_seguro_cobertura_tipo.seguro_cobertura_tipo_id WHERE productor_seguro_id=%s", 
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
function showEquipoRastreoPedido() {
	$query_Recordset1 = sprintf("SELECT equipo_rastreo_pedido_id, equipo_rastreo_pedido_nombre FROM equipo_rastreo_pedido");

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
	foreach(range(date('Y') + $future, $start, -1) as $year) {
		echo '<option value="'.$year.'">'.$year.'</option>';
	}
}

function showMarcas() {
	$query_Recordset1 = sprintf("SELECT automotor_marca_id, automotor_marca_nombre FROM automotor_marca");

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		if ($row_Recordset1[1] == 'ACURA') {
			echo '<optgroup label="----------"></optgroup>';
		}
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}

function showLimiteRC() {
	$query_Recordset1 = sprintf("SELECT seguro_cobertura_tipo_limite_rc_id, FORMAT(seguro_cobertura_tipo_limite_rc_valor, 0) FROM seguro_cobertura_tipo_limite_rc");

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}

function showZonasRiesgo($id) {
	$query_Recordset1 = sprintf("SELECT productor_seguro.zona_riesgo_id, zona_riesgo_nombre FROM productor_seguro JOIN zona_riesgo ON zona_riesgo.zona_riesgo_id = productor_seguro.zona_riesgo_id WHERE productor_seguro_id=%s", 
		GetSQLValueString($id, "int"));

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}
function showProducto() {
	$query_Recordset1 = sprintf("SELECT producto_id, producto_nombre FROM producto");

	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		echo '<option value="'.$row_Recordset1[0].'">'.$row_Recordset1[1].'</option>';
	}
}
?>
