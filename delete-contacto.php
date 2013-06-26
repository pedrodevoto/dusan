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
		$query_Recordset1 = sprintf("SELECT contacto.contacto_id, contacto.cliente_id, contacto_default FROM contacto WHERE contacto.contacto_id=%s",
								GetSQLValueString($_POST["id"], "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		
		// If record exists
		if ($totalRows_Recordset1 === 1) {
				
			// Delete record
			$deleteSQL = sprintf("DELETE FROM contacto WHERE contacto.contacto_id=%s LIMIT 1",
							GetSQLValueString($row_Recordset1["contacto_id"], "int"));
			$Result1 = mysql_query($deleteSQL, $connection);
			
			// Evaluate results
			switch (mysql_errno()) {
				case 0:				
					// Confirm
					echo "El registro ha sido eliminado con éxito.";
					
					// If record was set as default
					if ($row_Recordset1['contacto_default'] == 1) {						
						// Update
						$updateSQL = sprintf("UPDATE contacto SET contacto_default=1 WHERE contacto.cliente_id=%s LIMIT 1",
										GetSQLValueString($row_Recordset1["cliente_id"], "int"));
						$Result1 = mysql_query($updateSQL, $connection);
					}
					
					// Break
					break;
				default: 
					mysql_die();
					break;
			}
			
		} else {
			echo "Error: Registro no encontrado.";
		}
		
		// Close Recordset: Contacto
		mysql_free_result($query_Recordset1);		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>