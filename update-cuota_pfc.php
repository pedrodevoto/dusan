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
		
		// Recordset: Cuota
		$query_Recordset1 = sprintf("SELECT cuota.cuota_id, cuota_pfc FROM cuota WHERE cuota_nro=1 AND cuota.cuota_id=%s",
								GetSQLValueString($_POST["id"], "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		
		// If record exists
		if ($totalRows_Recordset1 === 1) {
											
			// Update
			$updateSQL = sprintf("UPDATE cuota SET cuota_pfc=IF(%s=1,0,1) WHERE cuota_nro=1 AND cuota.cuota_id=%s LIMIT 1",
							GetSQLValueString($row_Recordset1["cuota_pfc"], "int"),
							GetSQLValueString($row_Recordset1["cuota_id"], "int"));
			$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());
			
			// Confirm
			echo "El PFC de la cuota ha sido modificado.";
			
		} else {
			echo "Error: Registro no encontrado.";
		}
		
		// Close Recordset: Cuota
		mysql_free_result($query_Recordset1);		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>