<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Edificio</legend>                                    
        <p>
            <label for="box-incendio_edificio_domicilio_calle" style="width:200px">Dirección *</label>
            <input type="text" name="box-incendio_edificio_domicilio_calle" id="box-incendio_edificio_domicilio_calle" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
		<p>
            <label for="box-incendio_edificio_domicilio_nro" style="width:200px">Número *</label>
            <input type="text" name="box-incendio_edificio_domicilio_nro" id="box-incendio_edificio_domicilio_nro" maxlength="10" class="ui-widget-content required" style="width:80px" />
        </p>
        <p>
			<label for="box-incendio_edificio_domicilio_piso" style="width:200px">Piso</label>
            <input type="text" name="box-incendio_edificio_domicilio_piso" id="box-incendio_edificio_domicilio_piso" maxlength="4" class="ui-widget-content" style="width:40px" />
        </p>
        <p>
			<label for="box-incendio_edificio_domicilio_dpto" style="width:200px">Dpto</label>
            <input type="text" name="box-incendio_edificio_domicilio_dpto" id="box-incendio_edificio_domicilio_dpto" maxlength="3" class="ui-widget-content" style="width:40px" />
        </p>
		<p>
            <label for="box-incendio_edificio_domicilio_localidad" style="width:200px">Localidad *</label>
            <input type="text" name="box-incendio_edificio_domicilio_localidad" id="box-incendio_edificio_domicilio_localidad" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
        <p>
            <label for="box-incendio_edificio_domicilio_cp" style="width:200px">Código Postal *</label>
            <input type="text" name="box-incendio_edificio_domicilio_cp" id="box-incendio_edificio_domicilio_cp" maxlength="10" class="ui-widget-content required" style="width:100px" />
        </p>
		<p>
			<label for="box-incendio_edificio_country" style="width:200px">Barrio Cerrado / Country </label>
			<input type="text" name="box-incendio_edificio_country" id="box-incendio_edificio_country" maxlength="255" class="ui-widget-content" style="width:200px" />
		</p>
		<p>
			<label for="box-incendio_edificio_lote" style="width:200px">Lote </label>
			<input type="text" name="box-incendio_edificio_lote" id="box-incendio_edificio_lote" maxlength="255" class="ui-widget-content" style="width:200px" />
		</p>
		<p>
			<label for="box-incendio_edificio_valor_tasado" style="width:200px">Valor tasado de la propiedad</label>
			<input type="number" name="box-incendio_edificio_valor_tasado" id="box-incendio_edificio_valor_tasado" placeholder="Opcional" class="ui-widget-content" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Edificio Prorrata</legend> 
		<p>
			<label for="box-incendio_edificio_inc_edif">Valor *</label><input type="number" step="any" min="50000" max="5000000" name="box-incendio_edificio_inc_edif" id="box-incendio_edificio_inc_edif" class="ui-widget-content required" style="width:100px" /> <input type="checkbox" name="box-incendio_edificio_inc_edif_rep" id="box-incendio_edificio_inc_edif_rep">Con cláusula de Reposición a Nuevo</input>
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Mobiliario Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-incendio_edificio_inc_mob">Valor</label><input type="number" step="any" min="10000" max="1000000" name="box-incendio_edificio_inc_mob" id="box-incendio_edificio_inc_mob" class="ui-widget-content" style="width:100px" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">RC por Incendio - (Excluye cosas de Linderos) - a Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-incendio_edificio_rc_inc">Valor</label><input type="number" step="any" min="1000" max="250000" name="box-incendio_edificio_rc_inc" id="box-incendio_edificio_rc_inc" class="ui-widget-content" style="width:100px" />
		</p>

	</fieldset>
</form>