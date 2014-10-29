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
	if (!empty($_POST["box-automotor_id"])) {
		$automotor_id = mysql_real_escape_string($_POST['box-automotor_id']);
		
		$sql = sprintf('SELECT cliente_id FROM poliza JOIN automotor USING(poliza_id) WHERE automotor_id = %s', $automotor_id);
		$res = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($res);
		$cliente_id = $row[0];
		
		$sql = sprintf('INSERT INTO siniestros (automotor_id, cliente_id, timestamp) VALUES ("%s", "%s", NOW())', $automotor_id, $cliente_id);
		mysql_query($sql, $connection) or die(mysql_error());
		
		$siniestro_id = mysql_insert_id();
		
		if ($siniestro_id) {
			$values = array();
			foreach ($_POST as $k=>$v) {
				if (!preg_match('/^box-/', $k) or $k=='box-automotor_id') {
					continue;
				}
				$key = mysql_real_escape_string(substr($k, 4));
				$val = mysql_real_escape_string(trim($v));
				if ($val=='') continue;
			
				$values[] = sprintf('(%s, "%s", UPPER(TRIM("%s")))', $siniestro_id, $key, $val);
			}
			
			// Info cliente default
			$sql = sprintf("SELECT IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as asegurado_nombre, cliente_sexo as asegurado_sexo, contacto_domicilio as asegurado_calle, contacto_nro as asegurado_altura, localidad_nombre as asegurado_localidad, localidad_cp as asegurado_cp, contacto_telefono1 as asegurado_tel, contacto_telefono2 as asegurado_cel, DATE_FORMAT(cliente_nacimiento, '%%d/%%m/%%Y') as asegurado_fec_nac, cliente_registro as asegurado_registro, cliente_reg_vencimiento as asegurado_registro_venc FROM cliente JOIN contacto ON contacto.cliente_id = cliente.cliente_id AND contacto.contacto_default = 1 JOIN localidad USING(localidad_id) WHERE cliente.cliente_id = %s", $cliente_id);
			
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_assoc($res);
			foreach ($row as $k=>$v) {
				$key = mysql_real_escape_string($k);
				$val = mysql_real_escape_string(trim($v));
				if ($val=='') continue;
			
				$values[] = sprintf('(%s, "%s", UPPER(TRIM("%s")))', $siniestro_id, $key, $val);
			}
			
			// Info automotor default
			$sql = sprintf("SELECT automotor_marca_nombre as asegurado_marca, IFNULL(automotor_version_nombre, IFNULL(automotor_modelo_nombre, modelo)) as asegurado_modelo, ano as asegurado_ano, patente_0 as asegurado_patente_0, patente_1 as asegurado_patente_1, automotor_tipo_nombre as asegurado_tipo, uso as asegurado_uso, nro_motor as asegurado_nro_motor, nro_chasis as asegurado_nro_chasis FROM automotor LEFT JOIN automotor_marca USING (automotor_marca_id) LEFT JOIN automotor_modelo USING (automotor_modelo_id) LEFT JOIN automotor_version USING (automotor_version_id) LEFT JOIN automotor_tipo USING (automotor_tipo_id) WHERE automotor_id = %s", $automotor_id);
			
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_assoc($res);
			foreach ($row as $k=>$v) {
				$key = mysql_real_escape_string($k);
				$val = mysql_real_escape_string(trim($v));
				if ($val=='') continue;
			
				$values[] = sprintf('(%s, "%s", UPPER(TRIM("%s")))', $siniestro_id, $key, $val);
			}
			
			$sql = sprintf('INSERT INTO siniestros_data (`siniestro_id`, `key`, `value`) VALUES %s', implode(', ', $values));
			mysql_query($sql, $connection) or die(mysql_error());
			
			echo $siniestro_id;
		}
	} else {
		die("Error: Acceso denegado.");
	}
?>