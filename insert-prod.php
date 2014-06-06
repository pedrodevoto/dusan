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
	if ((isset($_POST["box-insert"])) && ($_POST["box-insert"] == "1")) {	
		
		// Insert
		$insertSQL = sprintf("INSERT INTO productor (productor_nombre, productor_iva, productor_cuit, productor_matricula, productor_email, productor_telefono, productor_exportar_lr, productor_lr_numeracion) VALUES (UPPER(TRIM(%s)), %s, TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), %s, %s)",
						GetSQLValueString($_POST['box-productor_nombre'], "text"),
						GetSQLValueString($_POST['box-productor_iva'], "text"),
						GetSQLValueString($_POST['box-productor_cuit'], "text"),
						GetSQLValueString($_POST['box-productor_matricula'], "text"),
						GetSQLValueString($_POST['box-productor_email'], "text"),
						GetSQLValueString($_POST['box-productor_telefono'], "text"),
						GetSQLValueString(isset($_POST['box-productor_exportar_lr']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-productor_lr_numeracion'], "int"));								
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo 'El registro ha sido insertado con éxito';
				break;
			case 1062:
				echo 'Error: Registro duplicado (CUIT).';
				break;
			default:
				mysql_die();
				break;
		}		
		
	} else {
		die("Error: Acceso denegado.");
	}
?>