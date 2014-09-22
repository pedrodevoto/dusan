<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	require_once('inc/db_functions.php');	
?>
<?php
	// Recordset: Main	
	$output = array();
	 
	$sql = sprintf('SELECT evento_id, evento_fecha, evento_titulo, evento_descripcion from evento where evento_fecha BETWEEN %s AND DATE(%s)-interval 1 day', GetSQLValueString($_GET['start'], 'date'), GetSQLValueString($_GET['end'], 'date'));
	$res = mysql_query($sql, $connection) or die(mysql_die());
	while ($row = mysql_fetch_array($res)) {
		$output[] = array('title'=>$row[2], 'start'=>$row[1], 'id'=>$row[0], 'description'=>$row[3]);
	}

	echo json_encode($output);
	
	// Close Recordset: Main	
	mysql_free_result($res);
?>