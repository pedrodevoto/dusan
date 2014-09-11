<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');		
	require_once('inc/pdf_functions.php');	
?>
<?php
if (empty($_GET['fecha_desde']) or empty($_GET['fecha_hasta']) or empty($_GET['sucursal_id'])) {
	die;
}
$date_from = mysql_real_escape_string($_GET['fecha_desde']);
$date_to = mysql_real_escape_string($_GET['fecha_hasta']);
$sucursal_id = mysql_real_escape_string($_GET['sucursal_id']);

$sql = sprintf('SELECT DATE_FORMAT(cuota_fe_pago, \'%%d/%%m/%%y\') as cuota_fe_pago, IF(cliente_tipo_persona=1, CONCAT(IFNULL(cliente_nombre, \'\'), \'\', IFNULL(cliente_apellido, \'\')), cliente_razon_social) as cliente_nombre, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, localidad_nombre, localidad_cp, CONCAT_WS("-", cliente_cuit_0, cliente_cuit_1, cliente_cuit_2) as cliente_cuit, cliente_cf_nombre as cliente_cf, CONCAT(automotor_marca_nombre, \' \', modelo, \'    Patente: \', CONCAT(IF(automotor_carroceria_id=17, "101", ""), patente_0, patente_1)) as detalle_poliza, cuota_monto FROM cuota JOIN (poliza, cliente, contacto) ON cuota.poliza_id = poliza.poliza_id AND poliza.cliente_id = cliente.cliente_id AND contacto.cliente_id = cliente.cliente_id LEFT JOIN localidad ON localidad.localidad_id = contacto.localidad_id LEFT JOIN (automotor, automotor_marca) ON automotor.poliza_id = cuota.poliza_id AND automotor_marca.automotor_marca_id = automotor.automotor_marca_id LEFT JOIN cliente_cf USING(cliente_cf_id) WHERE DATE(cuota_fe_pago) BETWEEN \'%s\' AND \'%s\' AND cuota_estado_id = 2 AND contacto_default = 1 AND sucursal_id=\'%s\' ORDER BY cuota_fe_pago ASC', $date_from, $date_to, $sucursal_id);
$res = mysql_query($sql) or die(mysql_error());

$percent_serv = 0.13045;

$type = 'pdf';
switch ($type) {
	case 'html':
		while($row = mysql_fetch_assoc($res)) {
			echo '<div style="page-break-after:always">';
			echo '<pre>';
			print_r($row);
			echo '</pre>';
			echo '</div>';
		}
		break;
	case 'pdf':
		require_once('Classes/fpdf/fpdf.php');
		require_once('Classes/fpdf/fpdi.php');
		$pdf = new FPDI('P', 'mm', array(207, 296));
		$pdf->SetMargins(0, 0);
		$pdf->SetFont('Times','',9);
		while($row = mysql_fetch_assoc($res)) {
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(FALSE);
			
			$first = TRUE;
			for ($i=0;$i<2;$i++) {
				$pdf->SetXY(160, 14+($first?0:165));
				$pdf->Write(5,$row['cuota_fe_pago']);
				$pdf->SetXY(25,43+($first?0:165));
				$pdf->Write(5,$row['cliente_nombre']);
				$pdf->SetXY(25,48+($first?0:165));
				$pdf->Write(5,$row['contacto_domicilio'].' '.$row['contacto_nro'].' '.$row['contacto_piso'].' '.$row['contacto_dpto']);
				$pdf->SetX(130);
				$pdf->Write(5,$row['localidad_nombre'].' ('.$row['localidad_cp'].')');
				$pdf->SetXY(132, 56+($first?0:165));
				$pdf->Write(5,$row['cliente_cuit']);
			
				$x = $row['cliente_cf']=='Consumidor Final'?35:$row['cliente_cf']=='Excento'?62:$row['cliente_cf']=='Monotributista'?97:NULL;
				$pdf->SetXY($x,56+($first?0:165));
				if ($x) $pdf->Write(5,'X');
			
				$pdf->SetXY(10, 85+($first?0:165));
				$pdf->Write(5, trimText('CUOTA SEGURO/SERVICIO - MANDATO N° 208 COBRANZA POR CUENTA Y ORDEN', $pdf, 150));
				$pdf->SetXY(10, 90+($first?0:165));
				$pdf->Write(5, $row['detalle_poliza']);
				$pdf->SetXY(10, 95+($first?0:165));
				$pdf->Write(5, trimText('SERVICIOS VARIOS DIRECTO', $pdf, 100));
				
				$pdf->SetXY(175,85+($first?0:165));
				$pdf->Write(5, '$'.formatNumber($row['cuota_monto'] * $percent_serv));
				$pdf->SetXY(175,136+($first?0:153));
				$pdf->Write(5, '$'.formatNumber($row['cuota_monto'] * $percent_serv));
				
				$first = FALSE;
			}
		}
		$pdf->Output();
		break;
}