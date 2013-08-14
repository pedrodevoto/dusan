<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	// Error variable
	$error = "";

	// If form was submitted
	if (isset($_POST['Usuario']) && isset($_POST['Clave']) && isset($_POST['Clave_Nueva1'])) {			
		
		// Set variables
		$username=$_POST['Usuario'];
		$password=$_POST['Clave'];
		$newpassword=$_POST['Clave_Nueva1'];

		// If password has a minimum number of characters
		if (strlen($newpassword)>=8) {		
		
			// Check if password is the same								
			if (strtolower($password)!=strtolower($newpassword)) {	
			
				// Check username query
				$query_Recordset1 = sprintf("SELECT usuario_id FROM usuario WHERE usuario_usuario=%s AND usuario_clave=md5(%s) AND usuario_acceso<>'deshabilitado'",
				GetSQLValueString($username, "text"), GetSQLValueString($password, "text"));	
				$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
				$foundUser = mysql_num_rows($Recordset1);
			
				// If user was found
				if ($foundUser) {	
				
					// Update password
					$updateSQL = sprintf("UPDATE usuario SET usuario_clave=md5(%s), usuario_cambioclave=NOW(), usuario_reseteado=0 WHERE usuario_usuario=%s AND usuario_clave=md5(%s) AND usuario_acceso<>'deshabilitado' LIMIT 1",
									GetSQLValueString($newpassword, "text"),				
									GetSQLValueString($username, "text"),
									GetSQLValueString($password, "text"));
					$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die());				
					
					// Redirect to confirmation page
					header("Location: passchange_ok.php");				
					die();
				
				} else {
					$error = "El usuario y/o clave son incorrectos o su usuario se encuentra deshabilitado.";
				} // End: If user was found
		
			} else {
				$error = "La nueva clave es igual a la anterior.";
			} // End: If password is different		
				
		} else {
			$error = "La nueva clave no cumple con los estándares de seguridad.";
		} // End: If password has a minimum number of characters		

	} // End: If form was submitted 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JARVIS - Password Change</title>

<!-- JQuery -->        
<script type="text/javascript" language="javascript" src="jquery/jquery-1.6.2.min.js"></script>

<!-- Validation -->
<script type="text/javascript" language="javascript" src="jquery-plugins/validation/jquery.validate.min.js"></script> 
<script type="text/javascript" language="javascript" src="jquery-plugins/validation/jquery.validate.custom-methods.js"></script>                
<script type="text/javascript" language="javascript" src="media/js/jquery.validate.password.js"></script> 
<link rel="stylesheet" href="media/css/jquery.validate.password.css" /> 

<!-- Base Style -->                     
<link rel="stylesheet" href="media/css/base.css" /> 

<!-- Init form -->                     
<script type="text/javascript">
<!--
$(document).ready(function() {
	
	// Validate signup form on keyup and submit
	var validator = $("#frmChangePass").validate({
		rules: {
			Usuario: {
				required: true
			},
			Clave: {
				required: true
			},			
			Clave_Nueva1: {
				password: "#Usuario"				
			},
			Clave_Nueva2: {
				required: true,
				equalTo: "#Clave_Nueva1",
				notEqualToField: "#Clave"
			}
		},
		messages: {
			Usuario: {
				required: "Ingrese su nombre de usuario"
			},
			Clave: {
				required: "Ingrese su clave anterior"
			},		
			Clave_Nueva2: {
				required: "Ingrese su clave",
				equalTo: "La clave no coincide",
				notEqualToField: "Debe ser diferente a la anterior"
			}
		},
		// The errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.prependTo( element.parent().next() );
		}			
	});	
	
	// Focus to first field
	$('#Usuario').focus();
		
});
//-->
</script>
</head>

<body>
<div id="divLogo"><img src="media/images/logo-big.png" alt="Logo" width="225" height="70" /></div>
<div class="divLoginText">
    <h1 class="titLogin">Cambio de clave</h1>
    <p class="txtLogin">Debe realizar el cambio de contraseña (seguridad) para poder ingresar al sistema.</p>
</div>
<div class="divChangeForm">
    <form name="frmChangePass" id="frmChangePass" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="frmChangePass">
        <table class="tblLogin">
            <tr>
                <td align="right"><label for="Usuario">Usuario *</label></td>
                <td><input type="text" name="Usuario" id="Usuario" maxlength="255" value="<?php if (isset($_POST['Usuario'])) { echo $_POST['Usuario']; } ?>" /></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="right"><label for="Clave">Clave Anterior *</label></td>
                <td><input type="password" name="Clave" id="Clave" maxlength="32" /></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="right"><label for="Clave_Nueva1">Clave Nueva *</label></td>
                <td><input type="password" name="Clave_Nueva1" id="Clave_Nueva1" maxlength="32" /></td>
                <td width="30">&nbsp;</td>
                <td>
                    <div class="password-meter">
                        <div class="password-meter-message">&nbsp;</div>
                        <div class="password-meter-bg">
                            <div class="password-meter-bar"></div>
                        </div>
                    </div>                
                </td>
            </tr>
            <tr>
                <td align="right"><label for="Clave_Nueva2">Repetir Clave Nueva *</label></td>
                <td><input type="password" name="Clave_Nueva2" id="Clave_Nueva2" maxlength="32" /></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                    <input type="submit" name="Change" id="Change" value="Cambiar" />
                </td>
            </tr>
        </table>
    </form>   
</div>
<?php if ($error!="") { ?>
    <div class="divLoginText">
        <p class="txtLoginError">Error: <?php echo $error; ?></p>
    </div>    
<? } ?>
</body>
</html>