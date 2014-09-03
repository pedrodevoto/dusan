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
	require_once('Classes/PHPExcel.php');
?>
<?php

$year = mysql_real_escape_string($_GET['ano']);
$month = mysql_real_escape_string($_GET['mes']);
$year_0 = $year;
$month_0 = $month - 2;
$month_1 = ($month==1?12:$month - 1);
if ($month_0<1) {
	$month_0 += 12;
	$year_0--;
}
$date_0 = date('01/m/Y', strtotime($year_0.'-'.$month_0.'-01'));
$date_1 = date('t/m/Y', strtotime($year.'-'.$month.'-01'));
$month_names = array('', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');

$borders = array(
  'borders' => array(
    'top' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
    ),
    'bottom' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
    )
  )
);

$xls = new PHPExcel();
$xls->setActiveSheetIndex(0);
$xls->getDefaultStyle()->getFont()
    ->setName('Arial')
    ->setSize(10);
$ws = $xls->getActiveSheet();

$i = 1;
$medios_pago = array(
	array(
		'poliza_medio_pago IN ("Directo") and poliza_cobranza_domicilio=0', 
		'Directo', 
		isset($_GET['directo'])
	), 
	array(
		'poliza_medio_pago IN ("Cuponera", "1 Pago Cupon Contado", "6 Cuotas Pago Cupones") and poliza_cobranza_domicilio=0', 
		'Cuponera', 
		isset($_GET['cuponera'])
	), 
	array(
		'poliza_cobranza_domicilio=1', 
		'Cobranza a domicilio',
		isset($_GET['domicilio'])
	), 
);
foreach ($medios_pago as $medio_pago) {
	if (!$medio_pago[2]) continue;
	$ws->mergeCells("A$i:C$i");
	$ws->SetCellValue("A$i", 'Listado de Vencimiento');
	$ws->getStyle("A$i:C$i")->getFont()->setBold(true);
	$i++;
	$ws->mergeCells("A$i:D$i");
	$ws->SetCellValue("A$i", 'Desde '.$date_0.' Hasta: '.$date_1);
	$ws->getStyle("A$i:C$i")->getFont()->setBold(true);
	$i++;
	$ws->mergeCells("A$i:B$i");
	$ws->SetCellValue("A$i", 'Cobrador: '.$medio_pago[1]);
	$ws->getStyle("A$i:B$i")->getFont()->setBold(true);
	$i++;

	// titulo
	$cols = array('Poliza Nº', 'Asegurado', 'Vencimiento', 'Nº Cuota', 'Dominio', 'Marca', $month_names[$month_0], $month_names[$month_1], $month_names[$month], 'TELEFONOS', 'DOMICILIO');

	$ws->fromArray($cols, NULL, "A$i");
	$ws->getStyle("A$i:K$i")->applyFromArray($borders);
	$i++;

	$sql = sprintf("SELECT poliza_numero, CONCAT_WS(' ', cliente_nombre, cliente_apellido, cliente_razon_social), DATE_FORMAT(MAX(cuota_vencimiento), '%%d/%%m/%%Y'), max(cuota_nro), CONCAT_WS('', patente_0, patente_1), CONCAT_WS(' ', automotor_marca_nombre, modelo), GROUP_CONCAT(DISTINCT CONCAT(month(cuota_periodo), ': ', IF(cuota_estado_id=1, 'DEBE',cuota_monto)) SEPARATOR ', '), GROUP_CONCAT(DISTINCT CONCAT_WS(', ', contacto_telefono1, contacto_telefono2, contacto_telefono_laboral, contacto_telefono_alt) SEPARATOR ', '), CONCAT_WS(' ', contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, localidad_nombre, localidad_cp)
		from poliza
		join cuota using (poliza_id)
		join cliente using (cliente_id)
		join contacto USING (cliente_id)
		left join localidad using (localidad_id)
		join automotor using (poliza_id)
		left join automotor_marca using (automotor_marca_id)
		where date_format(cuota_periodo, '%%Y-%%m') between '%s-%s' and '%s-%s' and %s
		group by poliza_id
		having sum(if(cuota_estado_id=1,1,0))>0
		%s
		order by max(cuota_vencimiento) asc", 
		$year_0, sprintf("%02s", $month_0), $year, sprintf("%02s", $month), 
		$medio_pago[0],
		(isset($_GET['vencimientos_anteriores'])?'':"and date_format(max(cuota_vencimiento),'%Y-%m') = '".$year."-".sprintf("%02s", $month)."'")
	);
	
	$res = mysql_query($sql) or die(mysql_error());
	
	while($row = mysql_fetch_array($res)) {
		$ws->SetCellValue("A$i", (string)$row[0]);
		$ws->SetCellValue("B$i", (string)$row[1]);
		$ws->SetCellValue("C$i", (string)$row[2]);
		$ws->SetCellValue("D$i", (string)$row[3]);
		$ws->SetCellValue("E$i", (string)$row[4]);
		$ws->SetCellValue("F$i", (string)$row[5]);
		$val = explode(',', $row[6]);
		foreach($val as $v) {
			list($m,$w) = explode(':', trim($v));
			if (intval($m)==intval($month_0)) 
				$ws->SetCellValue("G$i", (string)$w);
			if (intval($m)==intval($month_1)) 
				$ws->SetCellValue("H$i", (string)$w);
			if (intval($m)==intval($month)) 
				$ws->SetCellValue("I$i", (string)$w);
		}
		$ws->SetCellValue("J$i", (string)$row[7]);
		$ws->SetCellValue("K$i", (string)$row[8]);
		
		$i++;
	}
	$i += 10;
}

$ws->getColumnDimension('B')->setAutoSize(true);
$ws->getColumnDimension('F')->setAutoSize(true);
$ws->getColumnDimension('J')->setAutoSize(true);
$ws->getColumnDimension('K')->setAutoSize(true);

$ws->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$ws->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$ws->getPageSetup()->setFitToPage(true);
$ws->getPageSetup()->setFitToWidth(1);
$ws->getPageSetup()->setFitToHeight(0);

$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Vencimientos.xls"');

$objWriter->save('php://output');