<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Newsletter</title>

		<?php require_once('inc/library.php'); ?>               
		
		<script>
		$(document).ready(function() {
			$('#btnSMS').button().click(function(event) {
				var regex = /[^A-Za-z0-9!?#$%\(\)\*\+, -./:;=@]/;
				if ($('#mensaje').val().match(regex)) {
					alert('Usted ha ingeresado caracteres inválidos');
				}
				else {
					$('#btnSMS').button("option", "disabled", true);
					$.post("send-sms.php", $("#frmSMS").serialize(), function (data) {
						alert(data);
						$('#btnSMS').button("option", "disabled", false);
						$('#mensaje').focus();
					});
					event.preventDefault();
				}
			});
			
			$('#mensaje').keyup(function() {
				var caracteres = 160 - $(this).val().length;
				$('#caracteres').text(caracteres);
			}).keyup();
			
			$('#mensaje').focus();
		});
		
		</script>
	</head>
	<body>
		<div id="divContainer">
        
            <!-- Include Header -->
            <?php include('inc/header.php'); ?>
			<div class="center">
				<form id="frmSMS">
					<p>
						<input type="text" style="width:800px" placeholder="Esribe un mensaje" id="mensaje" name="mensaje" maxlength="160" />
					</p>
					<p>
						Caracteres restantes: <span id="caracteres"></span>
					</p>
					<p>
						Caracteres permitidos: ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!?#$%()*+, -./:;=@ y el espacio
					</p>
					<p>
						<input type="submit" id="btnSMS" value="Enviar" />
					</p>
				</form>
			</div>
    	</div>
	</body>
</html>