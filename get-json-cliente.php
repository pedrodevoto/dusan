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
	$query_Recordset1 = "SELECT cliente.cliente_id, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre FROM cliente";
	
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1 .= sprintf(' JOIN (cliente_sucursal, usuario_sucursal) ON cliente.cliente_id = cliente_sucursal.cliente_id AND usuario_sucursal.sucursal_id = cliente_sucursal.sucursal_id WHERE usuario_id = %s', GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	
	$query_Recordset1 .= " ORDER BY cliente_nombre";

	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = array();
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		$output[] = array($row_Recordset1[0], strip_tags($row_Recordset1[1]));
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>
