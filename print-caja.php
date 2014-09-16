<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
	require_once('inc/mail_functions.php');
?>
<?php
$sql = sprintf('SELECT cuota_recibo as recibo, concat_ws(" ", cliente_apellido, cliente_nombre, cliente_razon_social) as detalle, cuota_monto as importe from cuota join poliza using (poliza_id) join cliente using (cliente_id) where cuota_no_efc = 0 and sucursal_id = %1$s and date(cuota_fe_pago) = %2$s
union
select caja_ingreso_recibo as recibo, caja_ingreso_cliente as detalle, caja_ingreso_valor as importe from caja_ingresos where sucursal_id = %1$s and date(caja_ingreso_fecha) = %2$s
order by recibo asc', GetSQLValueString($_GET['sucursal_id'], "int"), GetSQLValueString($_GET['fecha'], "date"));
$ingresos = mysql_query($sql) or die(mysql_error());

$sql = sprintf('SELECT caja_egreso_detalle as detalle, caja_egreso_valor as importe from caja_egresos where sucursal_id = %s and date(caja_egreso_fecha) = %s', GetSQLValueString($_GET['sucursal_id'], "int"), GetSQLValueString($_GET['fecha'], "date"));
$egresos = mysql_query($sql) or die(mysql_error());

$sql = sprintf('SELECT caja_diaria_apertura, caja_diaria_cierre, caja_diaria_numero from caja_diaria WHERE sucursal_id = %s AND caja_diaria_fecha = %s', GetSQLValueString($_GET['sucursal_id'], "int"), GetSQLValueString($_GET['fecha'], "date"));
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
if (!$row) {
	die('Debe guardar la caja antes de exportarla');
}
list($apertura, $cierre, $caja_diaria_numero) = $row;

// arrastre
$sql = sprintf('SELECT SUM(cuota_monto) FROM cuota JOIN poliza USING (poliza_id) WHERE cuota_no_efc = 0 AND sucursal_id = %s AND DATE(cuota_fe_pago) >= "%s" AND DATE(cuota_fe_pago) < "%s"', GetSQLValueString($_GET['sucursal_id'], "int"), date('Y-m-01', strtotime($_GET['fecha'])), date('Y-m-d', strtotime($_GET['fecha'])));
error_log($sql);
$res = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($res);
$total_cuotas = $row[0];

$sql = sprintf('SELECT SUM(caja_ingreso_valor) FROM caja_ingresos WHERE sucursal_id = %s AND DATE(caja_ingreso_fecha) >= "%s" AND DATE(caja_ingreso_fecha) < "%s"', GetSQLValueString($_GET['sucursal_id'], "int"), date('Y-m-01', strtotime($_GET['fecha'])), date('Y-m-d', strtotime($_GET['fecha'])));
$res = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($res);
$total_ingresos = $row[0];

$sql = sprintf('SELECT SUM(caja_egreso_valor) FROM caja_egresos WHERE sucursal_id = %s AND DATE(caja_egreso_fecha) >= "%s" AND DATE(caja_egreso_fecha) < "%s"', GetSQLValueString($_GET['sucursal_id'], "int"), date('Y-m-01', strtotime($_GET['fecha'])), date('Y-m-d', strtotime($_GET['fecha'])));
$res = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($res);
$total_egresos = $row[0];

$arrastre_anterior = round((float)$total_cuotas + (float)$total_ingresos - (float)$total_egresos, 2);

?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<style>
	@media print{@page {size: landscape}}
	body {
		font-size:10px;
		font-family:Arial;
	}
	table {
		border-collapse:collapse;
	}
	table {
		border-top:1px solid black;
	}
	th, td {
		font-size:10px;
		font-family:Arial;
		border:1px solid black;
	}
	@media print
	{
	  table { page-break-after:auto }
	  tr    { page-break-inside:avoid; page-break-after:auto }
	  td    { page-break-inside:avoid; page-break-after:auto }
	  thead { display:table-header-group }
	  tfoot { display:table-footer-group }
	}
	</style>
</head>
<body>
	<div style="float:left;width:33.33%;">
		<b style="font-size:12px">Apertura: $<?=(float)$apertura?></b>
	</div>
	<div style="float:left;width:33.33%;text-align:center">
		<b>PLANILLA DE CAJA</b>
	</div>
	<div style="float:left;width:33.33%;text-align:right">
		<b>N de caja: <?=$caja_diaria_numero?></b>
		-
		<b style="font-size:12px"><?=date('d/m/Y', strtotime($_GET['fecha']))?></b>
	</div>
	<div style="width:100%;">
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="5" width="50%" style="color:darkgreen">INGRESOS</th>
					<th colspan="3" width="50%" style="color:darkred">EGRESOS</th>
				</tr>
				<tr>
					<th style="color:darkgreen" width="3%">Recibo</th>
					<th style="color:darkgreen" colspan="3">Detalle</th>
					<th style="color:darkgreen" width="3%">Importe</th>
					<th style="color:darkred" width="3%">Recibo</th>
					<th style="color:darkred">Detalle</th>
					<th style="color:darkred" width="3%">Importe</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$total_ingresos = 0;
			$total_egresos = 0;
			while (true) { 
			$ingreso = mysql_fetch_assoc($ingresos);
			$egreso = mysql_fetch_assoc($egresos);
			if (isset($ingreso['importe'])) $total_ingresos += (float)$ingreso['importe'];
			if (isset($egreso['importe'])) $total_egresos += (float)$egreso['importe'];
			if (!$ingreso and !$egreso) break;
			?>
				<tr>
					<td style="color:darkgreen"><?=($ingreso?$ingreso['recibo']:'')?></td>
					<td style="color:darkgreen" colspan="3"><?=($ingreso?$ingreso['detalle']:'')?></td>
					<td style="text-align:right;color:darkgreen"><?=($ingreso?'$'.$ingreso['importe']:'')?></td>
					<td></td>
					<td style="color:darkred"><?=($egreso?$egreso['detalle']:'')?></td>
					<td style="text-align:right;color:darkred"><?=($egreso?'$'.$egreso['importe']:'')?></td>
				</tr>
			<?php 
			} 
			$saldo = (float)$total_ingresos-(float)$total_egresos;
			$arrastre = $arrastre_anterior + $saldo;
			?>
				<tr>
					<td style="border:none"></td>
					<td style="border:none;text-align:right"><b>Arrastre del día anterior: </b></td>
					<td style="border:none">$<?=$arrastre_anterior?></td>
					<td style="border:none;text-align:right">Total de ingresos: </td>
					<td style="text-align:right;color:darkgreen">$<?=$total_ingresos?></td>
					<td style="border:none"></td>
					<td style="border:none;text-align:right">Total de egresos: </td>
					<td style="text-align:right;color:darkred">$<?=$total_egresos?></td>
				</tr>
				<tr>
					<td style="border:none"></td>
					<td style="border:none;text-align:right"><b>Saldo del día:</b></td>
					<td style="border:none">$<?=$saldo?></td>
					<td style="border:none;text-align:right">Menos egresos: </td>
					<td style="text-align:right">$<?=$total_egresos?></td>
					<td style="border:none"></td>
					<td style="border:none"></td>
					<td style="border:none"></td>
				</td>
				<tr>
					<td style="border:none"></td>
					<td style="border:none;text-align:right"><b>Arrastre total: </b></td>
					<td style="border:none">$<?=$arrastre?></td>
					<td style="border:none;text-align:right">Total: </td>
					<td style="text-align:right">$<?=$saldo?></td>
					<td style="border:none"></td>
					<td style="border:none"></td>
					<td style="border:none"></td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>