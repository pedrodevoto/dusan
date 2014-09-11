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

?>
<html>
<head>
	<style>
	@media print{@page {size: landscape}}
	body {
		font-size:8px;
		font-family:Arial;
	}
	table {
		border-collapse:collapse;
	}
	th, td {
		font-size:8px;
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
	  div.page {page-break-inside: avoid;}
	}
	</style>
</head>
<body>
	<div style="width:100%;margin:auto;text-align:center">
		<b>PLANILLA DE CAJA</b>
	</div>
	<div style="width:100%;">
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="3" width="50%">INGRESOS</th>
					<th colspan="3" width="50%">EGRESOS</th>
				</tr>
				<tr>
					<th width="5%">Comprobante</th>
					<th>Detalle</th>
					<th width="5%">Importe</th>
					<th width="5%">Comprobante</th>
					<th>Detalle</th>
					<th width="5%">Importe</th>
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
					<td><?=($ingreso?$ingreso['recibo']:'')?></td>
					<td><?=($ingreso?$ingreso['detalle']:'')?></td>
					<td style="text-align:right"><?=($ingreso?$ingreso['importe']:'')?></td>
					<td></td>
					<td><?=($egreso?$egreso['detalle']:'')?></td>
					<td style="text-align:right"><?=($egreso?$egreso['importe']:'')?></td>
				</tr>
			<?php 
			} 
			?>
			</tbody>
			<tfoot>
				<tr><div class="page">
					<td style="border:none"></td>
					<td style="border:none;text-align:right">Total de ingresos: </td>
					<td style="text-align:right"><?=$total_ingresos?></td>
					<td style="border:none"></td>
					<td style="border:none;text-align:right">Total de egresos: </td>
					<td style="text-align:right"><?=$total_egresos?></td>
				</div></tr>
			</tfoot>
		</table>
	</div>
</body>
</html>