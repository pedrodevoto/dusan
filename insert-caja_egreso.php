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
	if (!empty($_POST['sucursal_id']) && !empty($_POST['fecha']) && !empty($_POST["egreso_cantidad"]) && !empty($_POST["egreso_detalle"]) && !empty($_POST["egreso_valor"])) {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO caja_egresos (sucursal_id, usuario_id, caja_egreso_fecha, caja_egreso_cantidad, caja_egreso_detalle, caja_egreso_valor) VALUES (%s, %s, %s, %s, UPPER(TRIM(%s)), %s)",
						GetSQLValueString($_POST['sucursal_id'], "int"),
						$_SESSION['ADM_UserId'],
						GetSQLValueString($_POST['fecha'].' '.date('H:i:s'), "text"),
						GetSQLValueString($_POST['egreso_cantidad'], "int"),
						GetSQLValueString($_POST['egreso_detalle'], "text"),
						GetSQLValueString($_POST['egreso_valor'], "double"));		
						error_log($insertSQL);
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