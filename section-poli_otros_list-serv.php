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
	$query_Recordset1_fields = " poliza.poliza_id, poliza_numero, subtipo_poliza_nombre, seguro_nombre, productor_nombre, cliente_nombre, poliza_vigencia, poliza_validez_desde, poliza_validez_hasta, subtipo_poliza_tabla, poliza_estado, IF(poliza_medio_pago = 'Directo', IF(COUNT(IF(cuota_vencimiento <= DATE(NOW()) AND cuota_estado = '1 - No Pagado', 1, NULL))=0, 'Sí', 'No'), IF(poliza_medio_pago='Cuponera', 'Cup', IF(poliza_medio_pago='Débito Bancario', 'DC', 'TC'))) AS poliza_al_dia, IF(poliza_medio_pago = 'Directo', GROUP_CONCAT(IF(cuota_vencimiento <= DATE(NOW()) AND cuota_estado = '1 - No Pagado', CONCAT('Cuota número ', cuota_nro, ' (Período: ', DATE_FORMAT(cuota_periodo, '%m/%y'), ', venc: ', DATE_FORMAT(cuota_vencimiento, '%d/%m/%y'), ')'), NULL) SEPARATOR '\n'), '') AS poliza_al_dia_detalle";
	$query_Recordset1_tables = " FROM poliza JOIN (productor_seguro, seguro, productor, subtipo_poliza, cliente) ON (poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.seguro_id=seguro.seguro_id AND productor_seguro.productor_id=productor.productor_id AND poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND poliza.cliente_id=cliente.cliente_id) LEFT JOIN cuota ON cuota.poliza_id = poliza.poliza_id";
	$query_Recordset1_where = " WHERE  poliza.subtipo_poliza_id != 6";
		
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1_where .= sprintf(" AND poliza.sucursal_id IN (SELECT sucursal_id FROM usuario_sucursal WHERE usuario_id = %s)",
			GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	$query_Recordset1_group = " GROUP BY poliza.poliza_id";
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
			$aColumns = array('poliza_id', 'poliza_numero', 'subtipo_poliza_nombre', 'seguro_nombre', 'productor_nombre', 'cliente_nombre', 'poliza_vigencia', 'poliza_validez_desde', 'poliza_validez_hasta', 'subtipo_poliza_tabla', 'poliza_estado', 'poliza_anulada', ' ');
	
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
			$query_Recordset1_final = "SELECT SQL_CALC_FOUND_ROWS" . $query_Recordset1_base . " $sWhere $query_Recordset1_group $sOrder $sLimit";
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
							/* Custom: Transform results */
							if ($aColumns[$i] === 'subtipo_poliza_tabla') {
								$query_Recordset2 = sprintf("SELECT IF(COUNT(*)>0,'Si','No') AS total FROM %s WHERE poliza_id=%s",
														$aRow[$i],
														$aRow[0]);
								$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
								$row_Recordset2 = mysql_fetch_assoc($Recordset2);
								mysql_free_result($Recordset2);
								$row[] = $row_Recordset2['total'];
							} else {
								$row[] = strip_tags($aRow[ $aColumns[$i] ]);						
							}		
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