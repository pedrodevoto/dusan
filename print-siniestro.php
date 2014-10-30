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
	function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $maxline=0) {
		//Output text with automatic or explicit line breaks, maximum of $maxlines
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r", '', $txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$b=0;
		if($border)
		{
			if($border==1)
			{
				$border='LTRB';
				$b='LRT';
				$b2='LR';
			}
			else
			{
				$b2='';
				if(is_int(strpos($border, 'L')))
					$b2.='L';
				if(is_int(strpos($border, 'R')))
					$b2.='R';
				$b=is_int(strpos($border, 'T')) ? $b2.'T' : $b2;
			}
		}
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$ns=0;
		$nl=1;
		while($i<$nb)
		{
			//Get next character
			$c=$s[$i];
			if($c=="\n")
			{
				//Explicit line break
				if($this->ws>0)
				{
					$this->ws=0;
					$this->_out('0 Tw');
				}
				$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border and $nl==2)
					$b=$b2;
				if ( $maxline  && $nl > $maxline ) 
					return substr($s, $i);
				continue;
			}
			if($c==' ')
			{
				$sep=$i;
				$ls=$l;
				$ns++;
			}
			$l+=$cw[$c];
			if($l>$wmax)
			{
				//Automatic line break
				if($sep==-1)
				{
					if($i==$j)
						$i++;
					if($this->ws>0)
					{
						$this->ws=0;
						$this->_out('0 Tw');
					}
					$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
				}
				else
				{
					if($align=='J')
					{
						$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
						$this->_out(sprintf('%.3f Tw', $this->ws*$this->k));
					}
					$this->Cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border and $nl==2)
					$b=$b2;
				if ( $maxline  && $nl > $maxline ) 
					return substr($s, $i);
			}
			else
				$i++;
		}
		//Last chunk
		if($this->ws>0)
		{
			$this->ws=0;
			$this->_out('0 Tw');
		}
		if($border and is_int(strpos($border, 'B')))
			$b.='B';
		$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
		$this->x=$this->lMargin;
		return '';
	}
	
}
// Require PDF functions
require_once('inc/pdf_functions.php');	
require_once('inc/mail_functions.php');
?>
<?php
// Obtain URL parameter
$siniestro_id = intval($_GET['id']);
$sql = sprintf('SELECT seguro_id, productor_nombre, productor_seguro_codigo, poliza_numero, automotor_id, seguro_email_siniestro, poliza.cliente_id FROM siniestros JOIN automotor USING(automotor_id) JOIN poliza USING(poliza_id) JOIN productor_seguro USING(productor_seguro_id) JOIN productor USING(productor_id) JOIN seguro USING (seguro_id) WHERE id = %s', $siniestro_id);
$res = mysql_query($sql, $connection) or die(mysql_error());
if (!mysql_num_rows($res)) die ('Siniestro no encontrado');
$row = mysql_fetch_array($res);
list($seguro_id, $productor_nombre, $productor_seguro_codigo, $poliza_numero, $automotor_id, $seguro_email_siniestro, $cliente_id) = $row;

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

$seguros = array(
	1 => 'federal',
	2 => 'parana',
);

extract($siniestro);
include('siniestros/'.$seguros[$seguro_id].'.php');

if (isset($pdf)) {
	// OUTPUT
	if (isset($_GET['email'])) {
		$cc = explode(',', urldecode($_GET['email']));
		$subject = $_GET['mail-subject'];
		$to = $seguro_email_siniestro;
		$type = 9;
		$body = false;
		
		$filename = 'temp/'.md5(microtime()).'.pdf';
		$pdf->Output($filename, 'F');
		$attachments = array();
		$attachments[] = array('file'=>$filename, 'name'=>'Siniestro.pdf', 'type'=>'application/pdf');
		
		// fotos
		$sql = sprintf('SELECT automotor_cedula_verde_foto_url FROM automotor_cedula_verde_foto WHERE automotor_id = %s', $automotor_id);
		$res = mysql_query($sql) or die(mysql_error());
		$i = 1;
		while ($foto = mysql_fetch_array($res)) {
			$extension = strtolower(strrchr($foto[0], '.'));
			$mime = mime_content_type($foto[0]);
			$attachments[] = array('file'=>$foto[0], 'name'=>'Foto Cedula Verde '.$i.$extension, 'type'=>$mime);
			$i++;
		}
		$sql = sprintf('SELECT cliente_foto_url FROM cliente_foto WHERE cliente_id = %s', $cliente_id);
		$res = mysql_query($sql) or die(mysql_error());
		$i = 1;
		while ($foto = mysql_fetch_array($res)) {
			$extension = strtolower(strrchr($foto[0], '.'));
			$mime = mime_content_type($foto[0]);
			$attachments[] = array('file'=>$foto[0], 'name'=>'Foto Registro '.$i.$extension, 'type'=>$mime);
			$i++;
		}
		
		echo send_mail($type, $siniestro_id, $to, $subject, $body, $attachments, $cc, NULL, NULL, array('name'=>'Dusan Asesor de Seguros', 'email'=>'siniestros@dusanasegurador.com.ar'));
	}
	else {
		$pdf->Output();
	}
	if (isset($tmp_name)) {
		unlink($tmp_name);
	}
}

?>