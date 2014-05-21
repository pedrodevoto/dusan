<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require Connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php
	// Main Query
	$query_Recordset1 = "SELECT cliente.cliente_id, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre, cliente_tipo_doc, cliente_nro_doc FROM cliente";

	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1 .= sprintf(' JOIN (cliente_sucursal, usuario_sucursal) ON cliente.cliente_id = cliente_sucursal.cliente_id AND usuario_sucursal.sucursal_id = cliente_sucursal.sucursal_id WHERE usuario_id = %s', GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	else {
		$query_Recordset1 .= ' WHERE 1';
	}
	
	// Query Where	
	if (isset($_GET['box0-cliente_nombre']) || isset($_GET['box0-cliente_nro_doc'])) {
		if (isset($_GET['box0-cliente_nombre']) && $_GET['box0-cliente_nombre'] !== '') {
			$query_Recordset1 .= sprintf(" AND IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) LIKE %s",
									GetSQLValueString('%'.$_GET['box0-cliente_nombre'].'%', "text"));			
		}
		if (isset($_GET['box0-cliente_nro_doc']) && $_GET['box0-cliente_nro_doc'] !== '') {
			$query_Recordset1 .= sprintf(" AND cliente.cliente_nro_doc=%s",
									GetSQLValueString($_GET['box0-cliente_nro_doc'], "text"));
		}	
	} else 	{
		$query_Recordset1 .= " AND 1=2";
	}
	
	$query_Recordset1 .= ' LIMIT 5';
	error_log($query_Recordset1);
	// Recordset	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	// Output
	$output = array();
	$i = 0;
	if ($totalRows_Recordset1 > 0) {
		while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)) {
			foreach ($row_Recordset1 as $key=>$value) {
				$output[$i][$key] = strip_tags($value);
			}
			$i++;
		}
	} else {
		$output["empty"] = true;
	}
	echo json_encode($output);			
	
	// Close Recordset
	mysql_free_result($Recordset1);	
?>