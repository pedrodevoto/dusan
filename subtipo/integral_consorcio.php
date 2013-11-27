<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">General</legend>                                    
		<p>
            <label for="box-integral_consorcio_domicilio_calle" style="width:200px">Dirección *</label>
            <input type="text" name="box-integral_consorcio_domicilio_calle" id="box-integral_consorcio_domicilio_calle" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
		<p>
            <label for="box-integral_consorcio_domicilio_nro" style="width:200px">Número *</label>
            <input type="text" name="box-integral_consorcio_domicilio_nro" id="box-integral_consorcio_domicilio_nro" maxlength="10" class="ui-widget-content required" style="width:80px" />
        </p>
        <p>
			<label for="box-integral_consorcio_domicilio_piso" style="width:200px">Piso</label>
            <input type="text" name="box-integral_consorcio_domicilio_piso" id="box-integral_consorcio_domicilio_piso" maxlength="4" class="ui-widget-content" style="width:40px" />
        </p>
        <p>
			<label for="box-integral_consorcio_domicilio_dpto" style="width:200px">Dpto</label>
            <input type="text" name="box-integral_consorcio_domicilio_dpto" id="box-integral_consorcio_domicilio_dpto" maxlength="3" class="ui-widget-content" style="width:40px" />
        </p>
		<p>
            <label for="box-integral_consorcio_domicilio_localidad" style="width:200px">Localidad *</label>
            <input type="text" name="box-integral_consorcio_domicilio_localidad" id="box-integral_consorcio_domicilio_localidad" maxlength="255" class="ui-widget-content required" style="width:200px" />
        </p>
        <p>
            <label for="box-integral_consorcio_domicilio_cp" style="width:200px">Código Postal *</label>
            <input type="text" name="box-integral_consorcio_domicilio_cp" id="box-integral_consorcio_domicilio_cp" maxlength="10" class="ui-widget-content required" style="width:100px" />
        </p>
		<p>
			<label for="box-integral_consorcio_valor_tasado" style="width:200px">Valor tasado de la propiedad</label>
			<input type="number" step="any" name="box-integral_consorcio_valor_tasado" id="box-integral_consorcio_valor_tasado" placeholder="Opcional" class="ui-widget-content" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Edificio Prorrata</legend> 
		<p>
			<label for="box-integral_consorcio_inc_edif">Valor *</label><input type="number" step="any" min="50000" max="5000000" name="box-integral_consorcio_inc_edif" id="box-integral_consorcio_inc_edif" class="ui-widget-content required" style="width:100px" /> <input type="checkbox" name="box-integral_consorcio_inc_edif_rep" id="box-integral_consorcio_inc_edif_rep">Con cláusula de Reposición a Nuevo</input>
		</p>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Otros</legend> 
		<p>
			<label for="box-integral_consorcio_inc_contenido" style="width:500px">Incendio Contenido General - Partes Comunes</label><input type="number" step="any" name="box-integral_consorcio_inc_contenido" id="box-integral_consorcio_inc_contenido" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_robo_gral" style="width:500px">Robo Contenido General Mobiliario / Objetos Específicos – Partes Comunes</label><input type="number" step="any" name="box-integral_consorcio_robo_gral" id="box-integral_consorcio_robo_gral" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_robo_matafuegos" style="width:500px">Robo Matafuegos</label><input type="number" step="any" name="box-integral_consorcio_robo_matafuegos" id="box-integral_consorcio_robo_matafuegos" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_robo_lcm" style="width:500px">Robo de Luces de Emergencia, Cámaras de Seguridad y Mangueras de Incendio</label><input type="number" step="any" name="box-integral_consorcio_robo_lcm" id="box-integral_consorcio_robo_lcm" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_rc_comprensiva" style="width:500px">RC Comprensiva</label><input type="number" step="any" name="box-integral_consorcio_rc_comprensiva" id="box-integral_consorcio_rc_comprensiva" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_cristales" style="width:500px">Cristales y/o Vidrios y/o Espejos</label><input type="number" step="any" name="box-integral_consorcio_cristales" id="box-integral_consorcio_cristales" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_danios_agua" style="width:500px">Daños por Agua al Contenido de propiedad común</label><input type="number" step="any" name="box-integral_consorcio_danios_agua" id="box-integral_consorcio_danios_agua" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_rc_garage" style="width:500px">Responsabilidad Civil Garaje – Cubierto o Descubierto - por la guarda y/o depósito de vehículos</label><input type="number" step="any" name="box-integral_consorcio_rc_garage" id="box-integral_consorcio_rc_garage" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_acc_personales" style="width:500px">Accidentes Personales para el Personal que preste servicios al Consorcio sin 
relación de dependencia laboral en los términos de la Ley de Contrato de Trabajo</label><input type="number" step="any" name="box-integral_consorcio_acc_personales" id="box-integral_consorcio_acc_personales" class="ui-widget-content" />
		</p>
		<p>
			<label for="box-integral_consorcio_robo_exp" style="width:500px">Robo de Dinero de las Expensas en poder del Encargado</label><input type="number" step="any" name="box-integral_consorcio_robo_exp" id="box-integral_consorcio_robo_exp" class="ui-widget-content" />
		</p>
	</fieldset>
</form>