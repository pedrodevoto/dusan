<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  
	<?php $progress_bar = 'siniestro'; require_once('inc/progress.php'); ?>
	
  	<!-- Siniestro -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<span style="text-align:center"><h1>INFORMACIÓN DEL SINIESTRO</h1></span>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Siniestro</legend>  
            <p>
                <label for="box-productor_seguro_codigo"><b>Código de productor ^</b></label>
                <input type="text" name="productor_seguro_codigo" id="box-productor_seguro_codigo" maxlength="255" class="ui-widget-content" style="width:50px" readonly />
            </p>
			<div style="width:50%;float:left">
				<p>
					<label for="box-siniestro_numero"><b>N° DE SINIESTRO</b></label>
					<input type="text" name="box-siniestro_numero" id="box-siniestro_numero" maxlength="255" class="ui-widget-content" style="width:80px" />
				</p>
				<p>
					<label for="box-fecha_compania">Fecha de ingreso a la compañía</label>
					<input type="text" name="box-fecha_compania" id="box-fecha_compania" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
				</p>
			</div>
			
			<div style="width:50%;float:left">
	            <p>
	                <label for="box-fecha_denuncia">Fecha de ingreso de siniestro</label>
	                <input type="text" name="box-fecha_denuncia" id="box-fecha_denuncia" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
	            <p>
	                <label for="box-fecha_ocurrencia">Fecha de ocurrencia del siniestro</label>
	                <input type="text" name="box-fecha_ocurrencia" id="box-fecha_ocurrencia" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
			</div>
			<div style="clear:both"></div>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Estado</legend>
			<p>
				<label for="box-tipo_siniestro">Tipo de siniestro</label>
				<select name="box-tipo_siniestro" id="box-tipo_siniestro" class="ui-widget-content">
					<option value="1">SIN RECLAMO A TERCEROS</option>
					<option value="2">CON RECLAMO A TERCEROS</option>
					<option value="3">REPOSICION</option>
					<option value="4">INSPECCIÓN</option>
					<option value="5">ROBO TOTAL DE UNIDAD</option>
					<option value="6">INCENDIO TOTAL DE UNIDAD</option>
				</select>
				<label for="box-pagado" style="margin-left:40px;width:initial">Pagado</label> <input type="checkbox" name="box-pagado" id="box-pagado" />
				<label for="box-cerrado" style="margin-left:40px;width:initial">Cerrado</label> <input type="checkbox" name="box-cerrado" id="box-cerrado" />
			</p>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Eventos</legend>
			
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Estudio jurídico</legend>
			<p>
				<label for="box-enviado_estudio_juridico">Se envió a estudio jurídico?</label>
				<input type="checkbox" name="box-enviado_estudio_juridico" id="box-enviado_estudio_juridico" />
				<span style="margin-left:250px">Fecha de envío</span>
				<input type="text" name="box-fecha_enviado_estudio_juridico" id="box-fecha_enviado_estudio_juridico" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
			</p>
			<p>
				<label for="box-compania_tercero">Compañía del tercero</label>
				<input type="text" name="box-compania_tercero" id="box-compania_tercero" class="ui-widget-content" />
				<span style="margin-left:50px">Fecha de pago</span>
				<input type="text" name="box-fecha_pago" id="box-fecha" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
				<span style="margin-left:50px">Cobrado</span>
				<input type="checkbox" name="box-cobrado" id="box-cobrado" />
			</p>
		</fieldset>
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">
			<input type="hidden" name="box-siniestro_id" id="box-siniestro_id" value="" />
			<input type="hidden" name="box-automotor_id" id="box-automotor_id" value="" />
			<input type="button" name="btnBox" id="btnBox" value="Guardar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
	</form>    
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>