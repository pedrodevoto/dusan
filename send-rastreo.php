<?php
	$MM_authorizedUsers = "administrativo,master";
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

if ((isset($_GET["id"])) && ($_GET["id"] != "")) {		
	$poliza_id = intval(mysql_real_escape_string($_GET['id']));
	
	$sql = sprintf('SELECT date_format(now(), "%%d/%%m/%%y"), equipo_rastreo_pedido_nombre, poliza_numero, cliente_nombre, cliente_nro_doc, concat(contacto_domicilio, " ", contacto_nro, " ", IFNULL(contacto_piso, ""), IFNULL(contacto_dpto, "")), concat(contacto_localidad, " ", contacto_cp), contacto_telefono1, valor_total, automotor_marca_nombre, modelo, ano, patente, nro_motor, nro_chasis, productor_nombre FROM poliza JOIN (automotor, equipo_rastreo_pedido, cliente, contacto, automotor_marca, productor_seguro, productor) ON poliza.poliza_id = automotor.poliza_id AND automotor.equipo_rastreo_pedido_id = equipo_rastreo_pedido.equipo_rastreo_pedido_id AND poliza.cliente_id = cliente.cliente_id AND cliente.cliente_id = contacto.cliente_id AND automotor.automotor_marca_id = automotor_marca.automotor_marca_id AND poliza.productor_seguro_id = productor_seguro.productor_seguro_id AND productor_seguro.productor_id = productor.productor_id WHERE poliza.poliza_id = %s', $poliza_id);
	$res = mysql_query($sql, $connection) or die(mysql_error());

	$sql = sprintf('SELECT seguro_email_rastreador, patente from poliza join (productor_seguro, seguro, automotor) on productor_seguro.productor_seguro_id = poliza.productor_seguro_id and seguro.seguro_id = productor_seguro.seguro_id and poliza.poliza_id = automotor.poliza_id where poliza.poliza_id = %s', $poliza_id);
	$email = mysql_query($sql, $connection) or die(mysql_error());
	list($email, $patente) = mysql_fetch_array($email);

	$xls = new PHPExcel();
	$ws = $xls->getActiveSheet();

	$cols = array('FECHA', 'TipoOperacion', 'Póliza', 'ASEGURADO', 'DNI', 'DOMICILIO', 'LOCALIDAD', 'TELEFONO', 'SUMA ASEGURADA', 'MARCA', 'MODELO', 'AÑO', 'PATENTE', 'MOTOR', 'CHASIS', 'PRODUCTOR');

	$ws->fromArray($cols, NULL, 'A1');

	$header_start = 'A1';
	$header_end = getNameFromNumber(count($cols)).'1';
	$fill = array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'startcolor'=>array('rgb'=>'FFFD38'));		
	$ws->getStyle($header_start.":".$header_end)->getFill()->applyFromArray($fill);					
	$ws->getStyle($header_start.":".$header_end)->getFont()->setBold(true);	

	
	while($row = mysql_fetch_array($res, MYSQL_NUM)) {
		for($i = 0; $i < count($cols); $i++) {
			$ws->setCellValueExplicitByColumnAndRow($i, 2, strip_tags($row[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
			$ws->getColumnDimension(getNameFromNumber($i+1))->setAutoSize(true);			
		}
	}

	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
	$filename = 'temp/'.uniqid().'.xls';
	$objWriter->save($filename);
	
	$cc = explode(',', urldecode($_GET['email']));
	$to = $email;
	$subject = $_GET['mail-subject'];
	$body = 'Se solicita instalacion de equipo de rastreo al vehiculo con dominio '.$patente.'. Muchas gracias';
	$attachments = array();
	$attachments[] = array('file'=>$filename, 'name'=>'Formulario de pedido de equipo de rastreo.xls', 'type'=>'application/vnd.ms-excel');

	echo send_mail(8, $poliza_id, $to, $subject, FALSE, $attachments, $cc, $body);
}
?>