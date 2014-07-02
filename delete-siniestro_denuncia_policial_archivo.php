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
	if ((isset($_POST["id"])) && ($_POST["id"] != "")) {
		
		$sql = sprintf("SELECT siniestros_denuncia_policial_archivo_url FROM siniestros_denuncia_policial_archivo WHERE id=%s LIMIT 1",
						GetSQLValueString($_POST["id"], "int"));
		$res = mysql_query($sql, $connection);
		list($archivo) = mysql_fetch_array($res);
		// Delete record
		$deleteSQL = sprintf("DELETE FROM siniestros_denuncia_policial_archivo WHERE id=%s LIMIT 1",
						GetSQLValueString($_POST["id"], "int"));
		$Result1 = mysql_query($deleteSQL, $connection);
		
		// Evaluate results
		switch (mysql_errno()) {
			case 0:
				unlink($archivo);
				echo "El registro ha sido eliminado con éxito.";
				break;
			default: 
				mysql_die();
				break;
		}
		
	} else {
		die("Error: Acceso denegado.");
	}
?>