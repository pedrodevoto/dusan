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
	if ((isset($_POST["id"])) && ($_POST["id"] != "")) {
		
		// Get ID / value
		$arrID = explode("_", $_POST["id"]);
		$strID = intval($arrID[1]);
		$value = $_POST['value'];
		
		// If ID is valid and value is float
		if ($strID>0 && ((string)(float)$value === $value)) {
			
			// Update
			$updateSQL = sprintf("UPDATE cuota SET cuota_monto = %s WHERE cuota.cuota_id = %s LIMIT 1",
							GetSQLValueString($value, "double"),
							GetSQLValueString($strID, "int"));
			$Result1 = mysql_query($updateSQL, $connection);
			
		}
		
		// Recordset: Main
		$query_Recordset1 = sprintf("SELECT cuota_monto FROM cuota WHERE cuota.cuota_id=%s",
							GetSQLValueString($strID, "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);			
		
		// If record exists
		if ($totalRows_Recordset1 === 1) {
			echo $row_Recordset1['cuota_monto'];
		} else {
			echo "Error recuperando info.";	
		}		
		
		// Free Recordset: Main
		mysql_free_result($Recordset1);
		
	} else {
		die("Error actualizando registro.");
	}
?>