<form name="frmBoxAsegurado" id="frmBoxAsegurado" class="frmBoxMain" style="margin-top:20px">
	<input type="hidden" id="box-action" name="box-action" value="insert" />
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Asegurado</legend>                                    
		<p>
	        <label for="box-accidentes_asegurado_nombre" style="width:160px">Apellido y nombre *</label>
	        <input type="text" name="box-accidentes_asegurado_nombre" id="box-accidentes_asegurado_nombre" maxlength="50" class="ui-widget-content" style="width:200px" />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_documento" style="width:160px">DNI *</label>
	        <input type="text" name="box-accidentes_asegurado_documento" id="box-accidentes_asegurado_documento" maxlength="15" class="ui-widget-content" style="width:200px" />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_nacimiento" style="width:160px">Fecha de nacimiento *</label>
	        <input type="text" name="box-accidentes_asegurado_nacimiento" id="box-accidentes_asegurado_nacimiento" maxlength="255" class="ui-widget-content box-date" style="width:200px" />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_domicilio" style="width:160px">Dirección / Localidad</label>
	        <input type="text" name="box-accidentes_asegurado_domicilio" id="box-accidentes_asegurado_domicilio" maxlength="500" class="ui-widget-content" style="width:200px" />
	    </p>
	    <p>
	        <label for="box-accidentes_asegurado_actividad" style="width:160px">Actividad *</label>
	        <select name="box-accidentes_asegurado_actividad" id="box-accidentes_asegurado_actividad" class="ui-widget-content" style="width:200px">
	            <option value="">Cargando</option>                
	        </select>                        
	    </p>            
		<p>
	        <label for="box-accidentes_asegurado_suma_asegurada" style="width:160px">Suma asegurada *</label>
	        <input type="text" name="box-accidentes_asegurado_suma_asegurada" id="box-accidentes_asegurado_suma_asegurada" maxlength="12" class="ui-widget-content" style="width:200px" />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_gastos_medicos" style="width:160px">Gastos medicofarmacológicos *</label>
	        <input type="text" name="box-accidentes_asegurado_gastos_medicos" id="box-accidentes_asegurado_gastos_medicos" maxlength="12" class="ui-widget-content" style="width:200px" />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_total" style="width:160px">Total</label>
	        <input type="text" name="box-accidentes_asegurado_total" id="box-accidentes_asegurado_total" maxlength="12" class="ui-widget-content" style="width:200px" readonly />
	    </p>
		<p>
			<label for="box-accidentes_asegurado_beneficiario" style="width:160px">Cargar beneficiario</label>
			<input type="checkbox" name="box-accidentes_asegurado_beneficiario" id="box-accidentes_asegurado_beneficiario" />
		</p>
		<p>
	        <label for="box-accidentes_asegurado_beneficiario_nombre" style="width:160px">Nombre de beneficiario *</label>
	        <input type="text" name="box-accidentes_asegurado_beneficiario_nombre" id="box-accidentes_asegurado_beneficiario_nombre" maxlength="50" class="ui-widget-content" style="width:200px" disabled />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_beneficiario_documento" style="width:160px">DNI de beneficiario *</label>
	        <input type="text" name="box-accidentes_asegurado_beneficiario_documento" id="box-accidentes_asegurado_beneficiario_documento" maxlength="15" class="ui-widget-content" style="width:200px" disabled />
	    </p>
		<p>
	        <label for="box-accidentes_asegurado_beneficiario_nacimiento" style="width:160px">Fecha de nacimiento de beneficiario *</label>
	        <input type="text" name="box-accidentes_asegurado_beneficiario_nacimiento" id="box-accidentes_asegurado_beneficiario_nacimiento" maxlength="255" class="ui-widget-content box-date" style="width:200px" disabled />
	    </p>
		<p align="center" style="margin-top:10px">
			<input type="reset" name="btnBoxAseguradoReset" id="btnBoxAseguradoReset" value="Borrar" /> <input type="button" name="btnBoxAsegurado" id="btnBoxAsegurado" value="Agregar" />                                    
	    </p>
		<br />
		<div id="divBoxListAsegurado" style="min-height:30px">
		    Cargando...
		</div>
	</fieldset>
</form>

<form name="frmBoxClausula" id="frmBoxClausula" class="frmBoxMain" style="margin-top:20px">
	<input type="hidden" id="box-action" name="box-action" value="insert" />
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Cláusulas de No Repetición</legend>                                    
		<p>
	        <label for="box-accidentes_clausula_nombre" style="width:160px">Nombre *</label>
	        <input type="text" name="box-accidentes_clausula_nombre" id="box-accidentes_clausula_nombre" maxlength="50" class="ui-widget-content" style="width:200px" />
	    </p>
		<p>
	        <label for="box-accidentes_clausula_cuit" style="width:160px">CUIT *</label>
	        <input type="text" name="box-accidentes_clausula_cuit" id="box-accidentes_clausula_cuit" maxlength="15" class="ui-widget-content" style="width:200px" />
		<p>
	        <label for="box-accidentes_clausula_domicilio" style="width:160px">Dirección / Localidad</label>
	        <input type="text" name="box-accidentes_clausula_domicilio" id="box-accidentes_clausula_domicilio" maxlength="500" class="ui-widget-content" style="width:200px" />
	    </p>
		<p align="center" style="margin-top:10px">
			<input type="reset" name="btnBoxClausulaReset" id="btnBoxClausulaReset" value="Borrar" /> <input type="button" name="btnBoxClausula" id="btnBoxClausula" value="Agregar" />                                    
	    </p>
		<br />
		<div id="divBoxListClausula" style="min-height:30px">
		    Cargando...
		</div>
	</fieldset>
</form>

<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
