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
	// General variables
	$output = array();

	// Obtain URL parameter
	$poliza_id = intval($_GET['id']);
	
	// Recordset: Poliza
	$query_Recordset1 = sprintf("SELECT subtipo_poliza_tabla FROM subtipo_poliza JOIN (poliza) ON (subtipo_poliza.subtipo_poliza_id=poliza.subtipo_poliza_id) WHERE poliza.poliza_id=%s", $poliza_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	// If record exists: Poliza	
	if ($totalRows_Recordset1 === 1) {
					
		// Recordset: Detail
		$query_Recordset2 = sprintf("SELECT * FROM %s WHERE poliza_id=%s",
								$row_Recordset1['subtipo_poliza_tabla'],
								$poliza_id);
		$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);
		$totalRows_Recordset2 = mysql_num_rows($Recordset2);
					
		// If Recordset not empty
		if ($totalRows_Recordset2 > 0) {
			
			// Set Basic Info
			foreach ($row_Recordset2 as $key=>$value) {
				$output[$key] = strip_tags($value);
			}								
			
		} else {
			$output["empty"] = true;
		}	
		$output['subtipo_poliza'] = $row_Recordset1['subtipo_poliza_tabla'];
		// Close Recordset: Detail
		mysql_free_result($Recordset2);	
		
	} else {
		$output["empty"] = true;		
	} // End If record exists: Poliza
	
	// Encode JSON
	echo json_encode($output);			
		
	// Close Recordset: Poliza
	mysql_free_result($Recordset1);			
?>