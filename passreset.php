<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	// Require general functions
	require_once('inc/general_functions.php');	
	// Require global variables
	require_once('inc/globals.php');			
?>
<?php
	// Error variable
	$error = "";
	
	// If form was submitted
	if (isset($_POST['Usuario']) && isset($_POST['Email'])) {	
	
		// Set variables
		$username=$_POST['Usuario'];
		$email=$_POST['Email'];

		// Check username query
		$query_Recordset1 = sprintf("SELECT usuario_id FROM usuario WHERE usuario_usuario=%s AND usuario_email=%s AND usuario_acceso<>'deshabilitado'",
		GetSQLValueString($username, "text"), GetSQLValueString($email, "text"));	
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$foundUser = mysql_num_rows($Recordset1);
		
		// If user was found
		if ($foundUser) {
		
			// Generate password
			$genpassword = createPassword(8);

			// Update password
			$updateSQL = sprintf("UPDATE usuario SET usuario_clave=md5(%s), usuario_reseteado=1 WHERE usuario_usuario=%s AND usuario_email=%s AND usuario_acceso<>'deshabilitado' LIMIT 1",
							GetSQLValueString($genpassword, "text"), 
							GetSQLValueString($username, "text"),
							GetSQLValueString($email, "text"));
			$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());		

			// Send e-mail			
			$headers = "MIME-Version: 1.0\n";			
			$headers .= "Content-type: text/plain; charset=UTF-8\n";								
			$headers .= "Content-Transfer-Encoding: quoted-printable\n";			
			$headers .= "From: \"".$usuario_email_fromname."\" <".$usuario_email_fromad.">\n";			
						$subject = $usuario_system_name." - Generacion de clave";			
			$body = "Su contraseña provisional es: ".$genpassword."\n\n";			
			$body .= "Al ingresar el sistema le solicitará cambiar la contraseña por una de su preferencia.\n\n";
			$body .= $usuario_system_name;		
			$body = str_replace("=0A","\n",imap_8bit($body));			
			mail($email, $subject, $body, $headers); 			
						
			// Redirect to confirmation page
			header("Location: passreset_instructions.php");
			die();
						
		} else {
			$error = "El usuario y/o e-mail son incorrectos o su usuario se encuentra deshabilitado.";
		} // End: If user was found		
	
	} // End: If form was submitted 			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin - Password Reset</title>

<!-- JQuery -->        
<script type="text/javascript" language="javascript" src="jquery/jquery-1.6.2.min.js"></script>

<!-- Validation -->
<script type="text/javascript" language="javascript" src="jquery-plugins/validation/jquery.validate.min.js"></script>                
<script type="text/javascript" language="javascript" src="media/js/messages_es.js"></script>

<!-- Base Style -->                     
<link rel="stylesheet" href="media/css/base.css" /> 

<!-- Init form -->                     
<script type="text/javascript">
<!--
$(document).ready(function() {
	
	// Focus to first field
	$('#Usuario').focus();
	
});
//-->
</script>

<!-- Validation (basic) -->
<script type="text/javascript">
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

<body>
<div id="divLogo"><img src="media/images/logo-big.png" alt="Logo" width="225" height="70" /></div>
<div class="divLoginText">
    <h1 class="titLogin">Restablecer contraseña</h1>
    <p class="txtLogin">Ingrese su información de usuario para iniciar el restablecimiento de contraseña.</p>
</div>
<div class="divLoginForm">
    <form name="frmReset" id="frmReset" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="MM_validateForm('Usuario','','R','Email','','RisEmail');return document.MM_returnValue" class="frmLogin">
        <label for="Usuario">Usuario</label><br />
        <input type="text" name="Usuario" id="Usuario" maxlength="255" style="width:160px" />
        <br />
        <label for="Email">E-mail</label><br />
        <input type="text" name="Email" id="Email" maxlength="255" style="width:160px" />
        <br />
        <input type="submit" name="Reset" id="Reset" value="Restablecer" />
    </form>   
</div>
<?php if ($error!="") { ?>
    <div class="divLoginText">
        <p class="txtLoginError">Error: <?php echo $error; ?></p>
    </div>    
<? } ?>
</body>
</html>