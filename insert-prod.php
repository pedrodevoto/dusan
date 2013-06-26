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
		$insertSQL = sprintf("INSERT INTO productor (productor_nombre, productor_iva, productor_cuit, productor_matricula, productor_email, productor_telefono) VALUES (UPPER(TRIM(%s)), %s, TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s))",
						GetSQLValueString($_POST['box-productor_nombre'], "text"),
						GetSQLValueString($_POST['box-productor_iva'], "text"),
						GetSQLValueString($_POST['box-productor_cuit'], "text"),
						GetSQLValueString($_POST['box-productor_matricula'], "text"),
						GetSQLValueString($_POST['box-productor_email'], "text"),
						GetSQLValueString($_POST['box-productor_telefono'], "text"));								
		$Result1 = mysql_query($insertSQL, $connection);
		switch (mysql_errno()) {
			case 0:
				echo 'El registro ha sido insertado con éxito | <a href="javascript:openBoxProdSeg('.mysql_insert_id().')" class="lnkBox">RELACIONAR SEGUROS</a>';
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