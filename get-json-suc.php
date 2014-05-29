<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	require_once('inc/db_functions.php');	
?>
<?php
	// Recordset: Main	
	
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1 = sprintf("SELECT sucursal.sucursal_id, sucursal_nombre FROM usuario_sucursal JOIN sucursal ON sucursal.sucursal_id = usuario_sucursal.sucursal_id WHERE usuario_id = %s",
			GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	else {
		$query_Recordset1 = "SELECT sucursal_id, sucursal_nombre FROM sucursal";
	}
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());

	$output = array();
	while ($row_Recordset1 = mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>