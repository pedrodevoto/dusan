<?php
	$MM_authorizedUsers = "master";
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
		
		$query_Recordset1 = sprintf("SELECT poliza_id, subtipo_poliza_tabla FROM poliza JOIN (subtipo_poliza) ON (subtipo_poliza.subtipo_poliza_id=poliza.subtipo_poliza_id) WHERE poliza.cliente_id=%s", intval(mysql_real_escape_string($_POST["id"])));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		
		while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)) {
			$deleteSQL = sprintf("DELETE FROM %s WHERE poliza_id=%s",
							$row_Recordset1['subtipo_poliza_tabla'],
							$row_Recordset1['poliza_id']);
			mysql_query($deleteSQL, $connection);
		
			$deleteSQL = sprintf("DELETE FROM cuota WHERE poliza_id=%s",
							$row_Recordset1['poliza_id']);
			mysql_query($deleteSQL, $connection);
		
			$deleteSQL = sprintf("DELETE FROM poliza WHERE poliza_id=%s",
							$row_Recordset1['poliza_id']);
			$Result1 = mysql_query($deleteSQL, $connection);
			
		}
		
		mysql_free_result($Recordset1);	

		
		$deleteSQL = sprintf("DELETE FROM contacto WHERE cliente_id=%s",
						GetSQLValueString($_POST["id"], "int"));
		$Result1 = mysql_query($deleteSQL, $connection);
	
	
		// Delete record
		$deleteSQL = sprintf("DELETE FROM cliente WHERE cliente_id=%s LIMIT 1",
						GetSQLValueString($_POST["id"], "int"));
		$Result1 = mysql_query($deleteSQL, $connection);
		
		
		// Evaluate results
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido eliminado con éxito.";
				break;
			default: 
				mysql_die();
				break;
		}
		
	} else {
		die("Error: Acceso denegado.");
	}
?>