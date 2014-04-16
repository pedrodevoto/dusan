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
	if ((isset($_POST["box-poliza_id"])) && ($_POST["box-poliza_id"] !== "")) {
		
		// Insert
		$insertSQL = sprintf("INSERT INTO endoso (poliza_id, endoso_fecha_pedido, endoso_tipo_id, endoso_cuerpo, endoso_premio, endoso_numero, endoso_fecha_compania, endoso_completo, timestamp)
		 					  VALUES (%s, %s, %s, TRIM(%s), %s, TRIM(%s), %s, %s, NOW())",
								GetSQLValueString($_POST['box-poliza_id'], "int"),
								GetSQLValueString($_POST['box-endoso_fecha_pedido'], "date"),
								GetSQLValueString($_POST['box-endoso_tipo_id'], "int"),
								GetSQLValueString($_POST['box-endoso_cuerpo'], "text"),
								GetSQLValueString($_POST['box-endoso_premio'], "double"),
								GetSQLValueString($_POST['box-endoso_numero'], "text"),
								GetSQLValueString($_POST['box-endoso_fecha_compania'], "date"),
								GetSQLValueString(isset($_POST['box-endoso_completo']) ? 'true' : '', 'defined','1','0'));
		$Result1 = mysql_query($insertSQL, $connection);
		
		// Evaluate insert
		switch (mysql_errno()) {
			case 0:
				$endoso_id = mysql_insert_id();
				
				$sql = sprintf('SELECT poliza_premio FROM poliza WHERE poliza_id=%s', GetSQLValueString($_POST['box-poliza_id'], "int"));
				$res = mysql_query($sql, $connection);
				list($premio) = mysql_fetch_array($res);
		
				if (!empty($_POST['box-endoso_premio'])) {
					if ($_SESSION['ADM_UserGroup']=='master') {
						$sql = sprintf('SELECT SUM(cuota_monto) FROM cuota WHERE poliza_id = %s AND cuota_estado_id = 2', GetSQLValueString($_POST['box-poliza_id'], "int"));
						$res = mysql_query($sql, $connection);
						list($pagado) = mysql_fetch_array($res);
				
						$sql =  sprintf('SELECT COUNT(cuota_id) FROM cuota WHERE poliza_id = %s AND cuota_estado_id = 1', GetSQLValueString($_POST['box-poliza_id'], "int"));
						$res = mysql_query($sql, $connection);
						list($no_pagado_cant) = mysql_fetch_array($res);
				
						$cuota = (floatval($premio) + floatval($_POST['box-endoso_premio']) - $pagado) / $no_pagado_cant;
				
						$sql = sprintf('UPDATE cuota SET cuota_monto = %s WHERE poliza_id = %s AND cuota_estado_id = 1', $cuota, GetSQLValueString($_POST['box-poliza_id'], "int"));
						mysql_query($sql, $connection) or die(mysql_error());
				
						$sql = sprintf('UPDATE poliza SET poliza_premio = %s WHERE poliza_id = %s', GetSQLValueString($_POST['box-endoso_premio'], "double")+floatval($premio), GetSQLValueString($_POST['box-poliza_id'], "int"));
						mysql_query($sql, $connection) or die(mysql_error());
					}
					else {
						echo 'Acceso denegado para modificar el premio.';
					}
				}
				
				echo $endoso_id;
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