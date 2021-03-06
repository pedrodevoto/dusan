<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<script>
$('.addFoto').click(function() {
	var object = $(this).attr("object");
	var j = 0;
	$('#items-'+object+' p').each(function (i, e) {
		j = Math.max(Number($(e).attr('id')), j) + 1;
	});
	var item = '<p id="'+j+'"><label for="box-'+object+'_foto"> </label> <input type="file" name="box-'+object+'_foto[]" id="box-'+object+'_foto" class="ui-widget-content" style="width:220px" /></p>';
	$('#items-'+object).append(item);
	return false;
})
</script>
<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Cliente -->    
		<div style="float:left;width:50%">
			<fieldset class="ui-widget ui-widget-content ui-corner-all">
				<legend class="ui-widget ui-widget-header ui-corner-all">Tipo de Persona</legend>
	            <p>
					<label for="box-cliente_tipo_persona">Tipo de Persona</label>
					<select name="box-cliente_tipo_persona" id="box-cliente_tipo_persona" class="ui-widget-content" style="width:180px">
						<option value="1">Persona Física</option>
						<option value="2">Persona Jurídica</option>
					</select>
				</p>
			</fieldset>
		</div>
		<div style="float:left;width:50%">
			<fieldset class="ui-widget ui-widget-content ui-corner-all">
				<legend class="ui-widget ui-widget-header ui-corner-all">Sucursales</legend>
				<p>
					<label for="box-sucursal_id">Sucursales *</label>
					<select multiple="multiple" name="box-sucursal_id[]" id="box-sucursal_id" class="ui-widget-content" style="width:180px">
						<option value="">Cargando</option>
					</select>
				</p>
			</fieldset>
		</div>
		<div style="clear:both"></div>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all" id="titulo_tipo_persona"></legend>                                     
			<div style="float:left;width:50%">
				<p class="persona_fisica">
	                <label for="box-cliente_apellido">Apellido *</label>
	                <input type="text" name="box-cliente_apellido" id="box-cliente_apellido" maxlength="255" class="ui-widget-content" style="width:220px" />
	            </p>
				<p class="persona_fisica">
	                <label for="box-cliente_nombre">Nombre *</label>
	                <input type="text" name="box-cliente_nombre" id="box-cliente_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
	            </p>
				<p class="persona_juridica">
					<label for="box-cliente_razon_social">Razón Social *</label>
					<input type="text" name="box-cliente_razon_social" id="box-cliente_razon_social" maxlength="255" class="ui-widget-content" style="width:220px" />
				</p>
				<p class="persona_juridica">
					<label for="box-cliente_tipo_sociedad_id">Tipo de Sociedad</label>
					<select name="box-cliente_tipo_sociedad_id" id="box-cliente_tipo_sociedad_id" class="ui-widget-content" style="width:100px"></select>
				</p>
	            <p class="persona_fisica">
	                <label for="box-cliente_nacimiento">Nacimiento *</label>
	                <input type="text" name="box-cliente_nacimiento" id="box-cliente_nacimiento" maxlength="10" class="ui-widget-content box-date" style="width:220px" autocomplete="off" />
	            </p>                        
	            <p class="persona_fisica">
	                <label for="box-cliente_sexo">Sexo *</label>
	                <select name="box-cliente_sexo" id="box-cliente_sexo" class="ui-widget-content" style="width:65px"></select>
	            </p>
	            <p class="persona_fisica">
	                <label for="box-cliente_nacionalidad_id">Nacionalidad *</label>
	                <select name="box-cliente_nacionalidad_id" id="box-cliente_nacionalidad_id" class="ui-widget-content" style="width:220px">
					</select>
	            </p>
	            <p class="persona_fisica">
	                <label for="box-cliente_tipo_doc">Tipo Doc. *</label>
	                <select name="box-cliente_tipo_doc" id="box-cliente_tipo_doc" class="ui-widget-content" style="width:100px"></select>
	            </p>
			</div>
			<div style="float:left;width:50%">
	            <p class="persona_fisica">
	                <label for="box-cliente_nro_doc">Nro de Doc. *</label>
	                <input type="text" name="box-cliente_nro_doc" id="box-cliente_nro_doc" maxlength="15" class="ui-widget-content" style="width:220px" />
	            </p>
	            <p class="persona_fisica">
	                <label for="box-cliente_cf_id">Condición Fiscal *</label>
	                <select name="box-cliente_cf_id" id="box-cliente_cf_id" class="ui-widget-content" style="width:180px"></select>
	            </p>                                    
	            <p>
	                <label for="box-cliente_cuit">CUIT</label>
					<input type="text" name="box-cliente_cuit_0" id="box-cliente_cuit_0" maxlength="2" class="ui-widget-content" style="width:20px" /> <input type="text" name="box-cliente_cuit_1" id="box-cliente_cuit_1" maxlength="8" class="ui-widget-content" style="width:75px" /> <input type="text" name="box-cliente_cuit_2" id="box-cliente_cuit_2" maxlength="1" class="ui-widget-content" style="width:20px" />
	            </p>
	            <p>
	                <label for="box-cliente_email">E-mail</label>
	                <input type="text" name="box-cliente_email" id="box-cliente_email" maxlength="255" class="ui-widget-content" style="width:220px" />
	            </p>                                    
				<p>
					<label for="box-cliente_email_alt">E-mail Alternativo</label>
					<input type="text" name="box-cliente_email_alt" id="box-cliente_email_alt" maxlength="255" class="ui-widget-content" style="width:220px" />
				</p>
			</div>
			<div style="clear:both"></div>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Registro de Conducir</legend>
			<div style="float:left;width:50%">
	            <p>
	                <label for="box-cliente_registro">Registro de Cond.</label>
	                <input type="text" name="box-cliente_registro" id="box-cliente_registro" maxlength="15" class="ui-widget-content" style="width:220px" />
	            </p>
	            <p>
	                <label for="box-cliente_reg_vencimiento">Vencimiento</label>
	                <input type="text" name="box-cliente_reg_vencimiento" id="box-cliente_reg_vencimiento" maxlength="10" class="ui-widget-content box-date" style="width:220px" />
	            </p>
			</div>
			<div style="float:left;width:50%">
	            <p>
	                <label for="box-cliente_reg_tipo_id">Tipo de Registro</label>
	                <select multiple="multiple" name="box-cliente_reg_tipo_id[]" id="box-cliente_reg_tipo_id" class="ui-widget-content" style="width:100px"></select>
	            </p>
			</div>
			<div style="clear:both"></div>
		</fieldset>
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="hidden" name="box-insert" id="box-insert" value="1" />            
            <input type="button" name="btnBox" id="btnBox" value="Siguiente" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio</p>
	</form>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>