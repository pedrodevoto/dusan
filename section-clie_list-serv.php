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
	$query_Recordset1_fields = " cliente.cliente_id, IF(cliente_tipo_persona=1, cliente_nombre, '') as cliente_nombre, IF(cliente_tipo_persona=1, cliente_apellido, cliente_razon_social) as cliente_apellido, cliente_nro_doc, contacto_telefono1, contacto_telefono2, GROUP_CONCAT(sucursal_nombre SEPARATOR ', ') as sucursal";
	$query_Recordset1_tables = " FROM cliente LEFT JOIN (cliente_sucursal, sucursal) ON cliente.cliente_id = cliente_sucursal.cliente_id AND sucursal.sucursal_id = cliente_sucursal.sucursal_id LEFT JOIN contacto ON cliente.cliente_id = contacto.cliente_id AND contacto_default = 1";
	
	$query_Recordset1_where = " WHERE 1";
	
	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1_tables .= ' JOIN usuario_sucursal ON usuario_sucursal.sucursal_id = cliente_sucursal.sucursal_id';
		$query_Recordset1_where .= sprintf(' AND usuario_id = %s', GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
			
	$query_Recordset1_group = " GROUP BY cliente.cliente_id";
	
	// Filter by: cliente_nombre
	if(isset($_GET['cliente_nombre']) && $_GET['cliente_nombre']!=""){	
		$condition = array();
		foreach (explode(' ', $_GET['cliente_nombre']) as $term) {
			$conditions[] = sprintf('(cliente_nombre LIKE %1$s OR cliente_apellido LIKE %1$s)', GetSQLValueString('%' . $term . '%', "text"));
		}
		$query_Recordset1_where .= ' AND ('.implode(' OR ', $conditions).')';
	}	
	// Filter by: cliente_nro_doc
	if(isset($_GET['cliente_nro_doc']) && $_GET['cliente_nro_doc']!=""){	
		$query_Recordset1_where .= sprintf(" AND cliente_nro_doc LIKE %s",GetSQLValueString('%' . $_GET['cliente_nro_doc'] . '%', "text"));
	}	
	// Filter by: sucursal_id
	if(isset($_GET['sucursal_id']) && $_GET['sucursal_id']!=""){	
		$query_Recordset1_where .= sprintf(" AND cliente_sucursal.sucursal_id = %s",GetSQLValueString($_GET['sucursal_id'], "int"));
	}
	// Filter by: cliente_tipo_persona
	if(isset($_GET['cliente_tipo_persona']) && $_GET['cliente_tipo_persona']!=""){	
		$query_Recordset1_where .= sprintf(" AND cliente_tipo_persona = %s",GetSQLValueString($_GET['cliente_tipo_persona'], "int"));
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
			$aColumns = array('cliente_id', 'cliente_apellido', 'cliente_nombre', 'cliente_nro_doc', 'contacto_telefono1', 'contacto_telefono2', 'sucursal', ' ');
	
			/* Indexed column (used for fast and accurate table cardinality) */
			$sIndexColumn = "cliente.cliente_id";		
			
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