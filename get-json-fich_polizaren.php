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
	$query_Recordset1 = sprintf("SELECT poliza.poliza_id, sucursal_nombre, sucursal.sucursal_id, cliente_nombre, tipo_poliza_nombre, tipo_poliza.tipo_poliza_id as tipo_poliza_id, subtipo_poliza_nombre, poliza.subtipo_poliza_id as subtipo_poliza_id, poliza_numero AS poliza_renueva_num, poliza_vigencia, poliza_vigencia_dias, productor_seguro.seguro_id, productor_seguro.productor_seguro_id, poliza_prima, poliza_premio, poliza_medio_pago, poliza_pago_detalle, poliza_recargo, poliza_descuento, poliza_validez_hasta as poliza_validez_desde, poliza_flota, sucursal_pfc, sucursal_pfc_default
									FROM poliza JOIN (cliente, tipo_poliza, subtipo_poliza, productor_seguro, sucursal) ON (poliza.cliente_id=cliente.cliente_id AND tipo_poliza.tipo_poliza_id=subtipo_poliza.tipo_poliza_id AND poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND poliza.sucursal_id = sucursal.sucursal_id)
									WHERE poliza.poliza_id=%s", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		
	
	$output = array();
	
	// If Recordset not empty (Main)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			// Custom: Decrypt field
			if ($key === 'poliza_pago_detalle') {
				$value = Encryption::decrypt($value);				
			}
			$output[$key] = strip_tags($value);
		}				
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Main
	mysql_free_result($Recordset1);	
?>