<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
require_once('Connections/connection.php');
$poliza_id = intval(mysql_real_escape_string($_GET['id']));
$sql = 'SELECT poliza_estado_id FROM poliza WHERE poliza_id='.$poliza_id;
$res = mysql_query($sql);
list($state) = mysql_fetch_array($res);
?>
<div class="divBoxContainer" style="width:94%">

    <!-- Progress Menu -->
    <?php require_once('inc/progress.php'); ?>
    
  	<!-- Certificates -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Certificados</legend>            
			<input type="button" name="btnCCd" id="btnCCd" value="Enviar C.C. por email" />
			<input type="button" name="btnCCp" id="btnCCp" value="Imprimir C.C." />
            <input type="button" name="btnPE" id="btnPE" value="Pedido de Emisión" />
            <input type="button" name="btnPEMC" id="btnPEMC" value="Pedido de M/C" />            
			<?php if($state==4):?><input type="button" name="btnPR" id="btnPR" value="Pedido de Renovación" /><?php endif;?>
	    </fieldset>
    </form>
    
</div>