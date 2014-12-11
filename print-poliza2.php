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
	function wwrite($x, $y, $text, $size = 9, $style = '', $font = 'Arial') {
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
if (!empty($_GET['id'])) {
	$poliza_id = mysql_real_escape_string($_GET['id']);
	
	$sql = sprintf("SELECT *, TRIM(CONCAT_WS(' ', cliente_apellido, cliente_nombre, cliente_razon_social)) as cliente_nombre, COUNT(DISTINCT IF(cuota_pfc=1, cuota_id, null)) as cuota_pfc FROM poliza JOIN (subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro, cuota) ON (poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id AND cuota.poliza_id = poliza.poliza_id) LEFT JOIN contacto ON poliza.cliente_id=contacto.cliente_id AND contacto_default=1 LEFT JOIN localidad ON localidad.localidad_id = contacto.localidad_id JOIN sucursal ON poliza.sucursal_id = sucursal.sucursal_id LEFT JOIN (poliza_plan, poliza_pack) ON poliza.poliza_plan_id = poliza_plan.poliza_plan_id AND poliza.poliza_pack_id = poliza_pack.poliza_pack_id
									WHERE poliza.poliza_id=%s GROUP BY poliza.poliza_id", GetSQLValueString($_GET['id'], 'int'));
	$res = mysql_query($sql) or die(mysql_error());
	if ($row = mysql_fetch_assoc($res)) {
		if (is_null($row['contacto_id'])) {
			die("Error: el cliente no tiene un contacto primario asignado.");
		}
		$endoso['anulacion'] = NULL;
		if (!empty($_GET['endoso_id'])) {
			$sql = sprintf("SELECT endoso_tipo_nombre, endoso_cuerpo, IF(endoso_tipo_grupo_id=1, 1, 0) AS anulacion, endoso_fecha_pedido FROM endoso JOIN endoso_tipo ON endoso_tipo.endoso_tipo_id = endoso.endoso_tipo_id WHERE endoso_id=%s", GetSQLValueString($_GET['endoso_id'], 'int'));
			$res = mysql_query($sql) or die(mysql_error());
			$endoso = mysql_fetch_assoc($res) or die('No se encontró el endoso.');
		}
		$row['poliza_pago_detalle'] = Encryption::decrypt($row['poliza_pago_detalle']);
		
		if (file_exists('print-poliza2/'.$row['subtipo_poliza_tabla'].'.php')) 
			include('print-poliza2/'.$row['subtipo_poliza_tabla'].'.php');
		else
			// die("Error: Subtipo no habilitado.");
			include('print-poliza.php');
	}
	else {
		die('Error: póliza no encontrada');
	}
}