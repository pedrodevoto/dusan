<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
require_once('Connections/connection.php');
$siniestro_id = intval(mysql_real_escape_string($_GET['id']));
$sql = sprintf('SELECT automotor_id, siniestros.cliente_id, poliza_id, seguro_email_siniestro, poliza_numero, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ""), " ", IFNULL(cliente_nombre, ""))), cliente_razon_social) as cliente_nombre FROM siniestros JOIN automotor USING(automotor_id) JOIN poliza USING(poliza_id) JOIN cliente ON cliente.cliente_id = poliza.cliente_id JOIN productor_seguro USING (productor_seguro_id) JOIN seguro USING (seguro_id) WHERE id=%s', $siniestro_id);
error_log($sql);
$res = mysql_query($sql) or die(mysql_error());
list($automotor_id, $cliente_id, $poliza_id, $seguro_email_siniestro, $poliza_numero, $cliente_nombre) = mysql_fetch_array($res);
?>
<div class="divBoxContainer" style="width:94%">
	<input type="hidden" name="box-automotor_id" id="box-automotor_id" value="<?=$automotor_id?>" />
	<input type="hidden" name="box-cliente_id" id="box-cliente_id" value="<?=$cliente_id?>" />
	<input type="hidden" name="box-poliza_id" id="box-poliza_id" value="<?=$poliza_id?>" />
    
	<!-- Progress Menu -->
    <?php $progress_bar = 'siniestro'; require_once('inc/progress.php'); ?>
    
	<!-- Información de póliza -->
    <div>
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Poliza Seleccionada</legend> 
            <div id="divBoxInfo" style="min-height:90px">
                Cargando...
            </div>
        </fieldset>
    </div>
	
  	<!-- Certificates -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Archivos para imprimir</legend>            
			<input type="button" name="btnDE" id="btnDE" value="Denuncia" />
			<input type="button" name="btnCV" id="btnCV" value="Cédula verde" />
            <input type="button" name="btnRE" id="btnRE" value="Registro" />
	    </fieldset>
    </form>
    <form name="frmBox1" id="frmBox1" class="frmBoxMain" style="margin-top:20px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Envío de denuncia</legend> 
			<p>
				<div id="doc">
					<input type="radio" id="doc1" name="type" value="de" mail="<?=$seguro_email_siniestro?>" subject='PZA: <?=$poliza_numero?> - <?=$cliente_nombre?> - SINIESTRO' /><label for="doc1">Denuncia</label>
				</div>
			</p>
			<p>
				Para: <span id="default-email"></span>
			</p>
			<p>
				<input type="text" name="mail-subject" id="mail-subject" class="ui-widget-content" style="width:50%" placeholder="Asunto" />
			</p>	
			<p>
				<textarea name="mail-cc" id="mail-cc" class="ui-widget-content" style="width:100%" rows="5" placeholder="Direcciones de email (CC), separadas por coma"></textarea>
			</p>
        	<p align="center" style="margin-top:10px">
				<input type="hidden" name="id" value="<?=$siniestro_id?>" />
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
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Fotos cédula verde</legend> 
		<form class="fileForm" id="cedula_verde" suffix="CedulaVerde" action="upload-automotor_foto.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-automotor_cedula_verde_foto">Imagen</label>
		        <input type="file" name="box-automotor_cedula_verde_foto" id="box-automotor_cedula_verde_foto" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir foto"> <span id="fotosLoadingcedula_verde" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>
		<div id="divBoxFotosCedulaVerde" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Fotos registro</legend> 
		<form class="fileForm" id="cliente_foto" suffix="Registro" action="upload-cliente_foto.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-cliente_foto">Imagen</label>
		        <input type="file" name="box-cliente_foto" id="box-cliente_foto" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir foto"> <span id="fotosLoadingcliente_foto" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>
		<div id="divBoxFotosRegistro" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Denuncia policial</legend> 
		<form class="fileForm" id="denuncia_policial" suffix="DenunciaPolicial" action="upload-siniestro_archivo.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-denuncia_policial_archivo">Archivo</label>
		        <input type="file" name="box-denuncia_policial_archivo" id="box-denuncia_policial_archivo" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir archivo"> <span id="fotosLoadingdenuncia_policial" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>	
		<div id="divBoxArchivosDenunciaPolicial">
			Cargando...
		</div>
	</fieldset>
</div>
<script>
$('#doc1').change(function() {
	if ($(this).prop('checked')) {
		$('#default-email').text($(this).attr('mail'));
		$('#mail-subject').val($(this).attr('subject'));
	}
});
$('#doc1').click();
</script>