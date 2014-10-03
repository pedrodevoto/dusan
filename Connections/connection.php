<?php
	// Custom die function
	function mysql_die() {
		// echo "Database error.";
		error_log(mysql_error());
		echo "Error: ".mysql_error();
	}

	# FileName="Connection_php_mysql.htm"
	# Type="MYSQL"
	# HTTP="true"
	
	$hostname_connection = "internal-db.s162167.gridserver.com";
	$hostname_connection = "localhost";
	$database_connection = "db162167_dusan";
	$username_connection = "db162167";
	$password_connection = "Gqv7Q8Kx19Vz";
	$connection = mysql_pconnect($hostname_connection, $username_connection, $password_connection) or die(mysql_die()); 
	
	mysql_select_db($database_connection, $connection);
	mysql_query("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");	
	mysql_query("SET CHARACTER SET utf8");
	mysql_query("SET NAMES utf8"); 
	mysql_query("SET time_zone = '-03:00'");			
?>