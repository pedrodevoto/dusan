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
	// Main query
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}	
	$query_Recordset1 = sprintf("SELECT endoso_id, DATE_FORMAT(endoso_fecha_pedido, '%%d/%%m/%%y') as endoso_fecha_pedido, DATE_FORMAT(endoso_fecha_compania, '%%d/%%m/%%y') as endoso_fecha_compania, CONCAT(endoso_tipo_nombre, ' (', endoso_tipo_grupo_nombre, ')') as endoso_tipo, IF(endoso_completo=1, 'SÍ', 'NO') as endoso_completo FROM endoso JOIN (endoso_tipo, endoso_tipo_grupo) ON (endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo.endoso_tipo_grupo_id = endoso_tipo_grupo.endoso_tipo_grupo_id) WHERE endoso.poliza_id=%s", GetSQLValueString($colname_Recordset1, "int"));
			
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	$output = array();
	for ($i=0; $i<$totalRows_Recordset1; $i++) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$i][$key] = strip_tags($value);
		}		
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>