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
		
		// Recordset: Contacto
		$query_Recordset1 = sprintf("SELECT contacto.cliente_id, contacto.contacto_id FROM contacto WHERE contacto.contacto_id=%s",
								GetSQLValueString($_POST["id"], "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		
		// If record exists
		if ($totalRows_Recordset1 === 1) {
				
			// Unset default contact
			$updateSQL = sprintf("UPDATE contacto SET contacto_default=0 WHERE contacto.cliente_id=%s",
							GetSQLValueString($row_Recordset1["cliente_id"], "int"));
			$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());
						
			// Update
			$updateSQL = sprintf("UPDATE contacto SET contacto_default=1 WHERE contacto.contacto_id=%s LIMIT 1",
							GetSQLValueString($row_Recordset1["contacto_id"], "int"));
			$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());
			
			// Confirm
			echo "El contacto primario fue establecido con éxito.";
			
		} else {
			echo "Error: Registro no encontrado.";
		}
		
		// Close Recordset: Contacto
		mysql_free_result($query_Recordset1);		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>