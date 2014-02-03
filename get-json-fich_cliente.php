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
	$query_Recordset1 = sprintf("SELECT cliente.cliente_id, cliente_tipo_persona, cliente_nombre, cliente_apellido, cliente_razon_social, cliente_tipo_sociedad_id, cliente_nacimiento, cliente_sexo, cliente_tipo_doc, cliente_nro_doc, cliente_nacionalidad_id, cliente_cf_id, GROUP_CONCAT(cliente_reg_tipo_id) as cliente_reg_tipo_id, cliente_registro, cliente_reg_vencimiento, cliente_cuit, cliente_email, cliente_email_alt FROM cliente LEFT JOIN cliente_cliente_reg_tipo ON cliente_cliente_reg_tipo.cliente_id = cliente.cliente_id WHERE cliente.cliente_id=%s", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Cliente
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Cliente)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Cliente
	mysql_free_result($Recordset1);	
?>