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
		
		$poliza_validez_desde = $_POST['box-poliza_validez_desde'];
		$poliza_validez_hasta = $_POST['box-poliza_validez_hasta'];
		
		// Determine state
		$query_Recordset1 = sprintf("SELECT DATEDIFF(NOW(),%s) AS startdiff, DATEDIFF(NOW(),%s) AS enddiff, poliza_estado_id FROM poliza WHERE poliza_id = %s",
								GetSQLValueString($poliza_validez_desde, "date"),
								GetSQLValueString($poliza_validez_hasta, "date"),
								GetSQLValueString($_POST['box-poliza_id'], "int"));	
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$estado = determineState($row_Recordset1['startdiff'], $row_Recordset1['enddiff'], $row_Recordset1['poliza_estado_id']);
		if (is_null($estado)) {
			die("Error: No se pudo determinar el Estado de la póliza.");
		}
		
		// Update
		$updateSQL = sprintf("UPDATE poliza SET poliza_estado_id=%s, poliza_numero=TRIM(%s), poliza_validez_desde=%s, poliza_validez_hasta=%s, poliza_fecha_solicitud=%s, poliza_fecha_emision=%s, poliza_fecha_recepcion=%s, poliza_fecha_entrega=%s, poliza_correo=%s, poliza_email=%s, poliza_entregada=%s, poliza_prima=%s, poliza_medio_pago=%s, poliza_pago_detalle=%s, poliza_recargo=%s, poliza_descuento=%s",
						GetSQLValueString($estado, "int"),
						GetSQLValueString($_POST['box-poliza_numero'], "text"),							
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
						GetSQLValueString($_POST['box-poliza_descuento'], "int"));			
						
		if ($_SESSION['ADM_UserGroup']=='master') {
			$updateSQL .= sprintf(', sucursal_id=%s, productor_seguro_id=%s', GetSQLValueString($_POST['box-sucursal_id'], "int"), GetSQLValueString($_POST['box-productor_seguro_id'], "int"));
		}
		$updateSQL .= ' WHERE poliza.poliza_id='.GetSQLValueString($_POST['box-poliza_id'], "int").' LIMIT 1';
		$Result1 = mysql_query($updateSQL, $connection);
		
		switch (mysql_errno()) {
			case 0:									
				$sql = sprintf('SELECT poliza_premio FROM poliza WHERE poliza_id=%s', GetSQLValueString($_POST['box-poliza_id'], "int"));
				$res = mysql_query($sql, $connection);
				list($premio) = mysql_fetch_array($res);
	
				if (floatval($_POST['box-poliza_premio'])!=floatval($premio)) {
					if ($_SESSION['ADM_UserGroup']=='master') {
						$sql = sprintf('SELECT SUM(cuota_monto) FROM cuota WHERE poliza_id = %s AND cuota_estado_id = 2 AND cuota_pfc = 0', GetSQLValueString($_POST['box-poliza_id'], "int"));
						$res = mysql_query($sql, $connection);
						list($pagado) = mysql_fetch_array($res);
			
						$sql =  sprintf('SELECT COUNT(cuota_id) FROM cuota WHERE poliza_id = %s AND cuota_estado_id = 1 AND cuota_pfc = 0', GetSQLValueString($_POST['box-poliza_id'], "int"));
						$res = mysql_query($sql, $connection);
						list($no_pagado_cant) = mysql_fetch_array($res);
			
						$cuota = (intval($_POST['box-poliza_premio']) - $pagado) / $no_pagado_cant;
			
						$sql = sprintf('UPDATE cuota SET cuota_monto = %s WHERE poliza_id = %s AND cuota_estado_id = 1', $cuota, GetSQLValueString($_POST['box-poliza_id'], "int"));
						mysql_query($sql, $connection) or die(mysql_error());
			
						$sql = sprintf('UPDATE poliza SET poliza_premio = %s WHERE poliza_id = %s', GetSQLValueString($_POST['box-poliza_premio'], "int"), GetSQLValueString($_POST['box-poliza_id'], "int"));
						mysql_query($sql, $connection) or die(mysql_error());
					}
					else {
						die('Acceso denegado para modificar el premio.');
					}
				}

				if ($_POST['box-poliza_medio_pago']=='Directo' && isset($_POST['box-cuota_monto']) && intval($_POST['box-cuota_monto']>0)) {
					$cuota = intval($_POST['box-cuota_monto']);
					$sql = sprintf('UPDATE cuota SET cuota_monto = %s WHERE poliza_id = %s AND cuota_estado_id = 1', $cuota, GetSQLValueString($_POST['box-poliza_id'], "int"));
					mysql_query($sql, $connection) or die(mysql_error());
				}
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