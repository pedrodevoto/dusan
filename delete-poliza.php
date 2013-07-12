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
		
		// Recordset: Poliza
		$query_Recordset1 = sprintf("SELECT subtipo_poliza_tabla FROM subtipo_poliza JOIN (poliza) ON (subtipo_poliza.subtipo_poliza_id=poliza.subtipo_poliza_id) WHERE poliza.poliza_id=%s", intval(mysql_real_escape_string($_POST["id"])));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		if ($totalRows_Recordset1 !== 1) {
			die("Error: Poliza no encontrada.");
		}
		mysql_free_result($Recordset1);	

		;
		$deleteSQL = sprintf("DELETE FROM %s WHERE poliza_id=%s",
						$row_Recordset1['subtipo_poliza_tabla'],
						GetSQLValueString($_POST["id"], "int"));
		mysql_query($deleteSQL, $connection);
		
		$deleteSQL = sprintf("DELETE FROM cuota WHERE poliza_id=%s",
						GetSQLValueString($_POST["id"], "int"));
		mysql_query($deleteSQL, $connection);
		
		
		
		// Delete record
		$deleteSQL = sprintf("DELETE FROM poliza WHERE poliza_id=%s LIMIT 1",
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