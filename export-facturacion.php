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
$date_from = mysql_real_escape_string($_GET['fecha_desde']);
$date_to = mysql_real_escape_string($_GET['fecha_hasta']);

$sql = sprintf('SELECT DATE_FORMAT(cuota_fe_pago, \'%%d/%%m/%%y\') as cuota_fe_pago, cliente_nombre, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, contacto_localidad, contacto_cp, cliente_cuit, cliente_cf, CONCAT(automotor_marca_nombre, \' \', modelo, \'    Patente: \', patente) as detalle_poliza, cuota_monto FROM cuota JOIN (poliza, cliente, contacto) ON cuota.poliza_id = poliza.poliza_id AND poliza.cliente_id = cliente.cliente_id AND contacto.cliente_id = cliente.cliente_id LEFT JOIN (automotor, automotor_marca) ON automotor.poliza_id = cuota.poliza_id AND automotor_marca.automotor_marca_id = automotor.automotor_marca_id WHERE DATE(cuota_fe_pago) BETWEEN \'%s\' AND \'%s\' AND cuota_estado = \'2 - Pagado\' AND contacto_default = 1', $date_from, $date_to);
$res = mysql_query($sql) or die(mysql_error());

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
		$pdf = new FPDI();
		$pdf->SetFont('Times','',9);
		while($row = mysql_fetch_assoc($res)) {
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(FALSE);
			
			$first = TRUE;
			for ($i=0;$i<2;$i++) {
				$pdf->SetXY(166, 18+($first?0:148));
				$pdf->Write(5,$row['cuota_fe_pago']);
				$pdf->SetXY(25,40.5+($first?0:148.5));
				$pdf->Write(5,$row['cliente_nombre']);
				$pdf->SetXY(25,46+($first?0:148.5));
				$pdf->Write(5,$row['contacto_domicilio'].' '.$row['contacto_nro'].' '.$row['contacto_piso'].' '.$row['contacto_dpto']);
				$pdf->SetX(132);
				$pdf->Write(5,$row['contacto_localidad'].' ('.$row['contacto_cp'].')');
				$pdf->SetXY(132, 55+($first?0:148.5));
				$pdf->Write(5,$row['cliente_cuit']);
			
				$x = $row['cliente_cf']=='Consumidor Final'?40:$row['cliente_cf']=='Excento'?67:$row['cliente_cf']=='Monotributista'?102:NULL;
				$pdf->SetXY($x,55+($first?0:148.5));
				if ($x) $pdf->Write(5,'X');
			
				$pdf->SetXY(31.5, 82+($first?0:146));
				$pdf->Write(5, $row['detalle_poliza']);
				$pdf->SetXY(183,86+($first?0:149));
				$pdf->Write(5, '$'.formatNumber($row['cuota_monto']));
				$pdf->SetXY(181,127+($first?0:148));
				$pdf->Write(5, '$'.formatNumber($row['cuota_monto']));
				
				$first = FALSE;
			}
		}
		$pdf->Output();
		break;
}