<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<div class="divBoxContainer" style="width:94%">

    <!-- Progress Menu -->
    <?php require_once('inc/progress.php'); ?>
    
  	<!-- Certificates -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Certificados</legend>            
			<input type="button" name="btnCC" id="btnCC" value="Constancia de Cobertura" />
            <input type="button" name="btnPE" id="btnPE" value="Pedido de Emisión" />
            <input type="button" name="btnPEMC" id="btnPEMC" value="Pedido de Emisión MC" />            
	    </fieldset>
    </form>
    
</div>