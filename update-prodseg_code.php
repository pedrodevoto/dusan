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
		
		// If value not empty
		if ($strID > 0 && trim($value) !== '') {
		
			// Update
			$updateSQL = sprintf("UPDATE productor_seguro SET productor_seguro_codigo = TRIM(%s) WHERE productor_seguro.productor_seguro_id = %s LIMIT 1",
							GetSQLValueString($value, "text"),
							GetSQLValueString($strID, "int"));
			$Result1 = mysql_query($updateSQL, $connection);
			
		}
		
		// Recordset: Main
		$query_Recordset1 = sprintf("SELECT productor_seguro_codigo FROM productor_seguro WHERE productor_seguro.productor_seguro_id=%s",
							GetSQLValueString($strID, "int"));		
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);			
		
		// If record exists
		if ($totalRows_Recordset1 === 1) {
			echo $row_Recordset1['productor_seguro_codigo'];
		} else {
			echo "Error recuperando info.";	
		}		
		
		// Free Recordset: Main
		mysql_free_result($Recordset1);
		
	} else {
		die("Error actualizando registro.");
	}
?>