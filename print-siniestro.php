<?php
$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
// Set locale/timezone
setlocale(LC_TIME, 'es_AR');
date_default_timezone_set('America/Argentina/Buenos_Aires');
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
$tz = new DateTimeZone("America/Argentina/Buenos_Aires");
// Require connection
require_once('Connections/connection.php');
// Require DB functions
require_once('inc/db_functions.php');
// Require PDF libraries
require_once('Classes/fpdf/fpdf.php');
require_once('Classes/fpdf/fpdi.php');
class FPDIW extends FPDI {
	function wwrite($x, $y, $text, $size = 7, $style = '', $font = 'Arial') {
		$text = iconv('UTF-8', 'windows-1252', $text);
		$this->SetXY($x, $y);
		$this->SetFont($font, $style, $size);
		$this->Write($size, $text);
	}
}
// Require PDF functions
require_once('inc/pdf_functions.php');	
require_once('inc/mail_functions.php');
?>
<?php
// Obtain URL parameter
$siniestro_id = intval($_GET['id']);
$sql = sprintf('SELECT seguro_id, productor_nombre, poliza_numero FROM siniestros JOIN automotor USING(automotor_id) JOIN poliza USING(poliza_id) JOIN productor_seguro USING(productor_seguro_id) JOIN productor USING(productor_id) WHERE id = %s', $siniestro_id);
$res = mysql_query($sql, $connection) or die(mysql_error());
if (!mysql_num_rows($res)) die ('Siniestro no encontrado');
$row = mysql_fetch_array($res);
list($seguro_id, $productor_nombre, $poliza_numero) = $row;

$siniestro = array();

$siniestro['productor_nombre'] = $productor_nombre;
$siniestro['poliza_numero'] = $poliza_numero;

$sql = sprintf('SELECT `key`, value FROM siniestros_data WHERE siniestro_id = %s', $siniestro_id);
$res = mysql_query($sql) or die(mysql_error());
if (!mysql_num_rows($res)) die ('Siniestro no encontrado');

while ($row = mysql_fetch_array($res)) {
	$siniestro[$row[0]] = $row[1];
}

$siniestro['datos_terceros'] = array();
$sql = sprintf('SELECT id FROM siniestros_datos_terceros WHERE siniestro_id = %s', $siniestro_id);
$res = mysql_query($sql) or die(mysql_error());
while ($row = mysql_fetch_array($res)) {
	$sql2 = sprintf('SELECT `key`, value FROM siniestros_datos_terceros_data WHERE siniestros_datos_terceros_id = %s', $row[0]);
	$res2 = mysql_query($sql2, $connection) or die(mysql_error());
	$siniestro_datos_tercero = array();
	while ($row2 = mysql_fetch_array($res2)) {
		$siniestro_datos_tercero[$row2[0]] = $row2[1];
	}
	$siniestro['datos_terceros'][] = $siniestro_datos_tercero;
}

$siniestro['lesiones_terceros'] = array();
$sql = sprintf('SELECT id FROM siniestros_lesiones_terceros WHERE siniestro_id = %s', $siniestro_id);
$res = mysql_query($sql) or die(mysql_error());
while ($row = mysql_fetch_array($res)) {
	$sql2 = sprintf('SELECT `key`, value FROM siniestros_lesiones_terceros_data WHERE siniestros_lesiones_terceros_id = %s', $row[0]);
	$res2 = mysql_query($sql2, $connection) or die(mysql_error());
	$siniestro_lesiones_tercero = array();
	while ($row2 = mysql_fetch_array($res2)) {
		$siniestro_lesiones_tercero[$row2[0]] = $row2[1];
	}
	$siniestro['lesiones_terceros'][] = $siniestro_lesiones_tercero;
}

$seguros[2] = 'parana';

include('siniestros/'.$seguros[$seguro_id].'.php');

?>