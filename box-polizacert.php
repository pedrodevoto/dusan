<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
require_once('Connections/connection.php');
$poliza_id = intval(mysql_real_escape_string($_GET['id']));
$sql = 'SELECT poliza_estado_id, cliente_email, seguro_email_emision FROM poliza JOIN (cliente, productor_seguro, seguro) ON poliza.cliente_id = cliente.cliente_id AND poliza.productor_seguro_id = productor_seguro.productor_seguro_id AND productor_seguro.seguro_id = seguro.seguro_id WHERE poliza_id='.$poliza_id;
$res = mysql_query($sql);
list($state, $cliente_email, $seguro_email_emision) = mysql_fetch_array($res);
?>
<div class="divBoxContainer" style="width:94%">

    <!-- Progress Menu -->
    <?php require_once('inc/progress.php'); ?>
    
  	<!-- Certificates -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Ver Certificados</legend>            
			<input type="button" name="btnCCd" id="btnCCd" value="CC para email" />
			<input type="button" name="btnCCp" id="btnCCp" value="CC para imprimir" />
            <input type="button" name="btnPE" id="btnPE" value="Pedido de Emisión" />
            <input type="button" name="btnPEMC" id="btnPEMC" value="Pedido de M/C" />            
			<?php if($state==4):?><input type="button" name="btnPR" id="btnPR" value="Pedido de Renovación" /><?php endif;?>
	    </fieldset>
    </form>
    <form name="frmBox1" id="frmBox1" class="frmBoxMain" style="margin-top:20px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Enviar por email</legend> 
			<p>
				<div id="doc">
					<input type="radio" id="doc1" name="type" value="cc" /><label for="doc1">CC</label>
					<input type="radio" id="doc2" name="type" value="pe" /><label for="doc2">Pedido de Emisión</label>
					<input type="radio" id="doc3" name="type" value="pemc" /><label for="doc3">Pedido de M/C</label>
					<?php if($state==4):?><input type="radio" id="doc4" name="type" value="pere" /><label for="doc4">Pedido de Renovación</label><?php endif;?>
				</div>
			</p>
			<p>
				Para: <span id="default-email"></span>
			</p>	
			<p>
				<textarea name="email" id="email" class="ui-widget-content" style="width:100%" rows="5" placeholder="Direcciones de email (CC), separadas por coma"></textarea>
			</p>
        	<p align="center" style="margin-top:10px">
				<input type="hidden" name="id" value="<?=$poliza_id?>" />
				<input type="submit" name="btnBox1" id="btnBox1" value="Enviar email" />
			</p>
		</fieldset>
	</form>
    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>
<script>
$('#doc1').change(function() {
	if ($(this).prop('checked')) {
		$('#default-email').text('<?=$cliente_email?>');
	}
});
$('#doc2, #doc3, #doc4').change(function() {
	if ($(this).prop('checked')) {
		$('#default-email').text('<?=$seguro_email_emision?>');
	}
});
$('#doc1').click();
</script>