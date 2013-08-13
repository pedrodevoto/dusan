<?php
	// CHECK INTERFACE (avoid being run by browser, either locally or on the live server)
	if (strtolower(php_sapi_name())!="cli") {
		die("Error: Invalid interface.");
	}
?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php 
	// Extend execution time
	set_time_limit(600);	
?>
<?php
	// Show date-time
	echo "\n" . date('l jS \of F Y h:i:s A') . "\n\n";
	
	// General variables
	$affected_rows = 0;
		
	// Recordset: Poliza
	$query_Recordset1 = "SELECT poliza.poliza_id, poliza_estado_id, DATEDIFF(NOW(),poliza_validez_desde) AS startdiff, DATEDIFF(NOW(),poliza_validez_hasta) AS enddiff FROM poliza WHERE poliza_estado_id<>5";
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	// While rows in Recordset
	while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)) {

		// Determine correct state
		$estado = determineState($row_Recordset1['startdiff'], $row_Recordset1['enddiff']);

		// If state is valid and has changed
		if (!is_null($estado) && ($estado !== $row_Recordset1['poliza_estado_id'])) {
		
			// Update
			$updateSQL = sprintf("UPDATE poliza SET poliza_estado_id=%s WHERE poliza.poliza_id=%s LIMIT 1",
							GetSQLValueString($estado, "int"),
							$row_Recordset1['poliza_id']);			
			$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());
			
			// Add to counter
			$affected_rows += mysql_affected_rows();
		
		}

	}	

	// Free Recordset: Poliza
	mysql_free_result($Recordset1);	
	
	// Confirmation message
	echo "Se actualizaron ".$affected_rows." registros.\n\n";
?>