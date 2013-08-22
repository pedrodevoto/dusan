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
	if ((isset($_POST["box-poliza_id"])) && ($_POST["box-poliza_id"] !== "")) {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO endoso (poliza_id, endoso_fecha_pedido, endoso_tipo_id, endoso_cuerpo, endoso_numero, endoso_fecha_compania, endoso_completo)
		 					  VALUES (%s, %s, %s, TRIM(%s), TRIM(%s), %s, %s)",
								GetSQLValueString($_POST['box-poliza_id'], "int"),
								GetSQLValueString($_POST['box-endoso_fecha_pedido'], "date"),
								GetSQLValueString($_POST['box-endoso_tipo_id'], "int"),
								GetSQLValueString($_POST['box-endoso_cuerpo'], "text"),
								GetSQLValueString($_POST['box-endoso_numero'], "text"),
								GetSQLValueString($_POST['box-endoso_fecha_compania'], "date"),
								GetSQLValueString(isset($_POST['box-endoso_completo']) ? 'true' : '', 'defined','1','0'));
		$Result1 = mysql_query($insertSQL, $connection);
		
		// Evaluate insert
		switch (mysql_errno()) {
			case 0:
				echo "El registro ha sido insertado con éxito.";
				break;
			case 1062:
				echo 'Error: Registro duplicado.';
				break;
			default:
				mysql_die();
				break;
		}


	} else {
		die("Error: Acceso denegado.");
	}
?>