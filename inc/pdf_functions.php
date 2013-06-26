<?php
	// Formatting functions
	function formatNumber($number, $dec = 2) {
		return number_format($number, $dec, ',', '.');		
	}
	
	// Text functions
	function formatCB($value, $type) {
		switch ($type) {
			case 'W':
				return ($value == 1) ? 'Si' : 'No';
				break;
			case 'X':
				return ($value == 1) ? 'X' : '';
				break;				
		}
	}
	function trimText($text, &$pdf, $maxwidth) {
		$text = iconv('UTF-8', 'windows-1252', $text);
		$length = $pdf->GetStringWidth($text);
		while ($length > $maxwidth) {
			$text = substr($text, 0, -1);
			$length = $pdf->GetStringWidth($text);					
		}
		return $text;				
	}
	function printText($text, &$pdf, $maxwidth, $lineheight, $align = 'L') {
		$text = trimText($text, $pdf, $maxwidth);
		$pdf->Cell(0, $lineheight, $text, 0, 2, $align);				
	}
	
	// DB functions
	function getNextPayment ($poliza_id, $cuota_id) {
		global $connection;	
		$query_Recordset1 = sprintf("SELECT * FROM cuota WHERE cuota.poliza_id=%s AND cuota.cuota_id=%s",
								GetSQLValueString($poliza_id, "int"),
								GetSQLValueString($cuota_id + 1, "int"));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		mysql_free_result($Recordset1);
		if ($totalRows_Recordset1 === 1) {
			return $row_Recordset1;
		} else {
			return NULL;
		}
	}
?>