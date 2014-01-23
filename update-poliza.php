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
	if ((isset($_POST["box-poliza_id"])) && ($_POST["box-poliza_id"] != "")) {	
	
		// Encrypt Payment info
		if (isset($_POST['box-poliza_pago_detalle']) && $_POST['box-poliza_pago_detalle'] !== '') {
			$poliza_pago_detalle = Encryption::encrypt($_POST['box-poliza_pago_detalle']);
		} else {
			$poliza_pago_detalle = '';
		}		
		
		// Update
		$updateSQL = sprintf("UPDATE poliza SET poliza_numero=TRIM(%s), productor_seguro_id=%s, poliza_validez_desde=%s, poliza_validez_hasta=%s, poliza_fecha_solicitud=%s, poliza_fecha_emision=%s, poliza_fecha_recepcion=%s, poliza_fecha_entrega=%s, poliza_correo=%s, poliza_email=%s, poliza_entregada=%s, poliza_prima=%s, poliza_medio_pago=%s, poliza_pago_detalle=%s, poliza_recargo=%s, poliza_ajuste=%s WHERE poliza.poliza_id=%s LIMIT 1",
						GetSQLValueString($_POST['box-poliza_numero'], "text"),						
						GetSQLValueString($_POST['box-productor_seguro_id'], "int"),	
						GetSQLValueString($_POST['box-poliza_validez_desde'], "date"),						
						GetSQLValueString($_POST['box-poliza_validez_hasta'], "date"),
						GetSQLValueString($_POST['box-poliza_fecha_solicitud'], "date"),
						GetSQLValueString($_POST['box-poliza_fecha_emision'], "date"),						
						GetSQLValueString($_POST['box-poliza_fecha_recepcion'], "date"),						
						GetSQLValueString($_POST['box-poliza_fecha_entrega'], "date"),
						GetSQLValueString(isset($_POST['box-poliza_correo']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString(isset($_POST['box-poliza_email']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString(isset($_POST['box-poliza_entregada']) ? 'true' : '', 'defined','1','0'),
						GetSQLValueString($_POST['box-poliza_prima'], "double"),
						GetSQLValueString($_POST['box-poliza_medio_pago'], "text"),
						GetSQLValueString($poliza_pago_detalle, "text"),		
						GetSQLValueString($_POST['box-poliza_recargo'], "double"),									
						GetSQLValueString($_POST['box-poliza_ajuste'], "int"),						
						GetSQLValueString($_POST['box-poliza_id'], "int"));			
		$Result1 = mysql_query($updateSQL, $connection);
		
		switch (mysql_errno()) {
			case 0:									
				echo "El registro ha sido actualizado.";							
				break;								
			case 1062:
				echo "Error: Registro duplicado.";
				break;
			default:
				mysql_die();
				break;
		}
		
	} else {
		die("Error: Acceso denegado.");
	}
?>