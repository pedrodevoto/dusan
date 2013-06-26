<?php require_once('Connections/connection.php'); ?>
<?php require_once('inc/db_functions.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
	session_start();
}
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
	$_SESSION['ADM_PrevUrl'] = $_GET['accesscheck'];
}

// If form was submitted
if (isset($_POST['Usuario'])) {	

	$loginUsername=$_POST['Usuario'];
	$password=$_POST['Clave'];
	$MM_fldUserAuthorization = "usuario_acceso";
	$MM_redirectLoginSuccess = "main.php";
	$MM_redirectLoginFailed = "error.php";
	$MM_redirecttoReferrer = false;

	// Custom login query
	$LoginRS__query=sprintf("SELECT usuario_acceso, (TO_DAYS(NOW())-TO_DAYS(usuario_cambioclave)) AS last_update, usuario_reseteado FROM usuario WHERE usuario_usuario=%s AND usuario_clave=md5(%s)",
	GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 

	$LoginRS = mysql_query($LoginRS__query, $connection) or die(mysql_die());
	$loginFoundUser = mysql_num_rows($LoginRS);

	// If user was found
	if ($loginFoundUser) {		
		$loginStrGroup = mysql_result($LoginRS,0,'usuario_acceso');		
		
		// If user is not disabled
		if ($loginStrGroup!="deshabilitado") {					
		
			// Custom variables		
			$loginIntPasswordReset = intval(mysql_result($LoginRS,0,'usuario_reseteado'));										
			$loginIntLastUpdate = mysql_result($LoginRS,0,'last_update');	
			
			// If password is not temporary, user is not new and last password update happened recently
			if ($loginIntPasswordReset==0 && !is_null($loginIntLastUpdate) && $loginIntLastUpdate<90) {			
			
				if (PHP_VERSION >= 5.1) {
					session_regenerate_id(true);
				} else {
					session_regenerate_id();
				}
				//declare two session variables and assign them
				$_SESSION['ADM_Username'] = $loginUsername;
				$_SESSION['ADM_UserGroup'] = $loginStrGroup;	 
	
				if (isset($_SESSION['ADM_PrevUrl']) && $MM_redirecttoReferrer===true) {
					$MM_redirectLoginSuccess = $_SESSION['ADM_PrevUrl'];
					unset($_SESSION['ADM_PrevUrl']);
				}		
				header("Location: " . $MM_redirectLoginSuccess );			
				die();
			
			} else {
				header("Location: passchange.php");	
				die();		
			} // If password is not temporary and user logged in the last 31 days			
			
		} else {
			header("Location: ". $MM_redirectLoginFailed );
			die();			
		} // End: If user is not disabled			
		
	} else {
		header("Location: ". $MM_redirectLoginFailed );
		die();
	} // End: If user was found
	
} // End: If form was submitted
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin - Login</title>

<!-- JQuery -->        
<script type="text/javascript" language="javascript" src="jquery/jquery-1.6.2.min.js"></script>

<!-- Base Style -->                     
<link rel="stylesheet" href="media/css/base.css" /> 

<script type="text/javascript" language="javascript">
<!--
	function checkIE() {
		if ($.browser.msie && $.browser.version.substr(0,1)<8) {
			alert('Su versión de Internet Explorer no se encuentra soportada.\nHaga click en "OK" para bajar la última versión.');
			window.location = 'http://windows.microsoft.com/es-XL/internet-explorer/products/ie/home';
		}
	}
//-->	
</script>

<script type="text/javascript" language="javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe contener una dirección de e-mail válida.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' debe contener un número.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' debe contener un número entre '+min+' y '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es un campo requerido.\n'; }
    } if (errors) alert('Error:\n\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>

</head>

<body onload="javascript:document.frmLogin.Usuario.focus(); checkIE();">
<div id="divLogo"><img src="media/images/logo-big.png" alt="Logo" width="225" height="70" /></div>
<div class="divLoginForm">
    <form name="frmLogin" id="frmLogin" method="POST" action="<?php echo $loginFormAction; ?>" onsubmit="MM_validateForm('Usuario','','R','Clave','','R');return document.MM_returnValue" class="frmLogin">
        <label for="Usuario">Usuario</label><br />
        <input type="text" name="Usuario" id="Usuario" maxlength="255" style="width:120px" />
        <br />
        <label for="Clave">Clave</label><br />
        <input type="password" name="Clave" id="Clave" maxlength="255" style="width:120px" />
        <br />
        <input type="submit" name="Login" id="Login" value="Ingresar" />
    </form>   
</div>
</body>
</html>