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
	if (!empty($_POST['sucursal_id']) && !empty($_POST['fecha'])) {
		
		// Insert
		$insertSQL = sprintf('INSERT INTO caja_diaria (sucursal_id, caja_diaria_fecha, caja_diaria_apertura, caja_diaria_cierre, caja_diaria_observaciones) VALUES (%1$s, %2$s, %3$s, %4$s, UPPER(TRIM(%5$s)))
			ON DUPLICATE KEY UPDATE caja_diaria_apertura=%3$s, caja_diaria_cierre=%4$s, caja_diaria_observaciones=UPPER(TRIM(%5$s)), caja_diaria_id=LAST_INSERT_ID(caja_diaria_id)',
						GetSQLValueString($_POST['sucursal_id'], "int"),
						GetSQLValueString($_POST['fecha'], "date"),
						GetSQLValueString($_POST['box-caja_diaria_apertura'], "int"),
						GetSQLValueString($_POST['box-caja_diaria_cierre'], "int"),
						GetSQLValueString($_POST['box-caja_diaria_observaciones'], "text"));		
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