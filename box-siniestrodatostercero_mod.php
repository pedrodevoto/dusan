<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  
	<h2 style="text-align:center">DATOS DEL TERCERO</h2>
  	<!-- Siniestro -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del tercero</legend>
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
				<label for="box-registro">N° de registro</label>
				<input type="text" name="box-registro" id="box-registro" class="ui-widget-content" style="width:100px" />
				<label for="box-registro_venc" style="width:initial;margin-left:20px">Fecha de vencimiento</label>
				<input type="text" name="box-registro_venc" id="box-registro_venc" class="ui-widget-content box-date" style="width:80px" />
				<label for="box-acompanantes" style="width:initial;margin-left:20px">Cuántas personas lo acompañaban?</label>
				<input type="text" name="box-acompanantes" id="box-acompanantes" class="ui-widget-content	" style="width:40px" />
			</p>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all conductor-vehiculo" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del conductor del vehículo tercero</legend>
			<p>
				<b>¿El conductor es el propio asegurado?</b>
				<select name="box-conductor_asegurado" id="box-conductor_asegurado" style="ui-widget-content">
					<option value="1" selected>Sí</option>
					<option value="0">No</option>
				</select>
			</p>
			<p>
				<label for="box-conductor_nombre">Apellido y nombre</label>
				<input type="text" name="box-conductor_nombre" id="box-conductor_nombre" class="ui-widget-content" style="width:300px" />
				<label for="box-conductor_sexo" style="width:initial;vergical-align:initial">Sexo</label>
				<select name="box-conductor_sexo" id="box-conductor_sexo" style="ui-widget-content">
					<option value="1">M</option>
					<option value="2">F</option>
				</select>
			</p>
			<p>
				<label for="box-conductor_calle">Calle</label>
				<input type="text" name="box-conductor_calle" id="box-conductor_calle" class="ui-widget-content" />
				<label for="box-conductor_altura" style="width:initial">Altura</label>
				<input type="text" name="box-conductor_altura" id="box-conductor_altura" class="ui-widget-content" />
				<label for="box-conductor_localidad" style="width:initial">Localidad</label>
				<input type="text" name="box-conductor_localidad" id="box-conductor_localidad" class="ui-widget-content" style="width:180px" />
			</p>
			<p>
				<label for="box-conductor_provincia">Provincia</label>
				<input type="text" name="box-conductor_provincia" id="box-conductor_provincia" class="ui-widget-content" style="width:180px" />
				<label for="box-conductor_cp" style="width:initial">Cod Pos</label>
				<input type="text" name="box-conductor_cp" id="box-conductor_cp" class="ui-widget-content" style="width:80px" />
				<label for="box-conductor_tel" style="width:initial;margin-left:20px">Tel</label>
				<input type="text" name="box-conductor_tel" id="box-conductor_tel" class="ui-widget-content" style="width:80px" />
				<label for="box-conductor_cel" style="width:initial">Cel</label>
				<input type="text" name="box-conductor_cel" id="box-conductor_cel" class="ui-widget-content" style="width:80px" />
			</p>
			<p>
				<label for="box-conductor_fec_nac">Fecha de nacimiento</label>
				<input type="text" name="box-conductor_fecha_nac" id="box-conductor_fecha_nac" class="ui-widget-content box-date" style="width:80px" />
				<label for="box-conductor_registro" style="width:initial;margin-left:20px">N° de registro</label>
				<input type="text" name="box-conductor_registro" id="box-conductor_registro" class="ui-widget-content" style="width:100px" />
				<label for="box-conductor_registro_venc" style="width:initial;margin-left:20px">Fecha de vencimiento</label>
				<input type="text" name="box-conductor_registro_venc" id="box-conductor_registro_venc" class="ui-widget-content box-date" style="width:80px" />
			</p>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del vehículo tercero</legend>
			<p>
				<label for="box-seguro">Asegurado en</label>
				<input type="text" name="box-seguro" id="box-seguro" class="ui-widget-content">
				<label for="box-nro_poliza">N° póliza</label>
				<input type="text" name="box-nro_poliza" id="box-nro_poliza" class="ui-widget-content">
			</p>
			<p>
				<label for="box-marca">Marca</label>
				<input type="text" name="box-marca" id="box-marca" class="ui-widget-content" />
				<label for="box-modelo">Modelo</label>
				<input type="text" name="box-modelo" id="box-modelo" class="ui-widget-content" />
				<label for="box-ano" style="width:initial">Año</label>
				<input type="text" name="box-ano" id="box-marca" class="ui-widget-content" style="width:50px" />
				<label for="box-patente_0" style="width:initial">Patente</label>
		        <input type="text" name="box-patente_0" id="box-patente_0" maxlength="3" class="ui-widget-content required" style="width:30px" /> 
				<input type="text" name="box-patente_1" id="box-patente_1" maxlength="3" class="ui-widget-content required" style="width:30px" />
			</p>
			<p>
				<label for="box-tipo">Tipo</label>
				<input type="text" name="box-tipo" id="box-tipo" class="ui-widget-content" />
				<label for="box-uso">Uso</label>
				<input type="text" name="box-uso" id="box-uso" class="ui-widget-content" />
			</p>
			<p>
				<label for="box-nro_motor">N° motor</label>
				<input type="text" name="box-nro_motor" id="box-nro_motor" class="ui-widget-content" />
				<label for="box-nro_chasis">N° Chasis</label>
				<input type="text" name="box-nro_chasis" id="box-nro_chasis" class="ui-widget-content" />
			</p>
			<p>
				<b>DETALLE DE LOS DAÑOS</b>
			</p>
			<div style="width:33%;float:left">
				<p>
					<table>
						<thead>
							<th></th>
							<th>Izq</th>
							<th>Der</th>
						</thead>
						<tbody>
							<tr>
								<td><b>Guardabarro delantero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_guardabarro_del_izq" id="box-danios_guardabarro_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_guardabarro_del_der" id="box-danios_guardabarro_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Faro delantero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_faro_del_izq" id="box-danios_faro_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_faro_del_der" id="box-danios_faro_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Puerta delantera:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_puerta_del_izq" id="box-danios_puerta_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_puerta_del_der" id="box-danios_puerta_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Puerta trasera:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_puerta_tras_izq" id="box-danios_puerta_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_puerta_tras_der" id="box-danios_puerta_tras_der" />
								</td>
							</tr>
						</tbody>
					</table>
				</p>
			</div>
			<div style="width:33%;float:left">
				<p>
					<table>
						<thead>
							<th></th>
							<th>Izq</th>
							<th>Der</th>
						</thead>
						<tbody>
							<tr>
								<td><b>Guardabarro trasero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_guardabarro_tras_izq" id="box-danios_guardabarro_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_guardabarro_tras_der" id="box-danios_guardabarro_tras_der" />
								</td>
							</tr>
							<tr>
								<td><b>Faro trasero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_faro_tras_izq" id="box-danios_faro_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_faro_tras_der" id="box-danios_faro_tras_der" />
								</td>
							</tr>
							<tr>
								<td><b>Paragolpes delantero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_paragolpes_del_izq" id="box-danios_paragolpes_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_paragolpes_del_der" id="box-danios_paragolpes_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Paragolpes trasero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_paragolpes_tras_izq" id="box-danios_paragolpes_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_paragolpes_tras_der" id="box-danios_paragolpes_tras_der" />
								</td>
							</tr>
						</tbody>
					</table>
				</p>
			</div>
			<div style="width:33%;float:left">
				<p>
					<table>
						<thead>
							<th></th>
							<th>Izq</th>
							<th>Der</th>
						</thead>
						<tbody>
							<tr>
								<td><b>Espejo retrovisor:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_retrovisor_izq" id="box-danios_retrovisor_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_retrovisor_der" id="box-danios_retrovisor_der" />
								</td>
							</tr>
							<tr>
								<td><b>Baul:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_baul_izq" id="box-danios_baul_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_baul_der" id="box-danios_baul_der" />
								</td>
							</tr>
							<tr>
								<td><b>Capot:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_capot_izq" id="box-danios_capot_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_capot_der" id="box-danios_capot_der" />
								</td>
							</tr>
							<tr>
								<td><b>Techo:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-danios_techo_izq" id="box-danios_techo_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-danios_techo_der" id="box-danios_techo_der" />
								</td>
							</tr>
						</tbody>
					</table>
				</p>
			</div>
			<div style="clear:both"></div>
			<p>
				<label for="box-danios_observaciones" style="width:200px">Observaciones de daños:</label>
				<textarea name="box-danios_observaciones" id="box-danios_observaciones" style="width:100%" rows="4"></textarea>
			</p>
		</fieldset>
	<input type="hidden" name="box-siniestros_datos_terceros_id" id="box-siniestros_datos_terceros_id" value="" />
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
	