<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
	    <legend class="ui-widget ui-widget-header ui-corner-all">General</legend>                                    
		<!-- <input style="margin-left:580px;margin-top:-64px" type="checkbox" /> -->
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
			<input type="number" step="any" name="box-combinado_familiar_valor_tasado" id="box-combinado_familiar_valor_tasado" placeholder="Opcional" class="ui-widget-content" />
		</p>
	</fieldset>
	<?php if ($row_Recordset1['poliza_plan_flag']):?>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
		    <legend class="ui-widget ui-widget-header ui-corner-all">Detalles del plan</legend>                                    
			<?php
			$sql = 'SELECT poliza_pack_detalle_cobertura, poliza_pack_detalle_valor FROM poliza_pack_detalle WHERE poliza_pack_id = '.$row_Recordset1['poliza_pack_id'];
			$res = mysql_query($sql, $connection) or die(mysql_error());
			while ($cobertura = mysql_fetch_array($res)):
			?>
			<p>
	            <label style="width:200px"><?=$cobertura[0]?></label>
	            <input type="name" maxlength="255" class="ui-widget-content" style="width:200px" value="<?=$cobertura[1]?>" readonly />
	        </p>
			<?php endwhile; ?>
		</fieldset>
	<input type="hidden" name="box-poliza_plan_flag" id="box-poliza_plan_flag" value="1" />
	<?php else: ?>
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_inc_edif_flag" id="box-combinado_familiar_inc_edif_flag" type="checkbox" /> Incendio Edificio Prorrata</legend> 
		<p>
			<label for="box-combinado_familiar_inc_edif">Valor</label><input type="number" step="any" min="50000" max="5000000" name="box-combinado_familiar_inc_edif" id="box-combinado_familiar_inc_edif" class="ui-widget-content" style="width:100px" /> <input type="checkbox" name="box-combinado_familiar_inc_edif_rep" id="box-combinado_familiar_inc_edif_rep">Con cláusula de Reposición a Nuevo</input>
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Incendio Mobiliario Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_inc_mob">Valor *</label><input type="number" step="any" min="10000" max="1000000" name="box-combinado_familiar_inc_mob" id="box-combinado_familiar_inc_mob" class="ui-widget-content required" style="width:100px" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Robo y/o Hurto Mobiliario y/o Efectos Personales Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_ef_personales">Valor *</label><input type="number" step="any" min="1000" max="100000" name="box-combinado_familiar_ef_personales" id="box-combinado_familiar_ef_personales" class="ui-widget-content required" style="width:100px" />
		</p>
	</fieldset>

	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_tv_aud_vid_flag" id="box-combinado_familiar_tv_aud_vid_flag" type="checkbox" /> Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto</legend> 
		<p>
			<input type="button" id="box-combinado_familiar_tv_aud_vid_add" value="Agregar" /> 
			Suma asegurada total: <span id="tv_aud_vid_total"></span>
		</p>
		<div id="tv_aud_vid">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_obj_esp_prorrata_flag" id="box-combinado_familiar_obj_esp_prorrata_flag" type="checkbox" /> Robo y/o Hurto de Objetos Específicos y/o Aparatos Electrodomésticos a Prorrata</legend> 
		<p>
			<input type="button" id="box-combinado_familiar_obj_esp_prorrata_add" value="Agregar" />
			Suma asegurada total: <span id="obj_esp_prorrata_total"></span>
		</p>
		<div id="obj_esp_prorrata">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_equipos_computacion_flag" id="box-combinado_familiar_equipos_computacion_flag" type="checkbox" /> Todo Riesgo Equipos de Computación en Domicilio a Primer Riesgo Absoluto</legend> 
		<p>
			<input type="button" id="box-combinado_familiar_equipos_computacion_add" value="Agregar" />
			Suma asegurada total: <span id="equipos_computacion_total"></span>
		</p>
		<div id="equipos_computacion">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_film_foto_flag" id="box-combinado_familiar_film_foto_flag" type="checkbox" /> Robo de Filmadoras y/o Cam. Fotográficas a Prorrata</legend> 
		<p>
			<input type="button" id="box-combinado_familiar_film_foto_add" value="Agregar" />
			Suma asegurada total: <span id="film_foto_total"></span>
		</p>
		<div id="film_foto">
		</div>
	</fieldset>
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Cristales a Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_cristales">Valor *</label><input type="number" step="any" min="200" max="50000" name="box-combinado_familiar_cristales" id="box-combinado_familiar_cristales" class="ui-widget-content required" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_responsabilidad_civil_flag" id="box-combinado_familiar_responsabilidad_civil_flag" type="checkbox" /> RC Hechos Privados a Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_responsabilidad_civil">Valor</label><input type="number" step="any" min="10000" max="300000" name="box-combinado_familiar_responsabilidad_civil" id="box-combinado_familiar_responsabilidad_civil" class="ui-widget-content" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_rc_inc_flag" id="box-combinado_familiar_rc_inc_flag" type="checkbox" /> RC por Incendio - (Excluye cosas de Linderos) - a Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_rc_inc">Valor</label><input type="number" step="any" min="1000" max="250000" name="box-combinado_familiar_rc_inc" id="box-combinado_familiar_rc_inc" class="ui-widget-content" style="width:100px" />
		</p>

	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_danios_agua_flag" id="box-combinado_familiar_danios_agua_flag" type="checkbox" /> Daños por Agua al Mobil. y/o Ef. Pers. (Exc. Edificio) a Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_danios_agua">Valor</label><input type="number" step="any" min="1000" max="25000" name="box-combinado_familiar_danios_agua" id="box-combinado_familiar_danios_agua" class="ui-widget-content" />
		</p>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all optional" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all"><input class="toggle-fieldset" name="box-combinado_familiar_jugadores_golf_flag" id="box-combinado_familiar_jugadores_golf_flag" type="checkbox" /> Jugadores de Golf a Primer Riesgo Absoluto</legend> 
		<p>
			<label for="box-combinado_familiar_jugadores_golf">Valor</label><input class="toggle-fieldset" type="number" step="any" min="200" max="5000" name="box-combinado_familiar_jugadores_golf" id="box-combinado_familiar_jugadores_golf" class="ui-widget-content" />
		</p>
	</fieldset>
	<input type="hidden" name="box-poliza_plan_flag" id="box-poliza_plan_flag" value="0" />
	<?php endif; ?>
</form>