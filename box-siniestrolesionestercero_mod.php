<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  
	<h2 style="text-align:center">LESIONES DE TERCEROS</h2>
  	<!-- Siniestro -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Lesiones a tercero</legend>
			<p>
				<label for="box-nombre">Apellido y nombre</label>
				<input type="text" name="box-nombre" id="box-nombre" class="ui-widget-content" style="width:300px" />
				<label for="box-sexo" style="width:initial;vergical-align:initial">Sexo</label>
				<select name="box-sexo" id="box-sexo" style="ui-widget-content">
					<option value="1">M</option>
					<option value="2">F</option>
				</select>
			</p>
			<p>
				<label for="box-calle">Calle</label>
				<input type="text" name="box-calle" id="box-calle" class="ui-widget-content" />
				<label for="box-altura" style="width:initial">Altura</label>
				<input type="text" name="box-altura" id="box-altura" class="ui-widget-content" />
				<label for="box-localidad" style="width:initial">Localidad</label>
				<input type="text" name="box-localidad" id="box-localidad" class="ui-widget-content" style="width:180px" />
			</p>
			<p>
				<label for="box-provincia">Provincia</label>
				<input type="text" name="box-provincia" id="box-provincia" class="ui-widget-content" style="width:180px" />
				<label for="box-cp" style="width:initial">Cod Pos</label>
				<input type="text" name="box-cp" id="box-cp" class="ui-widget-content" style="width:80px" />
				<label for="box-tel" style="width:initial;margin-left:20px">Tel</label>
				<input type="text" name="box-tel" id="box-tel" class="ui-widget-content" style="width:80px" />
				<label for="box-cel" style="width:initial">Cel</label>
				<input type="text" name="box-cel" id="box-cel" class="ui-widget-content" style="width:80px" />
			</p>
			<p>
				<label for="box-fec_nac">Fecha de nacimiento</label>
				<input type="text" name="box-fecha_nac" id="box-fecha_nac" class="ui-widget-content box-date" style="width:80px" />
				<label for="box-nro_doc" style="width:initial;margin-left:20px">DNI</label>
				<input type="text" name="box-nro_doc" id="box-nro_doc" class="ui-widget-content" style="width:180px" />
				<label for="box-estado_civil" style="width:initial;margin-left:20px">Estado civil</label>
				<input type="text" name="box-estado_civil" id="box-estado_civil" class="ui-widget-content" style="width:180px" />
			</p>
			<p>
				<label for="box-relacion_asegurado"><b>Relación con el asegurado</b></label>
				<select name="box-relacion_asegurado" id="box-relacion_asegurado">
					<option value="1">Conductor otro vehículo</option>
					<option value="2">Pasajero otro vehículo</option>
					<option value="3">Pasajero vehículo asegurado</option>
					<option value="4">Peatón</option>
				</select>
			</p>
			<p>
				<label for="box-tipo_lesiones"><b>Tipo de lesiones</b></label>
				<select name="box-tipo_lesiones" id="box-tipo_lesiones">
					<option value="1">Leves</option>
					<option value="2">Graves (con internación)</option>
					<option value="3">Mortal</option>
				</select>
			</p>
			<p>
				<label for="box-examen_alcoholemia">Examen de alcoholemia</label>
				<select name="box-examen_alcoholemia" id="box-examen_alcoholemia" style="ui-widget-content">
					<option value="0">No</option>
					<option value="1">Sí</option>
					<option value="2">Se negó</option>
				</select>
			</p>
			<p>
				<label for="box-centro_asistencial">Centro asistencial</label>
				<input type="text" name="box-centro_asistencial" id="box-centro_asistencial" class="ui-widget-content" style="width:200px" />
			</p>
		</fieldset>
	<input type="hidden" name="box-siniestros_lesiones_terceros_id" id="box-siniestros_lesiones_terceros_id" value="" />
	</form>
    <!-- Acciones -->
    <p align="center" style="margin-top:20px">
		<button name="btnBox" id="btnBox">Guardar</button>
		<button name="btnBoxCancelar" id="btnBoxCancelar">Atrás</button>
    </p>
    <!-- Nota -->
    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>
	