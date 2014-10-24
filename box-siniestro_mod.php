<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<div class="divBoxContainer" style="width:94%">  
	<?php $progress_bar = 'siniestro'; require_once('inc/progress.php'); ?>
	
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<span style="text-align:center"><h1>INFORMACIÓN DEL SINIESTRO</h1></span>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	        <legend class="ui-widget ui-widget-header ui-corner-all">Información del cliente</legend>
			<p style="margin-bottom:10px">
				<label for="box-cliente_nombre"><b>Cliente:</b></label>
				<span type="span" id="box-cliente_nombre"></span>
			</p>
			<p style="margin-bottom:10px">
				<label for="box-cliente_domicilio"><b>Domicilio:</b></label>
				<span type="span" id="box-cliente_domicilio"></span>
				<span style="margin-left:20px"><b>Teléfonos: </b></span>
				<span type="span" id="box-cliente_telefonos"></span>
			</p>
			<p style="margin-bottom:10px">
				<label for="box-asegurado_registro"><b>Registro Nro: </b></label>
				<input type="text" name="box-asegurado_registro" id="box-asegurado_registro" class="ui-widget-content" style="width:100px" />
				<span style="margin-left:40px"><b>Vencimiento: </b></span>
				<input type="text" name="box-asegurado_registro_venc" id="box-asegurado_registro_venc" class="ui-widget-content box-date" style="width:80px" />
			</p>
		</fieldset>
	
  	<!-- Siniestro -->        
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Siniestro</legend>  
			<div style="width:50%;float:left">
				<p style="margin-bottom:10px">
					<label style="width:initial" for="box-seguro_nombre"><b>Compañía:</b></label>
					<span type="span" id="box-seguro_nombre"></span>
				</p>
				<p style="margin-bottom:10px">
					<label style="width:initial" for="box-poliza_numero"><b>Póliza nro:</b></label>
					<span type="span" id="box-poliza_numero"></span>
				</p>
			</div>
			<div style="width:50%;float:left">
	            <p style="margin-bottom:10px">
	                <label style="width:initial"  for="box-productor_seguro_codigo"><b>Productor:</b></label>
	                <span type="span" id="box-productor_seguro_codigo" />
	            </p>
				<p style="margin-bottom:10px">
					<label style="width:initial"  for="box-poliza_vigencia"><b>Vigencia:</b></label>
					<span type="span" id="box-poliza_vigencia"></span>
				</p>
	        </div>
			<div style="clear:both"></div>
			<p style="margin-bottom:10px">
				<label style="width:initial" for="box-poliza_detalle"><b>Detalle de póliza:</b></label>
				<span type="span" id="box-poliza_detalle"></span>
			</p>
			<div style="width:50%;float:left">
				<p>
					<label style="width:220px" for="box-siniestro_numero"><b>N° DE SINIESTRO</b></label>
					<input type="text" name="box-siniestro_numero" id="box-siniestro_numero" maxlength="255" class="ui-widget-content" style="width:80px" />
				</p>
				<p>
					<label style="width:220px" for="box-fecha_compania"><b>Fecha de ingreso a la compañía</b></label>
					<input type="text" name="box-fecha_compania" id="box-fecha_compania" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
				</p>
			</div>
			
			<div style="width:50%;float:left">
	            <p>
	                <label style="width:220px" for="box-fecha_denuncia"><b>Fecha de ingreso de siniestro</b></label>
	                <input type="text" name="box-fecha_denuncia" id="box-fecha_denuncia" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
	            <p>
	                <label style="width:220px" for="box-fecha_ocurrencia"><b>Fecha de ocurrencia del siniestro</b></label>
	                <input type="text" name="box-fecha_ocurrencia" id="box-fecha_ocurrencia" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
			</div>
			<div style="clear:both"></div>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Estado</legend>
			<p>
				<label for="box-tipo_siniestro"><b>Tipo de siniestro</b></label>
				<select name="box-tipo_siniestro" id="box-tipo_siniestro" class="ui-widget-content">
					<option value="1">SIN RECLAMO A TERCEROS</option>
					<option value="2">CON RECLAMO A TERCEROS</option>
					<option value="3">REPOSICION</option>
					<option value="4">INSPECCIÓN</option>
					<option value="5">ROBO TOTAL DE UNIDAD</option>
					<option value="6">INCENDIO TOTAL DE UNIDAD</option>
				</select>
				<label for="box-pagado" style="margin-left:40px;width:initial"><b>Pagado</b></label> <input type="checkbox" name="box-pagado" id="box-pagado" />
				<label for="box-cerrado" style="margin-left:40px;width:initial"><b>Cerrado</b></label> <input type="checkbox" name="box-cerrado" id="box-cerrado" />
			</p>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Denuncia</legend>
			<button id="btnDetalle">Ir al formulario</button>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Eventos</legend>
			<div style="width:50%;float:left">
				<p>
					<label for="box-evento_fecha"><b>Fecha: </b></label>
					<input type="text" name="box-evento_fecha" id="box-evento_fecha" class="ui-widget-content box-date" style="width:80px" maxlength="10" />
				</p>
				<p>
					<label for="box-evento_comentario"><b>Comentarios: </b></label>
					<textarea name="box-evento_comentario" id="box-evento_comentario" class="ui-widget-content" rows="4" style="width:220px" />
				</p>
				<p>
					<label>&nbsp;</label>
					<button id="btnEvento">Agregar</button>
				</p>
			</div>
			<div style="width:50%;float:left">
				<div style="width:100%;height:250px;overflow-y:scroll;overflow-x:hidden" id="siniestro_eventos">
					
				</div>
			</div>
			<div style="clear:both"></div>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Estudio jurídico</legend>
			<p>
				<label for="box-enviado_estudio_juridico"><b>Se envió a estudio jurídico?</b></label>
				<input type="checkbox" name="box-enviado_estudio_juridico" id="box-enviado_estudio_juridico" />
				<span style="margin-left:250px"><b>Fecha de envío</b></span>
				<input type="text" name="box-fecha_enviado_estudio_juridico" id="box-fecha_enviado_estudio_juridico" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
			</p>
			<p>
				<label for="box-compania_tercero"><b>Compañía del tercero</b></label>
				<input type="text" name="box-compania_tercero" id="box-compania_tercero" class="ui-widget-content" />
				<span style="margin-left:50px"><b>Fecha de pago</b></span>
				<input type="text" name="box-fecha_pago" id="box-fecha" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
				<span style="margin-left:50px"><b>Cobrado</b></span>
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