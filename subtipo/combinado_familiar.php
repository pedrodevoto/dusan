<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">General</legend>                                    
        <p>
            <label for="box-combinado_familiar_domicilio_calle" style="width:200px">Dirección *</label>
            <input type="text" name="box-combinado_familiar_domicilio_calle" id="box-combinado_familiar_domicilio_calle" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
		<p>
            <label for="box-combinado_familiar_domicilio_nro" style="width:200px">Número *</label>
            <input type="text" name="box-combinado_familiar_domicilio_nro" id="box-combinado_familiar_domicilio_nro" maxlength="10" class="ui-widget-content required" style="width:80px" />
        </p>
        <p>
			<label for="box-combinado_familiar_domicilio_piso" style="width:200px">Piso</label>
            <input type="text" name="box-combinado_familiar_domicilio_piso" id="box-combinado_familiar_domicilio_piso" maxlength="4" class="ui-widget-content" style="width:40px" />
        </p>
        <p>
			<label for="box-combinado_familiar_domicilio_dpto" style="width:200px">Dpto</label>
            <input type="text" name="box-combinado_familiar_domicilio_dpto" id="box-combinado_familiar_domicilio_dpto" maxlength="3" class="ui-widget-content" style="width:40px" />
        </p>
		<p>
            <label for="box-combinado_familiar_domicilio_localidad" style="width:200px">Localidad *</label>
            <input type="text" name="box-combinado_familiar_domicilio_localidad" id="box-combinado_familiar_domicilio_localidad" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
        <p>
            <label for="box-combinado_familiar_domicilio_cp" style="width:200px">Código Postal *</label>
            <input type="text" name="box-combinado_familiar_domicilio_cp" id="box-combinado_familiar_domicilio_cp" maxlength="10" class="ui-widget-content required" style="width:100px" />
        </p>
		<p>
			<label for="box-combinado_familiar_country" style="width:200px">Barrio Cerrado / Country </label>
			<input type="text" name="box-combinado_familiar_country" id="box-combinado_familiar_country" maxlength="255" class="ui-widget-content" style="width:200px" />
		</p>
		<p>
			<label for="box-combinado_familiar_lote" style="width:200px">Lote </label>
			<input type="text" name="box-combinado_familiar_lote" id="box-combinado_familiar_lote" maxlength="255" class="ui-widget-content" style="width:200px" />
		</p>
		<p>
			<label for="box-combinado_familiar_valor_tasado" style="width:200px">Valor tasado de la propiedad</label>
			<input type="number" name="box-combinado_familiar_valor_tasado" id="box-combinado_familiar_valor_tasado" placeholder="Opcional" class="ui-widget-content" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Edificio</legend> 
		<p>
			<label for="box-combinado_familiar_inc_edif" style="width:200px">Valor - Prorrata</label><input type="number" min="0" name="box-combinado_familiar_inc_edif" id="box-combinado_familiar_inc_edif" placeholder="Opcional" class="ui-widget-content" style="width:100px" /> <input type="number" min="0" max="100" name="box-combinado_familiar_inc_edif_prorrata" id="box-combinado_familiar_inc_edif_prorrata" maxlength="10" class="ui-widget-content" style="width:50px" /> %
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Responsabilidad Civil Linderos</legend> 
		<p>
			<label for="box-combinado_familiar_rc_lind" style="width:200px">Valor - Prorrata</label><input type="number" min="0" name="box-combinado_familiar_rc_lind" id="box-combinado_familiar_rc_lind" class="ui-widget-content" placeholder="Opcional" style="width:100px" /> <input type="number" min="0" max="100" name="box-combinado_familiar_rc_lind_prorrata" id="box-combinado_familiar_rc_lind_prorrata" maxlength="10" class="ui-widget-content" style="width:50px" /> %
		</p>

	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto</legend> 
		<p>
			<input type="button" id="box-combinado_familiar_tv_aud_vid_add" value="Agregar" /> 
			Suma asegurada total: <span id="tv_aud_vid_total"></span>
		</p>
		<div id="tv_aud_vid">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Robo y/o Hurto de Objetos Específicos y/o Aparatos Electrodomésticos a Prorrata</legend> 
		<p>
            <label for="box-combinado_familiar_prorrata_obj_esp" style="width:200px">Prorrata</label>
            <input type="number" min="0" max="100" name="box-combinado_familiar_prorrata_obj_esp" id="box-combinado_familiar_prorrata_obj_esp" maxlength="10" class="ui-widget-content" style="width:50px" /> %
		</p>
		<p>
			<input type="button" id="box-combinado_familiar_obj_esp_prorrata_add" value="Agregar" />
			Suma asegurada total (con prorrata): <span id="obj_esp_prorrata_total"></span>
		</p>
		<div id="obj_esp_prorrata">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Todo Riesgo Equipos de Computación en Domicilio a Primer Riesgo Absoluto</legend> 
		<p>
			<input type="button" id="box-combinado_familiar_equipos_computacion_add" value="Agregar" />
			Suma asegurada total: <span id="equipos_computacion_total"></span>
		</p>
		<div id="equipos_computacion">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Otros</legend> 
		<p>
			<label for="box-combinado_familiar_cristales" style="width:300px">Cristales a Primer Riesgo Absoluto</label><input type="number" name="box-combinado_familiar_cristales" id="box-combinado_familiar_cristales" placeholder="Opcional" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-combinado_familiar_responsabilidad_civil" style="width:300px">Responsabilidad Civil Hechos Privados a Primer Riesgo Absoluto con Franquicia</label><input type="number" name="box-combinado_familiar_responsabilidad_civil" id="box-combinado_familiar_responsabilidad_civil" placeholder="Opcional" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-combinado_familiar_danios_agua" style="width:300px">Daños por Agua al Mobiliario y/o Efectos Personales a Primer Riesgo Absoluto</label><input type="number" name="box-combinado_familiar_danios_agua" id="box-combinado_familiar_danios_agua" placeholder="Opcional" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-combinado_familiar_jugadores_golf" style="width:300px">Jugadores de Golf a Primer Riesgo Absoluto</label><input type="number" name="box-combinado_familiar_jugadores_golf" id="box-combinado_familiar_jugadores_golf" placeholder="Opcional" class="ui-widget-content" />
		</p>
	</fieldset>
</form>