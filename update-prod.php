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
	if ((isset($_POST["box-productor_id"])) && ($_POST["box-productor_id"] != "")) {		
		
		// Update
		$updateSQL = sprintf("UPDATE productor SET productor_nombre=UPPER(TRIM(%s)), productor_iva=%s, productor_cuit=TRIM(%s), productor_matricula=TRIM(%s), productor_email=TRIM(%s), productor_telefono=TRIM(%s) WHERE productor.productor_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-productor_nombre'], "text"),
						GetSQLValueString($_POST['box-productor_iva'], "text"),
						GetSQLValueString($_POST['box-productor_cuit'], "text"),
						GetSQLValueString($_POST['box-productor_matricula'], "text"),
						GetSQLValueString($_POST['box-productor_email'], "text"),
						GetSQLValueString($_POST['box-productor_telefono'], "text"),
						GetSQLValueString($_POST['box-productor_id'], "int"));			
		$Result1 = mysql_query($updateSQL, $connection);
		switch (mysql_errno()) {
			case 0:									
				echo "El registro ha sido actualizado.";							
				break;								
			case 1062:
				echo "Error: Registro duplicado (CUIT).";
				break;
			default:
				mysql_die();
				break;
		}
		
	} else {
		die("Error: Acceso denegado.");
	}
?>