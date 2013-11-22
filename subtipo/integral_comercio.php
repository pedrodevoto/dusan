<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">General</legend>                                    
		<!-- <input style="margin-left:580px;margin-top:-64px" type="checkbox" /> -->
		<p>
            <label for="box-integral_comercio_domicilio_calle" style="width:200px">Dirección *</label>
            <input type="text" name="box-integral_comercio_domicilio_calle" id="box-integral_comercio_domicilio_calle" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
		<p>
            <label for="box-integral_comercio_domicilio_nro" style="width:200px">Número *</label>
            <input type="text" name="box-integral_comercio_domicilio_nro" id="box-integral_comercio_domicilio_nro" maxlength="10" class="ui-widget-content required" style="width:80px" />
        </p>
        <p>
			<label for="box-integral_comercio_domicilio_piso" style="width:200px">Piso</label>
            <input type="text" name="box-integral_comercio_domicilio_piso" id="box-integral_comercio_domicilio_piso" maxlength="4" class="ui-widget-content" style="width:40px" />
        </p>
        <p>
			<label for="box-integral_comercio_domicilio_dpto" style="width:200px">Dpto</label>
            <input type="text" name="box-integral_comercio_domicilio_dpto" id="box-integral_comercio_domicilio_dpto" maxlength="3" class="ui-widget-content" style="width:40px" />
        </p>
		<p>
            <label for="box-integral_comercio_domicilio_localidad" style="width:200px">Localidad *</label>
            <input type="text" name="box-integral_comercio_domicilio_localidad" id="box-integral_comercio_domicilio_localidad" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
        <p>
            <label for="box-integral_comercio_domicilio_cp" style="width:200px">Código Postal *</label>
            <input type="text" name="box-integral_comercio_domicilio_cp" id="box-integral_comercio_domicilio_cp" maxlength="10" class="ui-widget-content required" style="width:100px" />
        </p>
        <p>
            <label for="box-integral_comercio_actividad" style="width:200px">Actividad *</label>
            <input type="text" name="box-integral_comercio_actividad" id="box-integral_comercio_actividad" class="ui-widget-content required" style="width:100px" />
        </p>
		<p>
			<label for="box-integral_comercio_valor_tasado" style="width:200px">Valor tasado de la propiedad</label>
			<input type="number" step="any" name="box-integral_comercio_valor_tasado" id="box-integral_comercio_valor_tasado" placeholder="Opcional" class="ui-widget-content" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Edificio Prorrata</legend> 
		<p>
			<label for="box-integral_comercio_inc_edif">Valor</label><input type="number" step="any" min="50000" max="5000000" name="box-integral_comercio_inc_edif" id="box-integral_comercio_inc_edif" class="ui-widget-content required" style="width:100px" /> <input type="checkbox" name="box-integral_comercio_inc_edif_rep" id="box-integral_comercio_inc_edif_rep">Con cláusula de Reposición a Nuevo</input>
		</p>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-integral_comercio_bienes_de_uso_flag" id="box-integral_comercio_bienes_de_uso_flag" type="checkbox" /> Bienes de Uso</legend> 
		<p>
			<input type="button" id="box-integral_comercio_bienes_de_uso_add" value="Agregar" /> 
			Suma asegurada total: <span id="bienes_de_uso_total"></span>
		</p>
		<div id="bienes_de_uso">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Otros</legend> 
		<p>
			<label for="box-integral_comercio_inc_contenido">Incendio Contenido</label><input type="number" step="any" name="box-integral_comercio_inc_contenido" id="box-integral_comercio_inc_contenido" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_robo_pra">Robo a Primer Riesgo Absoluto</label><input type="number" step="any" name="box-integral_comercio_robo_pra" id="box-integral_comercio_robo_pra" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_cristales_pra">Cristales a Primer Riesgo Absoluto</label><input type="number" step="any" name="box-integral_comercio_cristales_pra" id="box-integral_comercio_cristales_pra" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_rc_comprensiva">RC Comprensiva</label><input type="number" step="any" name="box-integral_comercio_rc_comprensiva" id="box-integral_comercio_rc_comprensiva" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_rc_ascensor">RC Ascensor</label><input type="number" step="any" name="box-integral_comercio_rc_ascensor" id="box-integral_comercio_rc_ascensor" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_robo_matafuegos">Robo Matafuegos</label><input type="number" step="any" name="box-integral_comercio_robo_matafuegos" id="box-integral_comercio_robo_matafuegos" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_robo_lcm">Robo Luces, Cámaras, Mangueras</label><input type="number" step="any" name="box-integral_comercio_robo_lcm" id="box-integral_comercio_robo_lcm" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_danios_agua">Daños por Agua</label><input type="number" step="any" name="box-integral_comercio_danios_agua" id="box-integral_comercio_danios_agua" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_rc_garage">RC Garage</label><input type="number" step="any" name="box-integral_comercio_rc_garage" id="box-integral_comercio_rc_garage" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_comercio_rc_lind">RC Linderos</label><input type="number" step="any" name="box-integral_comercio_rc_lind" id="box-integral_comercio_rc_lind" class="ui-widget-content" />
		</p>
	</fieldset>
</form>