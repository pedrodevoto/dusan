<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');		
?>
<?php	
	// Main Query
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}
	$query_Recordset1 = "SELECT endoso_tipo_id, endoso_tipo_nombre, endoso_tipo_grupo_nombre FROM endoso_tipo JOIN endoso_tipo_grupo ON endoso_tipo.endoso_tipo_grupo_id = endoso_tipo_grupo.endoso_tipo_grupo_id ORDER BY endoso_tipo_grupo_nombre, endoso_tipo_nombre";
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = array();
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[2]][] = array($row_Recordset1[0], strip_tags($row_Recordset1[1]));
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>
