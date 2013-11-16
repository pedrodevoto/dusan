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
	$query_Recordset1 = sprintf("SELECT contacto_tipo, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, contacto_localidad, contacto_cp, contacto_country, contacto_lote, contacto_telefono1, contacto_telefono2 FROM contacto WHERE contacto_id=%s", GetSQLValueString($colname_Recordset1, "int"));
			
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
	$output = array();
	foreach ($row_Recordset1 as $key=>$value) {
		$output[$key] = strip_tags($value);
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>