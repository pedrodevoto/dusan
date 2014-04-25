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
	// Main Query
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}
	$query_Recordset1 = sprintf("SELECT cuota_log_tipo, cuota_nro, cuota_recibo, DATE_FORMAT(cl.timestamp, '%%e/%%m/%%Y') as dia, DATE_FORMAT(cl.timestamp, '%%H:%%i:%%s') as hora, usuario_nombre FROM cuota_log cl JOIN (cuota c, usuario u) ON cl.cuota_id = c.cuota_id AND u.usuario_id = cl.usuario_id WHERE cl.poliza_id=%s ORDER BY cl.timestamp DESC",
							GetSQLValueString($colname_Recordset1, "int"));
							
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	$output = array();
	while ($row=mysql_fetch_assoc($Recordset1)) {
		$output[] = $row;
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>