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
	// Main Query
	$colname_Recordset1 = "-1";
	if (isset($_GET['type']) and isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
		$types = array();
		foreach (explode(',', $_GET['type']) as $type) {
			$types[] = intval(mysql_real_escape_string($type));
		}
	}
	$query_Recordset1 = sprintf("SELECT email_type_name, usuario_usuario, email_log_to, DATE_FORMAT(email_log_timestamp, '%%d/%%m/%%y %%H:%%i') as email_log_timestamp FROM email_log JOIN (email_type, usuario) ON email_type.email_type_id = email_log.email_type_id AND usuario.usuario_id = email_log.usuario_id WHERE email_log.email_type_id IN (%s) AND object_id=%s ORDER BY email_log_timestamp ASC",
							implode(',', $types),
							GetSQLValueString($colname_Recordset1, "int"));
							
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
	$output = array();
	for ($i=0; $i<$totalRows_Recordset1; $i++) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$i][$key] = strip_tags($value);
		}		
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>