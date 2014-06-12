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

function getNameFromNumber($num) {
    $numeric = ($num - 1) % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval(($num - 1) / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2) . $letter;
    } else {
        return $letter;
    }
}

$sql = 'SELECT cliente_apellido, cliente_nombre, cliente_razon_social, cliente_email, cliente_email_alt, MAX(poliza_validez_hasta) as ultima_vigencia, GROUP_CONCAT(DISTINCT CONCAT_WS(", ", contacto_telefono1, contacto_telefono2, contacto_telefono2_compania, contacto_telefono_laboral, contacto_telefono_alt) SEPARATOR "\n") as telefonos, GROUP_CONCAT(DISTINCT CONCAT_WS(" ", contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, ", ", contacto_country, contacto_lote) SEPARATOR "\n") as domicilios, GROUP_CONCAT(DISTINCT localidad_nombre SEPARATOR "\n") as localidades, GROUP_CONCAT(DISTINCT localidad_cp SEPARATOR "\n") as cp FROM cliente LEFT JOIN (contacto, localidad) ON contacto.cliente_id = cliente.cliente_id AND localidad.localidad_id = contacto.localidad_id LEFT JOIN poliza ON poliza.cliente_id = cliente.cliente_id WHERE poliza_estado_id NOT IN (3,4,7) GROUP BY cliente.cliente_id ORDER BY cliente_apellido, cliente_nombre, cliente_razon_social';

$res = mysql_query($sql, $connection) or die(mysql_error());

$xls = new PHPExcel();
$ws = $xls->getActiveSheet();

$cols = array('Apellido', 'Nombre', 'Razón social', 'Email', 'Email alt.', 'Última póliza venció', 'Teléfonos', 'Domicilios', 'Localidades', 'CP');

$ws->fromArray($cols, NULL, 'A1');

$header_start = 'A1';
$header_end = getNameFromNumber(count($cols)).'1';
$fill = array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'startcolor'=>array('rgb'=>'FFFD38'));		
$ws->getStyle($header_start.":".$header_end)->getFill()->applyFromArray($fill);					
$ws->getStyle($header_start.":".$header_end)->getFont()->setBold(true);	

$j = 2;
while($row = mysql_fetch_array($res, MYSQL_NUM)) {
	for($i = 0; $i < count($cols); $i++) {
		$ws->setCellValueExplicitByColumnAndRow($i, $j, strip_tags($row[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
		$ws->getColumnDimension(getNameFromNumber($i+1))->setAutoSize(true);			
	}
	$j++;
}

$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Clientes.xls"');

$objWriter->save('php://output');