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
		$value = trim($_POST['value']);
		$date = DateTime::createFromFormat("Y-m-d", $value);
		$daterror = DateTime::getLastErrors();
		
		// If ID / value is valid
		if ($strID>0 && $date && $daterror['warning_count']===0) {
		
			// Update
			$updateSQL = sprintf("UPDATE cuota SET cuota_vencimiento = %s WHERE cuota.cuota_id = %s LIMIT 1",
							GetSQLValueString($value, "date"),
							GetSQLValueString($strID, "int"));
			$Result1 = mysql_query($updateSQL, $connection);
			
		}
		
		// Recordset: Main
		$query_Recordset1 = sprintf("SELECT cuota_vencimiento FROM cuota WHERE cuota.cuota_id=%s",
							GetSQLValueString($strID, "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);			
		
		// If record exists
		if ($totalRows_Recordset1 === 1) {
			echo $row_Recordset1['cuota_vencimiento'];
		} else {
			echo "Error recuperando info.";	
		}		
		
		// Free Recordset: Main
		mysql_free_result($Recordset1);
		
	} else {
		die("Error actualizando registro.");
	}
?>