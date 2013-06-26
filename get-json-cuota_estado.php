<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
?>
<?php
	// Main Query
	$query_Recordset1 = "SELECT column_type FROM information_schema.columns WHERE table_name = 'cuota' AND column_name = 'cuota_estado'";
	
	// Recordset: Cliente
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();	
	
	// If Recordset not empty (Cliente)
	if ($totalRows_Recordset1 > 0) {	

		// Parse result	
		$result = str_replace(array("enum('", "')", "''"), array('', '', "'"), $row_Recordset1['column_type']);
		$result = explode("','", $result);
		
		// Create array
		foreach ($result as $key=>$value) {
			$output[$value] = ucfirst($value);
		}

	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Cliente
	mysql_free_result($Recordset1);
?>