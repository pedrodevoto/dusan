<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-autocomplete.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	// Main Query
	$query_Recordset1 = "SELECT DISTINCT cliente.cliente_nombre FROM cliente WHERE 1";
	// Append Search
	if (isset($_GET['term']) && $_GET['term'] !== "") {
		$query_Recordset1 .= sprintf(" AND cliente.cliente_nombre LIKE %s",
								GetSQLValueString('%'.$_GET['term'].'%', "text"));
	}	
	// Order By / Limit
	$query_Recordset1 .= " ORDER BY cliente.cliente_nombre ASC LIMIT 0,10";

	// Recordset
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	// Output
	$output = array();
	while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)) {
		 $output[] = $row_Recordset1['cliente_nombre'];
	}
	echo json_encode($output);
	
	// Free Recordset
	mysql_free_result($Recordset1);
?>