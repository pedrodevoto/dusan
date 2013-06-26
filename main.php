<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/db_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin - Main</title>
    <?php require_once('inc/library.php'); ?>                             
</head>
<body>
    <div id="divContainer">
        <!-- Include Header -->
        <?php include('inc/header.php'); ?>    
        <div id="divMain">
            <p class="txtMain">Bienvenido al panel de administración, <strong><?php echo($_SESSION['ADM_Username']); ?></strong>.</p>                      
        </div>
	</div>
</body>
</html>