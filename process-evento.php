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
if (!empty($_POST['box-evento_titulo'])) {	
	if (!empty($_POST['box-evento_id'])) {
		$sql = sprintf("UPDATE evento set evento_titulo=UPPER(TRIM(%s)), evento_descripcion=UPPER(TRIM(%s)) where evento_id = %s",
						GetSQLValueString($_POST['box-evento_titulo'], "text"),
						GetSQLValueString($_POST['box-evento_descripcion'], "text"),
						GetSQLValueString($_POST['box-evento_id'], "int"));
		mysql_query($sql, $connection) or die(mysql_die());
	}
	elseif (!empty($_POST['box-evento_fecha'])) {
		$sql = sprintf("INSERT INTO evento (evento_fecha, evento_titulo, evento_descripcion) VALUES (%s, UPPER(TRIM(%s)), UPPER(TRIM(%s)))",
						GetSQLValueString($_POST['box-evento_fecha'], "date"),
						GetSQLValueString($_POST['box-evento_titulo'], "text"),
						GetSQLValueString($_POST['box-evento_descripcion'], "text"));
		$res = mysql_query($sql, $connection) or die(mysql_die());
		echo mysql_insert_id($connection);
	}
	
} else {
	die("Error: No se especificaron todos los campos.");
}
?>