<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php	
	// Get ID / value
	$arrID = explode("_", $_POST["id"]);
	$strID = intval($arrID[1]);	
	$value = $_POST['value'];

	// Recordset: Main
	$query_Recordset1 = sprintf("SELECT cuota_estado_id FROM cuota WHERE cuota.cuota_id=%s",
						GetSQLValueString($strID, "int"));	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	// If record exists
	if ($totalRows_Recordset1 === 1) {
		
		// Switch (Estado)
		switch ($value) {
			case 1:
				// Do Nothing
				break;
			case 2:
				// Recordset: Max Value
				$query_Recordset2 = "SELECT COALESCE(MAX(cuota_recibo)+1,1) AS val_max FROM cuota";
				$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
				$row_Recordset2 = mysql_fetch_assoc($Recordset2);				
				// Update
				$updateSQL = sprintf("UPDATE cuota SET cuota_estado_id=%s, cuota_fe_pago=NOW(), cuota_recibo=%s WHERE cuota.cuota_id=%s LIMIT 1",
								GetSQLValueString($value, "text"),
								$row_Recordset2['val_max'],
								GetSQLValueString($strID, "int"));											
				$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());
				// Close Recordset: Max Value
				mysql_free_result($Recordset2);
				// Show value
				echo $value;
				// Break							
				break;
			case 3:
				// If not specific state			
				if ($row_Recordset1['cuota_estado_id'] !== 1) {
					// Update
					$updateSQL = sprintf("UPDATE cuota SET cuota_estado_id=%s WHERE cuota.cuota_id=%s LIMIT 1",
									GetSQLValueString($value, "int"),
									GetSQLValueString($strID, "int"));											
					$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());
					// Show value
					echo $value;			
				} else {
					// Show original value
					echo $row_Recordset1['cuota_estado_id'];
				}
				break;
			default:
				die("Error: No se puede determinar Estado.");
				break;
		}	
		
	} else {
		die("Error: Record not found.");
	}		
	
	// Close Recordset: Main
	mysql_free_result($Recordset1);	
?>