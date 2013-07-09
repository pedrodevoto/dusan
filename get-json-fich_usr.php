<?php
	$MM_authorizedUsers = "master";
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
	$query_Recordset1 = sprintf("SELECT usuario.usuario_id, usuario_acceso, usuario_usuario, usuario_email, usuario_nombre, usuario_cambioclave, IF(usuario_reseteado=1,'SI','NO') AS usuario_reseteado, GROUP_CONCAT(sucursal_id) as usuario_sucursal FROM usuario LEFT JOIN usuario_sucursal ON usuario.usuario_id = usuario_sucursal.usuario_id WHERE usuario.usuario_id=%s GROUP BY usuario.usuario_id", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Usuario
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Usuario)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Usuario
	mysql_free_result($Recordset1);
	
?>