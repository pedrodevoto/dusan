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
	if ((isset($_POST["box-cliente_id"])) && ($_POST["box-cliente_id"] !== "")) {
		
		// Get reusable fields
		$poliza_cant_cuotas = intval($_POST['box-poliza_cant_cuotas']);
		$poliza_premio = doubleval($_POST['box-poliza_premio']);
		$poliza_validez_desde = $_POST['box-poliza_validez_desde'];
		$poliza_validez_hasta = $_POST['box-poliza_validez_hasta'];
		
		// Determine state
		$query_Recordset1 = sprintf("SELECT DATEDIFF(NOW(),%s) AS startdiff, DATEDIFF(NOW(),%s) AS enddiff FROM DUAL",
								GetSQLValueString($poliza_validez_desde, "date"),
								GetSQLValueString($poliza_validez_hasta, "date"));	
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$estado = determineState($row_Recordset1['startdiff'], $row_Recordset1['enddiff']);
		if (is_null($estado)) {
			die("Error: No se pudo determinar el Estado de la póliza.");
		}
		mysql_free_result($Recordset1);		
		
		// Encrypt Payment info
		if (isset($_POST['box-poliza_pago_detalle']) && $_POST['box-poliza_pago_detalle'] !== '') {
			$poliza_pago_detalle = Encryption::encrypt($_POST['box-poliza_pago_detalle']);
		} else {
			$poliza_pago_detalle = '';
		}
		
		// Insert
		$insertSQL = sprintf("INSERT INTO poliza (sucursal_id, cliente_id, subtipo_poliza_id, poliza_estado_id, poliza_numero, productor_seguro_id, poliza_vigencia, poliza_vigencia_dias, poliza_validez_desde, poliza_validez_hasta, poliza_cuotas, poliza_cant_cuotas, poliza_fecha_solicitud, poliza_fecha_emision, poliza_fecha_recepcion, poliza_fecha_entrega, poliza_correo, poliza_email, poliza_entregada, poliza_prima, poliza_premio, poliza_medio_pago, poliza_pago_detalle, poliza_recargo, poliza_ajuste, poliza_plan_flag, poliza_plan_id, poliza_pack_id)
		 					  VALUES (%s, %s, %s, %s, TRIM(%s), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
								GetSQLValueString($_POST['box-sucursal_id'], "int"),
								GetSQLValueString($_POST['box-cliente_id'], "int"),
								GetSQLValueString($_POST['box-subtipo_poliza_id'], "int"),
								GetSQLValueString($estado, "int"),
								GetSQLValueString($_POST['box-poliza_numero'], "text"),
								GetSQLValueString($_POST['box-productor_seguro_id'], "int"),
								GetSQLValueString($_POST['box-poliza_vigencia'], "text"),
								$_POST['box-poliza_vigencia']=='Otra'?
									GetSQLValueString($_POST['box-poliza_vigencia_dias'], "int"):'NULL',
								GetSQLValueString($poliza_validez_desde, "date"),
								GetSQLValueString($poliza_validez_hasta, "date"),
								GetSQLValueString($_POST['box-poliza_cuotas'], "text"),
								GetSQLValueString($poliza_cant_cuotas, "int"),
								GetSQLValueString($_POST['box-poliza_fecha_solicitud'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_emision'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_recepcion'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_entrega'], "date"),
								GetSQLValueString(isset($_POST['box-poliza_correo']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString(isset($_POST['box-poliza_email']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString(isset($_POST['box-poliza_entregada']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString($_POST['box-poliza_prima'], "double"),
								GetSQLValueString($poliza_premio, "double"),
								GetSQLValueString($_POST['box-poliza_medio_pago'], "text"),
								GetSQLValueString($poliza_pago_detalle, "text"),
								GetSQLValueString($_POST['box-poliza_recargo'], "double"),
								GetSQLValueString($_POST['box-poliza_ajuste'], "int"),
								GetSQLValueString($_POST['box-poliza_plan_flag'], "int"),
								$_POST['box-poliza_plan_flag']=='1'? 
									GetSQLValueString($_POST['box-poliza_plan_id'], "int"):'NULL',
								$_POST['box-poliza_plan_flag']=='1'? 
									GetSQLValueString($_POST['box-poliza_pack_id'], "int"):'NULL');								
		$Result1 = mysql_query($insertSQL, $connection);
		
		// Evaluate insert
		switch (mysql_errno()) {
			case 0:
			
				// Get ID
				$poliza_id = mysql_insert_id();
			
				// Insert: Cuotas
				$monto = $poliza_premio / $poliza_cant_cuotas;
				
				$pfc = (isset($_POST['box-sucursal_pfc'])?1:0);
				$poliza_cant_cuotas += $pfc;
				
				for ($i=0; $i<$poliza_cant_cuotas; $i++) {
					$insertSQL = sprintf("INSERT INTO cuota (poliza_id, cuota_nro, cuota_periodo, cuota_monto, cuota_vencimiento, cuota_estado_id, cuota_pfc) VALUES (%s, %s, DATE_FORMAT(DATE_ADD(%s, INTERVAL %s MONTH),'%%Y-%%m-01'), %s, DATE_ADD(%s, INTERVAL %s MONTH), 1, %s)",
									GetSQLValueString($poliza_id, "int"),
									GetSQLValueString($i+1, "int"),
									GetSQLValueString($poliza_validez_desde, "date"),									
									GetSQLValueString($i, "int"),									
									GetSQLValueString($monto, "double"),
									GetSQLValueString($poliza_validez_desde, "date"),
									GetSQLValueString($i, "int"),
									(($i==0 and $pfc==1)?'1':'0')); 
					$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
				}
				
				$query_Recordset1 = sprintf("SELECT subtipo_poliza_tabla, subtipo_poliza_polizadet_auto FROM subtipo_poliza WHERE subtipo_poliza_id=%s",
										GetSQLValueString($_POST['box-subtipo_poliza_id'], "int"));	
				$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
				$row_Recordset1 = mysql_fetch_assoc($Recordset1);
				
				if ($row_Recordset1['subtipo_poliza_polizadet_auto']==1){
					$insertSQL = "INSERT INTO ".$row_Recordset1['subtipo_poliza_tabla']." (poliza_id) VALUES(".$poliza_id.")";
					$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
				}
				
				// Return ID
				echo $poliza_id;
				
				break;
			case 1062:
				echo 'Error: Registro duplicado.';
				break;
			default:
				mysql_die();
				break;
		}


	} else {
		die("Error: Acceso denegado.");
	}
?>