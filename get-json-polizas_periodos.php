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
$sql = "set lc_time_names = 'es_AR'";
mysql_query($sql);

$sql = "SELECT date_format(poliza_validez_desde, '%Y-%m'), concat_ws(' ', monthname(poliza_validez_desde), year(poliza_validez_desde)) from poliza where year(poliza_validez_desde) >= 2014 group by date_format(poliza_validez_desde, '%m-%Y') order by poliza_validez_desde desc";	

$res = mysql_query($sql) or die(mysql_error());

$output = array();
while ($row=mysql_fetch_array($res)) {
	$output[] = array(strip_tags($row[0]), $row[1]);
}
echo json_encode($output);

mysql_free_result($res);
?>
