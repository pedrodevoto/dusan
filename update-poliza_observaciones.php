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
	if ((isset($_POST["box-poliza_id"])) && ($_POST["box-poliza_id"] != "")) {	
	
		// Update
		$updateSQL = sprintf("UPDATE poliza SET poliza_observaciones=UPPER(TRIM(%s)), poliza_cobranza_domicilio=%s WHERE poliza_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-poliza_observaciones'], "date"),
						GetSQLValueString(isset($_POST['box-poliza_cobranza_domicilio']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-poliza_id'], "int"));			
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
			case 0:									
				echo "El registro ha sido actualizado.";							
				break;								
			case 1062:
				echo "Error: Registro duplicado.";
				break;
			default:
				mysql_die();
				break;
		}
		
	} else {
		die("Error: Acceso denegado.");
	}
?>