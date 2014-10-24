<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');		
?>
<?php

	// GENERATE MAIN QUERY (WITHOUT SELECT STATEMENT)
	$query_Recordset1_fields = " siniestros.id as id, fecha.value as fecha, poliza_numero, asegurado_nombre.value as nombre, tipo.value as tipo_siniestro, CONCAT_WS('', patente_0.value, patente_1.value) as patente, CONCAT(DATE_FORMAT(poliza_validez_desde, '%d/%m/%Y'), ' al ', DATE_FORMAT(poliza_validez_hasta, '%d/%m/%Y')) as poliza_vigencia, siniestro_numero.value as siniestro_numero, IF(estudio_juridico.value is not null, 'Sí', 'No') as estudio_juridico";
	$query_Recordset1_tables = " FROM siniestros
		JOIN automotor USING (automotor_id)
		JOIN poliza USING (poliza_id)
		LEFT JOIN siniestros_data asegurado_nombre ON asegurado_nombre.siniestro_id = siniestros.id AND asegurado_nombre.key = 'asegurado_nombre' 
		LEFT JOIN siniestros_data patente_0 ON patente_0.siniestro_id = siniestros.id AND patente_0.key = 'asegurado_patente_0' 
		LEFT JOIN siniestros_data patente_1 ON patente_1.siniestro_id = siniestros.id AND patente_1.key = 'asegurado_patente_1' 
		LEFT JOIN siniestros_data fecha ON fecha.siniestro_id = siniestros.id AND fecha.key = 'fecha_denuncia' 
		LEFT JOIN siniestros_data lugar ON lugar.siniestro_id = siniestros.id AND lugar.key = 'lugar_denuncia' 
		LEFT JOIN siniestros_data siniestro_numero ON siniestro_numero.siniestro_id = siniestros.id AND siniestro_numero.key = 'siniestro_numero'
		LEFT JOIN siniestros_data tipo ON tipo.siniestro_id = siniestros.id AND tipo.key = 'tipo_siniestro'
		LEFT JOIN siniestros_data estudio_juridico ON estudio_juridico.siniestro_id = siniestros.id AND estudio_juridico.key = 'enviado_estudio_juridico'";
	
	$query_Recordset1_where = " WHERE 1";
	$query_Recordset1_having = " HAVING 1";
	
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1_where .= sprintf(" AND sucursal_id IN (SELECT sucursal_id FROM usuario_sucursal WHERE usuario_id = %s)",
			GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	
	// Filter by: poliza_numero
	if(!empty($_GET['poliza_numero'])){	
		$query_Recordset1_having .= sprintf(" AND poliza_numero LIKE %s",GetSQLValueString('%' . $_GET['poliza_numero'] . '%', "text"));
	}
	// Filter by: tipo_siniestro
	if(!empty($_GET['tipo_siniestro'])){	
		$query_Recordset1_having .= sprintf(" AND tipo_siniestro = %s",GetSQLValueString($_GET['tipo_siniestro'], "int"));
	}
	if(!empty($_GET['estudio_juridico'])){	
		$query_Recordset1_having .= sprintf(" AND estudio_juridico = 'Sí'");
	}
?>
<?php

	// DETERMINE PAGE ACTION
	if (isset($_GET['action']) && $_GET['action']!="") {
		$action = $_GET['action'];
	} else {
		$action = "none";		
	}

	switch ($action) {

		// --------------------------------------- VIEW RESULTS ----------------------------------------------
		case "view":

			// COMBINE MAIN QUERY (WITHOUT SELECT STATEMENT)
			$query_Recordset1_base = $query_Recordset1_fields . $query_Recordset1_tables . $query_Recordset1_where;	
	
			/* Array of database columns which should be read and sent back to DataTables */
			$aColumns = array('id', 'fecha', 'poliza_numero', 'nombre', 'tipo_siniestro', 'patente', 'poliza_vigencia', 'siniestro_numero', 'estudio_juridico', ' ');
	
			/* Indexed column (used for fast and accurate table cardinality) */
			$sIndexColumn = "siniestros.id";		
			
			/* Paging */
			$sLimit = "";
			if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength']!='-1'){
				$sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " . mysql_real_escape_string($_GET['iDisplayLength']);
			}
	
			/* Ordering */
			if (isset($_GET['iSortCol_0'])){
				$sOrder = "ORDER BY  ";
				for ($i=0; $i<intval($_GET['iSortingCols']); $i++){
					if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
						$sOrder .= reset(preg_split('/_formateada$/', $aColumns[intval($_GET['iSortCol_'.$i])])) . " " . mysql_real_escape_string($_GET['sSortDir_'.$i]) . ", ";
					}
				}
				$sOrder = substr_replace($sOrder, "", -2);
				if ($sOrder == "ORDER BY"){
					$sOrder = "";
				}
			}
				
			/* Global Filtering */
			$sWhere = "";
			if (isset($_GET['sSearch']) && $_GET['sSearch']!= "") {
				$sWhere = "AND (";
				for ($i=0; $i<count($aColumns); $i++) {
					if ($aColumns[$i]!=' ') {
						if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
							$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
						}
					}
				}
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ')';
			}

			/* Individual column filtering */
			for ($i=0; $i<count($aColumns); $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '') {
					$sWhere .= " AND ".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				}
			}			
		
			/* SQL queries: Get data to display */			
			$query_Recordset1_final = "SELECT SQL_CALC_FOUND_ROWS" . $query_Recordset1_base . " $sWhere $query_Recordset1_group $query_Recordset1_having $sOrder $sLimit";
			$Recordset1 = mysql_query($query_Recordset1_final, $connection) or die(mysql_die());	
		
			/* Data set length after filtering */
			$query_Recordset1_final = "SELECT FOUND_ROWS()";
			$rResultFilterTotal = mysql_query($query_Recordset1_final, $connection) or die(mysql_die());
			$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
			mysql_free_result($rResultFilterTotal);					
			$iFilteredTotal = $aResultFilterTotal[0];	
			
			/* Total data set length */
			$query_Recordset1_final = "SELECT COUNT(".$sIndexColumn.")" . $query_Recordset1_tables . $query_Recordset1_where;
			$rResultTotal = mysql_query($query_Recordset1_final, $connection) or die(mysql_die());
			$aResultTotal = mysql_fetch_array($rResultTotal);
			mysql_free_result($rResultTotal);							
			$iTotal = $aResultTotal[0];
			
			/* Output */		
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
			while ($aRow = mysql_fetch_array($Recordset1)) {
				$row = array();
				for ($i=0; $i<count($aColumns); $i++) {
					/* General output */
					switch ($aColumns[$i]) {
						case ' ':
							$row[] = ' ';						
							break;
						default:
							$row[] = strip_tags($aRow[ $aColumns[$i] ]);						
							break;
					}
				}
				$output['aaData'][] = $row;
			}
			mysql_free_result($Recordset1);		
			echo json_encode( $output );
			
			break;
			
	} // End switch	
	
?>