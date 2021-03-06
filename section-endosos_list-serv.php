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
	$query_Recordset1_fields = " endoso_id, poliza.cliente_id as cliente_id, poliza_numero, CONCAT(endoso_tipo_grupo_nombre, ' (', endoso_tipo_nombre, ')') as endoso_tipo, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre, seguro_nombre, sucursal_nombre, DATE_FORMAT(endoso_fecha_pedido, '%d/%m/%y') as endoso_fecha_pedido_formateada, IF(endoso_completo = 1, 'SÍ', 'NO') as endoso_completo";
	$query_Recordset1_tables = " FROM endoso JOIN (poliza, productor_seguro, seguro, endoso_tipo, endoso_tipo_grupo, cliente, sucursal) ON (poliza.poliza_id = endoso.poliza_id AND productor_seguro.productor_seguro_id = poliza.productor_seguro_id AND productor_seguro.seguro_id = seguro.seguro_id AND endoso_tipo.endoso_tipo_id = endoso.endoso_tipo_id AND endoso_tipo.endoso_tipo_grupo_id = endoso_tipo_grupo.endoso_tipo_grupo_id AND poliza.cliente_id = cliente.cliente_id AND poliza.sucursal_id = sucursal.sucursal_id) ";
	
	$query_Recordset1_where = " WHERE 1";
	
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1_where .= sprintf(" AND poliza.sucursal_id IN (SELECT sucursal_id FROM usuario_sucursal WHERE usuario_id = %s)",
			GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	
	// Filter by: poliza_numero
	if(isset($_GET['poliza_numero']) && $_GET['poliza_numero']!=""){	
		$query_Recordset1_where .= sprintf(" AND poliza_numero LIKE %s",GetSQLValueString('%' . $_GET['poliza_numero'] . '%', "text"));
	}
	
	// Filter by: endoso_completo
	if(isset($_GET['endoso_completo'])){	
		if ($_GET['endoso_completo']!="") {
			$query_Recordset1_where .= sprintf(" AND endoso_completo = %s",GetSQLValueString($_GET['endoso_completo'], "int"));
		}
	}
	else {
		$query_Recordset1_where .= " AND endoso_completo = 0";
	}
	// Filter by: seguro_nombre
	if(isset($_GET['seguro_id']) && $_GET['seguro_id']!=""){	
		$query_Recordset1_where .= sprintf(" AND productor_seguro.seguro_id = %s",GetSQLValueString($_GET['seguro_id'], "int"));
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
			$aColumns = array('endoso_id', 'cliente_id', 'poliza_numero', 'endoso_tipo', 'cliente_nombre', 'seguro_nombre', 'sucursal_nombre', 'endoso_fecha_pedido_formateada', 'endoso_completo', ' ');
	
			/* Indexed column (used for fast and accurate table cardinality) */
			$sIndexColumn = "endoso.endoso_id";		
			
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
			$query_Recordset1_final = "SELECT SQL_CALC_FOUND_ROWS" . $query_Recordset1_base . " $sWhere $sOrder $sLimit";
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