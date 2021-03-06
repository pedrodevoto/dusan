<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	if (!empty($_POST["box-siniestro_id"])) {
		
		$siniestro_id = mysql_real_escape_string($_POST['box-siniestro_id']);
		$sql = sprintf('DELETE FROM siniestros_data WHERE siniestro_id = "%s"', $siniestro_id);
		if (!empty($_POST['limited'])) {
			
			$sql .= ' AND `key` IN ("automotor_id", "asegurado_registro", "asegurado_registro_venc", "siniestro_numero", "fecha_compania", "fecha_denuncia", "fecha_ocurrencia", "tipo_siniestro", "pagado", "cerrado", "enviado_estudio_juridico", "fecha_enviado_estudio_juridico", "compania_tercero", "forma_pago", "cobrado", "siniestro_id")';
		}
		else {
			$sql .= ' AND `key` NOT IN ("siniestro_numero", "fecha_compania", "fecha_denuncia", "fecha_ocurrencia", "tipo_siniestro", "pagado", "cerrado", "enviado_estudio_juridico", "fecha_enviado_estudio_juridico", "compania_tercero", "forma_pago", "cobrado", "siniestro_id", "asegurado_nombre", "asegurado_sexo", "asegurado_calle", "asegurado_altura", "asegurado_localidad", "asegurado_provincia", "asegurado_cp", "asegurado_tel", "asegurado_cel", "asegurado_fecha_nac", "asegurado_registro", "asegurado_registro_venc", "asegurado_marca", "asegurado_modelo", "asegurado_tipo", "asegurado_uso", "asegurado_ano", "asegurado_patente_0", "asegurado_patente_1", "asegurado_nro_motor", "asegurado_nro_chasis")';
		}
		mysql_query($sql, $connection) or die(mysql_error());
				
		if ($siniestro_id) {
			$values = array();
			foreach ($_POST as $k=>$v) {
				if (!preg_match('/^box-/', $k) or $k=='box-poliza_id' or $k=='box-siniestro_id' or preg_match('/^box-evento/', $k)) {
					continue;
				}
				$key = mysql_real_escape_string(substr($k, 4));
				if (preg_match('/-noupper$/', $k)) 
					$val = mysql_real_escape_string(trim($v));
				else
					$val = mysql_real_escape_string(mb_strtoupper(trim($v), 'UTF-8'));
				if ($val=='') continue;
			
				$values[] = sprintf('(%s, "%s", "%s")', $siniestro_id, $key, $val);
			}
		
			$sql = sprintf('INSERT INTO siniestros_data (`siniestro_id`, `key`, `value`) VALUES %s', implode(', ', $values));
			mysql_query($sql, $connection) or die(mysql_error());
			
			echo "El registro ha sido actualizado.";
		}
	} else {
		die("Error: Acceso denegado.");
	}
?>