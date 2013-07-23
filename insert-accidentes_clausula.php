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
	if (isset($_POST["box-poliza_id"]) && $_POST["box-poliza_id"]!="") {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO accidentes_clausula (poliza_id, accidentes_clausula_nombre, accidentes_clausula_cuit, accidentes_clausula_domicilio) VALUES (%s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)))",
						GetSQLValueString($_POST['box-poliza_id'], "int"),
						GetSQLValueString($_POST['box-accidentes_clausula_nombre'], "text"),
						GetSQLValueString($_POST['box-accidentes_clausula_cuit'], "text"),
						GetSQLValueString($_POST['box-accidentes_clausula_domicilio'], "text"));						
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
				break;
			default:
				mysql_die();
				break;
		}							
		
	} else {
		die("Error: Acceso denegado.");
	}
?>