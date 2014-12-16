<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain" enctype="multipart/form-data">
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
					<label for="box-cliente_razon_social">Razón Social</label>
					<input type="text" name="box-cliente_razon_social" id="box-cliente_razon_social" maxlength="255" class="ui-widget-content" style="width:220px" />
				</p>
				<p class="persona_juridica">
					<label for="box-cliente_tipo_sociedad_id">Tipo de Sociedad</label>
					<select name="box-cliente_tipo_sociedad_id" id="box-cliente_tipo_sociedad_id" class="ui-widget-content" style="width:100px"></select>
				</p>
	            <p class="persona_fisica">
	                <label for="box-cliente_nacimiento">Nacimiento *</label>
	                <input type="text" name="box-cliente_nacimiento" id="box-cliente_nacimiento" maxlength="10" class="ui-widget-content box-date" style="width:220px" />
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
		<input type="hidden" name="box-cliente_id" id="box-cliente_id" />            
	</form>
	<form name="frmBoxContacto" id="frmBoxContacto" class="frmBoxMain" style="margin-top:20px">
		<input type="hidden" id="box-action" name="box-action" value="insert" />
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
			<legend class="ui-widget ui-widget-header ui-corner-all">Agregar Contacto</legend>
			<div style="float:left;width:50%">
				<p>
					<label for="box-contacto_tipo">Tipo *</label>
					<select name="box-contacto_tipo" id="box-contacto_tipo" class="ui-widget-content" style="width:110px">
						<option value="">Cargando</option>                
					</select>                        
				</p>                                                
				<p>
					<label for="box-contacto_domicilio">Dirección *</label>
					<input type="text" name="box-contacto_domicilio" id="box-contacto_domicilio" maxlength="255" class="ui-widget-content" style="width:200px" />
				</p>
				<p>
					<label for="box-contacto_nro">Número *</label>
					<input type="text" name="box-contacto_nro" id="box-contacto_nro" maxlength="10" class="ui-widget-content" style="width:80px" />
				</p>
				<p>
					<label for="box-contacto_piso">Piso</label>
					<input type="text" name="box-contacto_piso" id="box-contacto_piso" maxlength="4" class="ui-widget-content" style="width:40px" />
				</p>
				<p>
					<label for="box-contacto_dpto">Dpto</label>
					<input type="text" name="box-contacto_dpto" id="box-contacto_dpto" maxlength="3" class="ui-widget-content" style="width:40px" />
				</p>
				<p>
					<label for="box-localidad_id">Localidad/CP *</label>
					<select style="height:10px;width:200px" name="box-localidad_id" id="box-localidad_id"></select>
				</p>
				<p>
					<label for="box-contacto_country">Barrio Cerrado / Country </label>
					<input type="text" name="box-contacto_country" id="box-contacto_country" maxlength="255" class="ui-widget-content" style="width:200px" />
				</p>
				<p>
					<label for="box-contacto_lote">Lote </label>
					<input type="text" name="box-contacto_lote" id="box-contacto_lote" maxlength="255" class="ui-widget-content" style="width:200px" />
				</p>
			</div>
			<div style="float:left;width:50%">
				<p>
					<label for="box-contacto_telefono1">Tel. Particular</label>
					<input type="text" name="box-contacto_telefono1" id="box-contacto_telefono1" maxlength="25" class="ui-widget-content" style="width:200px" />
				</p>
				<p>
					<label for="box-contacto_telefono2" style="width:68px">Tel. Celular</label> (011) 15
					<input type="text" name="box-contacto_telefono2" id="box-contacto_telefono2" maxlength="8" class="ui-widget-content" style="width:200px" pattern="[0-9]{8}" />
				</p>                                                                     
					<p>
						<label for="box-contacto_telefono2_compania">Compañía</label>
						<select name="box-contacto_telefono2_compania" id="box-contacto_telefono2_compania"><option value="">Elegir</option></select>	
					</p>
	                <p>
						<label for="box-contacto_telefono_laboral">Tel. Laboral</label>
						<input type="text" name="box-contacto_telefono_laboral" id="box-contacto_telefono_laboral" maxlength="25" class="ui-widget-content" style="width:200px" />
					</p>
					<p>
						<label for="box-contacto_telefono_alt">Tel Alternativo</label>
						<input type="text" name="box-contacto_telefono_alt" id="box-contacto_telefono_alt" maxlength="25" class="ui-widget-content" style="width:200px" />
					</p>
					<p>
						<label for="box-contacto_observaciones">Observaciones</label>
						<textarea name="box-contacto_observaciones" id="box-contacto_observaciones" maxlength="500" class="ui-widget-content" style="width:200px"></textarea>
					</p>                                                                     
				</div>
				<div style="clear:both">
					<p align="center" style="margin-top:10px">
						<input type="reset" name="btnBoxResetContacto" id="btnBoxResetContacto" value="Borrar" class="alert-error" /> <input type="button" name="btnBoxContacto" id="btnBoxContacto" value="Agregar" class="alert-success" />                                    
					</p>
				</div>
			</fieldset>         
		</form>
	<div style="margin-top:10px">
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
			<legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Contactos Relacionados</legend> 
			<div id="divBoxList" style="min-height:30px">
				Cargando...
			</div>
		</fieldset>
	</div>
	<form name="frmBox1" id="frmBox1" class="frmBoxMain">
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
	</form>

    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Fotos registro</legend> 
		<form class="fileForm" id="cliente_foto" suffix="Registro" action="upload-cliente_foto.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-cliente_foto">Imagen</label>
		        <input type="file" name="box-cliente_foto" id="box-cliente_foto" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir foto"> <span id="fotosLoadingcliente_foto" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		<div id="divBoxFotosRegistro" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
	
    <!-- Acciones -->
    <p align="center" style="margin-top:20px">     
        <input type="button" name="btnBox" id="btnBox" value="Aceptar" />
		<button id="btnAcciones" class="alert-success">Hecho</button>
    </p>
    <!-- Nota -->
    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio</p>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>