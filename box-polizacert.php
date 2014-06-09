<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
require_once('Connections/connection.php');
$poliza_id = intval(mysql_real_escape_string($_GET['id']));
$sql = 'SELECT poliza_estado_id, IF(cliente_tipo_persona=1, CONCAT(IFNULL(cliente_nombre, \'\'), \'\', IFNULL(cliente_apellido, \'\')), cliente_razon_social) as cliente_nombre, cliente_email, seguro_email_emision, seguro_email_emision_vida, seguro_email_patrimoniales_otras, seguro_email_fotos, seguro_email_inspeccion, seguro_email_rastreador, tipo_poliza_id, poliza.subtipo_poliza_id, CONCAT(IF(automotor_carroceria_id=17, "101", ""), patente_0, patente_1) as patente, IF(COUNT(automotor_foto_id) > 0, 1, 0) as automotor_fotos, equipo_rastreo, equipo_rastreo_pedido_nombre, pedido_instalacion FROM poliza JOIN (subtipo_poliza, cliente, productor_seguro, seguro) ON poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND poliza.cliente_id = cliente.cliente_id AND poliza.productor_seguro_id = productor_seguro.productor_seguro_id AND productor_seguro.seguro_id = seguro.seguro_id LEFT JOIN automotor ON poliza.poliza_id = automotor.poliza_id LEFT JOIN equipo_rastreo_pedido ON automotor.equipo_rastreo_pedido_id = equipo_rastreo_pedido.equipo_rastreo_pedido_id LEFT JOIN automotor_foto ON automotor.automotor_id = automotor_foto.automotor_id WHERE poliza.poliza_id='.$poliza_id.' GROUP BY poliza.poliza_id';
$res = mysql_query($sql) or die(mysql_error());
list($state, $cliente_nombre, $cliente_email, $seguro_email_emision, $seguro_email_emision_vida, $seguro_email_patrimoniales_otras, $seguro_email_fotos, $seguro_email_inspeccion, $seguro_email_rastreador, $tipo_poliza_id, $subtipo_poliza_id, $patente, $fotos, $equipo_rastreo, $equipo_rastreo_pedido, $pedido_instalacion) = mysql_fetch_array($res);
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
					<input type="radio" id="doc1" name="type" value="cc" mail="<?=$cliente_email?>" subject='DUSAN ASESORES DE SEGUROS' /><label for="doc1">CC</label>
					<input type="radio" id="doc2" name="type" value="pe" mail="<?=($tipo_poliza_id==3?$seguro_email_emision_vida:($tipo_poliza_id==2&&$subtipo_poliza_id!=6?$seguro_email_patrimoniales_otras:$seguro_email_emision))?>" subject='EMITIR <?=$patente?>' /><label for="doc2">Pedido de Emisión</label>
					<input type="radio" id="doc3" name="type" value="pemc" mail="<?=($tipo_poliza_id==3?$seguro_email_emision_vida:($tipo_poliza_id==2&&$subtipo_poliza_id!=6?$seguro_email_patrimoniales_otras:$seguro_email_emision))?>" subject='M/C <?=$patente?>' /><label for="doc3">Pedido de M/C</label>
					<?php if($state==4):?><input type="radio" id="doc4" name="type" value="pere" mail="<?=$seguro_email_emision?>" subject="RENOVACION <?=$patente?>" /><label for="doc4">Pedido de Renovación</label><?php endif;?>
					<?php if($fotos):?><input type="radio" id="doc5" name="type" value="fotos" mail="<?=$seguro_email_fotos?>" subject="FOTOS <?=$patente?>" /><label for="doc5">Fotos</label><?php endif; ?>
					<?php if($equipo_rastreo): ?><input type="radio" id="doc6" name="type" value="rast" mail="<?=$seguro_email_rastreador?>" subject="<?=$equipo_rastreo_pedido?> EQUIPO DE RASTREO <?=$patente?> - '<?=$cliente_nombre?>'" /><label for="doc6">Equipo de Rastreo</label><?php endif;?>
					<?php if($pedido_instalacion): ?><input type="radio" id="doc7" name="type" value="insp" mail="<?=$seguro_email_inspeccion?>" subject="Pedido de Inspección <?=$patente?> - '<?=$cliente_nombre?>'" /><label for="doc7">Pedido de Inspección</label><?php endif; ?>
				</div>
			</p>
			<p>
				Para: <span id="default-email"></span>
			</p>
			<p>
				<input type="text" name="mail-subject" id="mail-subject" class="ui-widget-content" style="width:50%" placeholder="Asunto" />
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
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
	<p>
		<div style="width:100%;text-align:right"><button class="alert-error" id="btnFinalizar">Finalizar</button></div>
	</p>
    <div style="margin-top:10px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Registro de envíos</legend> 
            <div id="divBoxList" style="min-height:30px">
                Cargando...
            </div>
        </fieldset>
	</div>
</div>
<script>
$('#doc1, #doc2, #doc3, #doc4, #doc5, #doc6, #doc7').change(function() {
	if ($(this).prop('checked')) {
		$('#default-email').text($(this).attr('mail'));
		$('#mail-subject').val($(this).attr('subject'));
	}
});
$('#doc1').click();
</script>