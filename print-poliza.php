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

	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	// Require PDF libraries
	require_once('Classes/fpdf/fpdf.php');
	require_once('Classes/fpdf/fpdi.php');
	// Require PDF functions
	require_once('inc/pdf_functions.php');	
	require_once('inc/mail_functions.php');
?>
<?php
	// Obtain URL parameter
	$poliza_id = intval($_GET['id']);
	
	// Recordset: Main
	$query_Recordset1 = sprintf("SELECT *, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre FROM poliza JOIN (subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro) ON (poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id) LEFT JOIN (contacto, localidad) ON (poliza.cliente_id=contacto.cliente_id AND contacto_default=1 AND localidad.localidad_id = contacto.localidad_id) JOIN sucursal ON poliza.sucursal_id = sucursal.sucursal_id LEFT JOIN (poliza_plan, poliza_pack) ON poliza.poliza_plan_id = poliza_plan.poliza_plan_id AND poliza.poliza_pack_id = poliza_pack.poliza_pack_id
									WHERE poliza.poliza_id=%s",
									$poliza_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	// If record not found
	if ($totalRows_Recordset1 !== 1) {
		die("Error: Poliza no encontrada.");
	}	
	// If no contact info found
	if (is_null($row_Recordset1['contacto_domicilio']) || is_null($row_Recordset1['contacto_nro'])) {
		die("Error: El cliente no tiene un contacto primario asignado.");
	}
	
	$endoso['anulacion'] = NULL;
	if (isset($_GET['endoso_id']) && $_GET['endoso_id']!='') {
		$endoso_id = mysql_real_escape_string($_GET['endoso_id']);
		$sql = sprintf("SELECT endoso_tipo_nombre, endoso_cuerpo, IF(endoso_tipo_grupo_id=1, 1, 0) AS anulacion, endoso_fecha_pedido FROM endoso JOIN endoso_tipo ON endoso_tipo.endoso_tipo_id = endoso.endoso_tipo_id WHERE endoso_id=%s", $endoso_id);
		$res = mysql_query($sql) or die(mysql_error());
		$endoso = mysql_fetch_assoc($res) or die('No se encontró el endoso.');
	}
	
	$row_Recordset1['poliza_pago_detalle'] = Encryption::decrypt($row_Recordset1['poliza_pago_detalle']);

	if (file_exists('print-poliza/'.$row_Recordset1['subtipo_poliza_tabla'].'.php')) 
		include('print-poliza/'.$row_Recordset1['subtipo_poliza_tabla'].'.php');
	else
		die("Error: Subtipo no habilitado.");

	// Free Recordset: Main
	mysql_free_result($Recordset1);			
?>