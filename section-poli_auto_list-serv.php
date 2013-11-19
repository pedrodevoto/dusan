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
	$query_Recordset1_fields = " poliza.poliza_id, poliza.cliente_id as cliente_id, poliza_numero, patente, seguro_nombre, sucursal_nombre, CONCAT(productor_nombre, ' (', productor_seguro_codigo, ')') as productor_nombre, cliente_nombre, CONCAT(poliza_vigencia, IF(poliza_vigencia='Otra', CONCAT(' (', poliza_vigencia_dias, ')'), '')) as poliza_vigencia, DATE_FORMAT(poliza_validez_desde, '%d/%m/%y') as poliza_validez_desde, DATE_FORMAT(poliza_validez_hasta, '%d/%m/%y') as poliza_validez_hasta, IF(poliza_entregada=1, 'Entregada', IF(poliza_correo=1, 'Enviada', IF(poliza_fecha_recepcion IS NOT NULL, 'Sí', 'No'))) as poliza_estado_entrega, IF(COUNT(endoso_id) > 0, 'ANULADA', poliza_estado_nombre) as poliza_estado_nombre, IF(automotor_carroceria_id=17, 'N/A', IF(COUNT(poliza_foto_id) > 0, 'Sí', 'No')) as poliza_fotos, IF(poliza_medio_pago = 'Directo', IF(COUNT(IF(cuota_vencimiento <= DATE(NOW()) AND cuota_estado = '1 - No Pagado', 1, NULL))=0, 'Sí', 'No'), IF(poliza_medio_pago='Cuponera', 'Cup', IF(poliza_medio_pago='Débito Bancario', 'DC', 'TC'))) AS poliza_al_dia, IF(poliza_medio_pago = 'Directo', GROUP_CONCAT(IF(cuota_vencimiento <= DATE(NOW()) AND cuota_estado = '1 - No Pagado', CONCAT('Cuota número ', cuota_nro, ' (Período: ', DATE_FORMAT(cuota_periodo, '%m/%y'), ', venc: ', IF(DATE(cuota_vencimiento) = DATE(NOW()), 'hoy', DATE_FORMAT(cuota_vencimiento, '%d/%m/%y')), ')'), NULL) SEPARATOR '\n'), '') AS poliza_al_dia_detalle";
	$query_Recordset1_tables = " FROM poliza LEFT JOIN (productor_seguro, seguro, productor, cliente) ON (poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.seguro_id=seguro.seguro_id AND productor_seguro.productor_id=productor.productor_id AND poliza.cliente_id=cliente.cliente_id) JOIN sucursal ON poliza.sucursal_id = sucursal.sucursal_id LEFT JOIN cuota ON cuota.poliza_id = poliza.poliza_id LEFT JOIN poliza_foto ON poliza.poliza_id = poliza_foto.poliza_id JOIN subtipo_poliza ON subtipo_poliza.subtipo_poliza_id = poliza.subtipo_poliza_id LEFT JOIN automotor ON poliza.poliza_id=automotor.poliza_id JOIN poliza_estado on poliza_estado.poliza_estado_id = poliza.poliza_estado_id LEFT JOIN (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1) LEFT JOIN seguro_cobertura_tipo ON automotor.seguro_cobertura_tipo_id = seguro_cobertura_tipo.seguro_cobertura_tipo_id";
	$query_Recordset1_where = " WHERE poliza.subtipo_poliza_id = 6";
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1_where .= sprintf(" AND poliza.sucursal_id IN (SELECT sucursal_id FROM usuario_sucursal WHERE usuario_id = %s)",
			GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	$query_Recordset1_group = " GROUP BY poliza.poliza_id";
	$query_Recordset1_having = " HAVING 1";
	
	// Filter by: poliza_numero
	if(isset($_GET['poliza_numero']) && $_GET['poliza_numero']!=""){	
		$query_Recordset1_where .= sprintf(" AND poliza_numero LIKE %s",GetSQLValueString('%' . $_GET['poliza_numero'] . '%', "text"));
	}
	// Filter by: patente
	if(isset($_GET['patente']) && $_GET['patente']!=""){	
		$query_Recordset1_where .= sprintf(" AND patente LIKE %s",GetSQLValueString('%' . $_GET['patente'] . '%', "text"));
	}
	// Filter by: seguro_nombre
	if(isset($_GET['seguro_nombre']) && $_GET['seguro_nombre']!=""){	
		$query_Recordset1_where .= sprintf(" AND seguro_nombre LIKE %s",GetSQLValueString('%' . $_GET['seguro_nombre'] . '%', "text"));
	}
	// Filter by: sucursal_id
	if(isset($_GET['sucursal_id']) && $_GET['sucursal_id']!=""){	
		$query_Recordset1_where .= sprintf(" AND poliza.sucursal_id = %s",GetSQLValueString($_GET['sucursal_id'], "int"));
	}
	// Filter by: productor_nombre
	if(isset($_GET['productor_nombre']) && $_GET['productor_nombre']!=""){	
		$query_Recordset1_where .= sprintf(" AND productor_nombre LIKE %s",GetSQLValueString('%' . $_GET['productor_nombre'] . '%', "text"));
	}
	// Filter by: cliente_nombre
	if(isset($_GET['cliente_nombre']) && $_GET['cliente_nombre']!=""){	
		$query_Recordset1_where .= sprintf(" AND cliente_nombre LIKE %s",GetSQLValueString('%' . $_GET['cliente_nombre'] . '%', "text"));
	}
	// Filter by: poliza_estado_id
	if(isset($_GET['poliza_estado_id']) && $_GET['poliza_estado_id']!=""){	
		$query_Recordset1_where .= sprintf(" AND poliza.poliza_estado_id = %s",GetSQLValueString($_GET['poliza_estado_id'], "int"));
	}
	// Filter by: poliza_medio_pago
	if(isset($_GET['poliza_medio_pago']) && $_GET['poliza_medio_pago']!=""){	
		$query_Recordset1_where .= sprintf(" AND poliza.poliza_medio_pago = %s",GetSQLValueString($_GET['poliza_medio_pago'], "text"));
	}
	// Filter by: fotos
	if(isset($_GET['fotos']) && $_GET['fotos']!=""){	
		$query_Recordset1_having .= sprintf(" AND poliza_fotos IN %s ", $_GET['fotos']=='1'?'("Sí", "N/A")':'("No")');
	}
	// Filter by: castigado
	if(isset($_GET['castigado']) && $_GET['castigado']!=""){	
		$query_Recordset1_where .= sprintf(" AND automotor.castigado = %s",GetSQLValueString($_GET['castigado'], "int"));
	}
	// Filter by: micro_grabado
	if(isset($_GET['micro_grabado']) && $_GET['micro_grabado']!=""){	
		$query_Recordset1_where .= sprintf(" AND automotor.micro_grabado = %s",GetSQLValueString($_GET['micro_grabado'], "int"));
	}
	// Filter by: seguro_cobertura_tipo_nombre
	if(isset($_GET['seguro_cobertura_tipo_nombre']) && $_GET['seguro_cobertura_tipo_nombre']!=""){	
		$query_Recordset1_where .= sprintf(" AND seguro_cobertura_tipo_nombre = %s",GetSQLValueString($_GET['seguro_cobertura_tipo_nombre'], "text"));
	}
	// Filter by: poliza_al_dia
	if(isset($_GET['poliza_al_dia']) && $_GET['poliza_al_dia']!=""){	
		$query_Recordset1_having .= sprintf(" AND poliza_al_dia = %s", GetSQLValueString($_GET['poliza_al_dia']=='1'?'Sí':'No', "text"));
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
			$aColumns = array('poliza_id', 'cliente_id', 'poliza_numero', 'patente', 'seguro_nombre', 'sucursal_nombre', 'productor_nombre', 'cliente_nombre', 'poliza_vigencia', 'poliza_validez_desde', 'poliza_validez_hasta', 'poliza_estado_entrega', 'poliza_estado_nombre', 'poliza_fotos', 'poliza_al_dia', 'poliza_al_dia_detalle', ' ');
	
			/* Indexed column (used for fast and accurate table cardinality) */
			$sIndexColumn = "poliza.poliza_id";		
			
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
						$sOrder .= $aColumns[intval($_GET['iSortCol_'.$i])] . " " . mysql_real_escape_string($_GET['sSortDir_'.$i]) . ", ";
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
			$query_Recordset1_final = "SELECT COUNT(DISTINCT ".$sIndexColumn.")" . $query_Recordset1_tables . $query_Recordset1_where;
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