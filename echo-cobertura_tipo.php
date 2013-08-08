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
?>
