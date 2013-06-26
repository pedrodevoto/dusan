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
	if (isset($_POST["box-cliente_id"]) && $_POST["box-cliente_id"]!="") {
		
		// Recordset: Contacto
		$query_Recordset1 = sprintf("SELECT contacto.contacto_id FROM contacto WHERE contacto_default=1 AND contacto.cliente_id=%s", GetSQLValueString($_POST['box-cliente_id'], "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		mysql_free_result($Recordset1);
		
		// Set fields
		$contacto_default = ($totalRows_Recordset1 === 0) ? 1 : 0;
		
		// Insert
		$insertSQL = sprintf("INSERT INTO contacto (contacto.cliente_id, contacto_tipo, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, contacto_localidad, contacto_cp, contacto_telefono1, contacto_telefono2, contacto_default) VALUES (%s, %s, TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), TRIM(%s), %s)",
						GetSQLValueString($_POST['box-cliente_id'], "int"),
						GetSQLValueString($_POST['box-contacto_tipo'], "text"),
						GetSQLValueString($_POST['box-contacto_domicilio'], "text"),
						GetSQLValueString($_POST['box-contacto_nro'], "text"),
						GetSQLValueString($_POST['box-contacto_piso'], "text"),
						GetSQLValueString($_POST['box-contacto_dpto'], "text"),						
						GetSQLValueString($_POST['box-contacto_localidad'], "text"),
						GetSQLValueString($_POST['box-contacto_cp'], "text"),
						GetSQLValueString($_POST['box-contacto_telefono1'], "text"),
						GetSQLValueString($_POST['box-contacto_telefono2'], "text"),
						GetSQLValueString($contacto_default, "int"));						
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