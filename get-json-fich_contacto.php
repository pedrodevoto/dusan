<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	// Main query
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}	
	$query_Recordset1 = sprintf("SELECT contacto.contacto_id, contacto_tipo, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, localidad_nombre, localidad_cp, contacto_country, contacto_lote, contacto_telefono1, concat('15-',contacto_telefono2) contacto_telefono2, contacto_default FROM contacto LEFT JOIN localidad ON localidad.localidad_id = contacto.localidad_id WHERE contacto.cliente_id=%s", GetSQLValueString($colname_Recordset1, "int"));
			
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
	$output = array();
	for ($i=0; $i<$totalRows_Recordset1; $i++) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$i][$key] = strip_tags($value);
		}		
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>