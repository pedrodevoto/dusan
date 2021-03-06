<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	// Require DB functions
	require_once('inc/db_functions.php');	
	require_once('inc/echo_functions.php');	
?>

<div class="divBoxContainer" style="width:94%">  
  	<!-- Siniestro -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<span style="text-align:center"><h1>FORMULARIO DE SINIESTRO</h1></span>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del siniestro</legend>
			<p>
				<label for="box-fecha">Fecha de siniestro</label>
				<input type="text" name="box-fecha" id="box-fecha" class="ui-widget-content box-date" style="width:80px" />
				<label for="box-hora" style="width:initial">Hora</label>
				<input type="text" name="box-hora" id="box-hora" class="ui-widget-content" style="width:80px" />
				<label for="box-diurno" style="width:initial;vertical-align:initial">Diurno</label>
				<input type="checkbox" value="1" name="box-diurno" id="box-diurno" class="ui-widget-content" />
				<label for="box-nocturno" style="width:initial;vertical-align:initial">Nocturno</label>
				<input type="checkbox" value="1" name="box-nocturno" id="box-nocturno" class="ui-widget-content" />
				<label for="box-seco" style="width:initial;vertical-align:initial">Seco</label>
				<input type="checkbox" value="1" name="box-seco" id="box-seco" class="ui-widget-content" />
				<label for="box-lluvia" style="width:initial;vertical-align:initial">Lluvia</label>
				<input type="checkbox" value="1" name="box-lluvia" id="box-lluvia" class="ui-widget-content" />
				<label for="box-granizo" style="width:initial;vertical-align:initial">Granizo</label>
				<input type="checkbox" value="1" name="box-granizo" id="box-granizo" class="ui-widget-content" />
				<label for="box-niebla" style="width:initial;vertical-align:initial">Niebla</label>
				<input type="checkbox" value="1" name="box-niebla" id="box-niebla" class="ui-widget-content" />
				<label for="box-nieve" style="width:initial;vertical-align:initial">Nieve</label>
				<input type="checkbox" value="1" name="box-nieve" id="box-nieve" class="ui-widget-content" />
			</p>
			<p>
				<label for="box-calle">Calle</label>
				<input type="text" name="box-calle" id="box-calle" class="ui-widget-content" />
				<label for="box-altura" style="width:initial">Altura</label>
				<input type="text" name="box-altura" id="box-altura" class="ui-widget-content" />
			</p>
			<p>
				<label for="box-interseccion_1">Intersección de/entre</label>
				<input type="text" name="box-interseccion_1" id="box-interseccion_1" class="ui-widget-content" />
				<label for="box-interseccion_2" style="width:initial">y</label>
				<input type="text" name="box-interseccion_2" id="box-interseccion_2" class="ui-widget-content" />
			</p>
			<p>
				<label for="box-localidad">Localidad</label>
				<input type="text" name="box-localidad" id="box-localidad" class="ui-widget-content" style="width:180px" />
				<label for="box-provincia" style="width:initial">Provincia</label>
				<input type="text" name="box-provincia" id="box-provincia" class="ui-widget-content" style="width:180px" />
				<label for="box-cp" style="width:initial">Cod Pos</label>
				<input type="text" name="box-cp" id="box-cp" class="ui-widget-content" style="width:80px" />
			</p>
			<p>
				<label for="box-cruce_tren">Cruce tren</label>
				<select name="box-cruce_tren" id="box-cruce_tren" style="ui-widget-content">
					<option value="0">No</option>
					<option value="1">Sí</option>
				</select>
				<label for="box-barrera" style="width:initial;vergical-align:initial">Barrera</label>
				<select name="box-barrera" id="box-barrera" style="ui-widget-content">
					<option value="0">No</option>
					<option value="1">Sí</option>
				</select>
				<label for="box-cruce_senalizado" style="width:initial;vergical-align:initial">Cruce señalizado</label>
				<select name="box-cruce_senalizado" id="box-cruce_senalizado" style="ui-widget-content">
					<option value="0">No</option>
					<option value="1">Sí</option>
				</select>
				<label for="box-semaforo" style="width:initial;vergical-align:initial">Semáforo</label>
				<select name="box-semaforo" id="box-semaforo" style="ui-widget-content">
					<option value="0">No</option>
					<option value="1">Sí</option>
				</select>
				<label for="box-semaforo_color" style="width:initial;vergical-align:initial;margin-left:23px">Color</label>
				<input type="text" name="box-semaforo_color" id="box-semaforo_color" class="ui-widget-content" style="width:80px" />
			</p>
			<p>
				<label for="box-tipo_calzada">Tipo de calzada</label>
				<input type="text" name="box-tipo_calzada" id="box-tipo_calzada" class="ui-widget-content" />
				<label for="box-estado_calzada">Estado de calzada</label>
				<input type="text" name="box-estado_calzada" id="box-estado_calzada" class="ui-widget-content" />
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
				<label for="box-conductor_habitual">Conductor habitual del vehículo</label>
				<select name="box-conductor_habitual" id="box-conductor_habitual" style="ui-widget-content">
					<option value="1">Sí</option>
					<option value="0">No</option>
				</select>
			</p>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del asegurado</legend>
			<p style="margin-bottom:5px">
				<label for="box-asegurado_nombre"><b>Apellido y nombre</b></label>
				<span type="span" id="box-asegurado_nombre" style="display:inline-block;width:337px"></span>
				<label for="box-asegurado_sexo" style="width:initial;vergical-align:initial"><b>Sexo</b></label>
				<span type="span" id="box-asegurado_sexo"></span>
			</p>
			<p style="margin-bottom:5px">
				<label for="box-asegurado_calle"><b>Calle</b></label>
				<span type="span" id="box-asegurado_calle" style="display:inline-block;width:246px"></span>
				<label for="box-asegurado_altura" style="width:initial"><b>Altura</b></label>
				<span type="span" id="box-asegurado_altura" style="display:inline-block;width:45px"></span>
				<label for="box-asegurado_localidad" style="width:initial"><b>Localidad</b></label>
				<span type="span" id="box-asegurado_localidad" style="display:inline-block;width:180px"></span>
			</p>
			<p style="margin-bottom:5px">
				<label for="box-asegurado_provincia"><b>Provincia</b></label>
				<span type="span" id="box-asegurado_provincia" style="display:inline-block;width:180px"></span>
				<label for="box-asegurado_cp" style="width:initial"><b>Cod Pos</b></label>
				<span type="span" id="box-asegurado_cp" style="display:inline-block;width:80px"></span>
				<label for="box-asegurado_tel" style="width:initial;margin-left:20px"><b>Tel</b></label>
				<span type="span" id="box-asegurado_tel" style="display:inline-block;width:80px"></span>
				<label for="box-asegurado_cel" style="width:initial"><b>Cel</b></label>
				<span type="span" id="box-asegurado_cel" style="display:inline-block;width:80px"></span>
			</p>
			<p>
				<label for="box-asegurado_fec_nac"><b>Fecha de nacimiento</b></label>
				<span type="span" id="box-asegurado_fecha_nac" style="display:inline-block;width:80px"></span>
				<label for="box-asegurado_registro" style="width:initial;margin-left:20px"><b>N° de registro</b></label>
				<input type="text" name="box-asegurado_registro" id="box-asegurado_registro" class="ui-widget-content" style="width:100px" />
				<label for="box-asegurado_registro_venc" style="width:initial;margin-left:20px"><b>Fecha de vencimiento</b></label>
				<input type="text" name="box-asegurado_registro_venc" id="box-asegurado_registro_venc" class="ui-widget-content box-date" style="width:80px" />
			</p>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all conductor-vehiculo" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del conductor del vehículo asegurado</legend>
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
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos del vehículo asegurado</legend>
			<p>
				<label for="box-asegurado_marca"><b>Marca</b></label>
				<span type="span" id="box-asegurado_marca" style="display:inline-block;width:140px" />
				<label for="box-asegurado_modelo"><b>Modelo</b></label>
				<span type="span" id="box-asegurado_modelo" />
			</p>
			<p>
				<label for="box-asegurado_tipo"><b>Tipo</b></label>
		        <span type="span" id="box-asegurado_tipo" style="display:inline-block;width:140px" />
				<label for="box-asegurado_uso"><b>Uso</b></label>
				<span type="span" id="box-asegurado_uso" style="display:inline-block;width:140px" />
				<label for="box-asegurado_ano" style="width:initial"><b>Año</b></label>
				<span type="span" id="box-asegurado_ano" style="display:inline-block;width:50px" />
				<label for="box-asegurado_patente_0" style="width:initial"><b>Patente</b></label>
		        <span type="span"  id="box-asegurado_patente_0" /> 
				<span type="span"  id="box-asegurado_patente_1" />
			</p>
			<p>
				<label for="box-asegurado_nro_motor"><b>N° motor</b></label>
				<span type="span" id="box-asegurado_nro_motor" style="display:inline-block;width:140px" />
				<label for="box-asegurado_nro_chasis"><b>N° chasis</b></label>
				<span type="span" id="box-asegurado_nro_chasis" style="display:inline-block;width:140px" />
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
									<input type="checkbox" value="1" name="box-asegurado_danios_guardabarro_del_izq" id="box-asegurado_danios_guardabarro_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_guardabarro_del_der" id="box-asegurado_danios_guardabarro_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Faro delantero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_faro_del_izq" id="box-asegurado_danios_faro_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_faro_del_der" id="box-asegurado_danios_faro_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Puerta delantera:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_puerta_del_izq" id="box-asegurado_danios_puerta_del_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_puerta_del_der" id="box-asegurado_danios_puerta_del_der" />
								</td>
							</tr>
							<tr>
								<td><b>Puerta trasera:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_puerta_tras_izq" id="box-asegurado_danios_puerta_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_puerta_tras_der" id="box-asegurado_danios_puerta_tras_der" />
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
									<input type="checkbox" value="1" name="box-asegurado_danios_guardabarro_tras_izq" id="box-asegurado_danios_guardabarro_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_guardabarro_tras_der" id="box-asegurado_danios_guardabarro_tras_der" />
								</td>
							</tr>
							<tr>
								<td><b>Faro trasero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_faro_tras_izq" id="box-asegurado_danios_faro_tras_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_faro_tras_der" id="box-asegurado_danios_faro_tras_der" />
								</td>
							</tr>
							<tr>
								<td><b>Paragolpes delantero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_paragolpes_del_izq" id="box-asegurado_danios_paragolpes_del_izq" />
								</td>
								<td>
									
								</td>
							</tr>
							<tr>
								<td><b>Paragolpes trasero:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_paragolpes_tras_izq" id="box-asegurado_danios_paragolpes_tras_izq" />
								</td>
								<td>
									
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
									<input type="checkbox" value="1" name="box-asegurado_danios_retrovisor_izq" id="box-asegurado_danios_retrovisor_izq" />
								</td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_retrovisor_der" id="box-asegurado_danios_retrovisor_der" />
								</td>
							</tr>
							<tr>
								<td><b>Baul:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_baul_izq" id="box-asegurado_danios_baul_izq" />
								</td>
								<td>
									
								</td>
							</tr>
							<tr>
								<td><b>Capot:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_capot_izq" id="box-asegurado_danios_capot_izq" />
								</td>
								<td>
									
								</td>
							</tr>
							<tr>
								<td><b>Techo:</b></td>
								<td>
									<input type="checkbox" value="1" name="box-asegurado_danios_techo_izq" id="box-asegurado_danios_techo_izq" />
								</td>
								<td>
									
								</td>
							</tr>
						</tbody>
					</table>
				</p>
			</div>
			<div style="clear:both"></div>
			<p>
				<label for="box-asegurado_danios_observaciones" style="width:200px">Observaciones de daños:</label>
				<textarea name="box-asegurado_danios_observaciones" id="box-asegurado_danios_observaciones" style="width:100%" rows="4"></textarea>
			</p>
		</fieldset>
		<fieldset id="fieldset-datos-terceros" class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Datos de terceros</legend>
			<p>
				<button id="btnNuevoDatosTercero">Agregar datos de tercero</button>
			</p>
			<div id="divBoxListDatosTerceros" style="min-height:30px">
			    Cargando...
			</div>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Detalle del siniestro</legend>
			<p>
				<label><b>Tipo de accidente</b></label>
			
				<label for="box-detalle_tipo_frontal" style="text-align:right;width:100px">Frontal</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_frontal" id="box-detalle_tipo_frontal" />
			
				<label for="box-detalle_tipo_posterior" style="text-align:right;width:100px">Posterior</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_posterior" id="box-detalle_tipo_posterior" />
			
				<label for="box-detalle_tipo_lateral" style="text-align:right;width:100px">Lateral</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_lateral" id="box-detalle_tipo_lateral" />
			
				<label for="box-detalle_tipo_en_cadena" style="text-align:right;width:100px">En cadena</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_en_cadena" id="box-detalle_tipo_en_cadena" />
			
				<label for="box-detalle_tipo_vuelco" style="text-align:right;width:100px">Vuelco</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_vuelco" id="box-detalle_tipo_vuelco" />
			</p>
			<p>
				<label>&nbsp;</label>
			
				<label for="box-detalle_tipo_desplazamiento" style="text-align:right;width:100px">Desplazamiento</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_desplazamiento" id="box-detalle_tipo_desplazamiento" />
			
				<label for="box-detalle_tipo_inmersion" style="text-align:right;width:100px">Inmersión</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_inmersion" id="box-detalle_tipo_inmersion" />
			
				<label for="box-detalle_tipo_incendio" style="text-align:right;width:100px">Incendio</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_incendio" id="box-detalle_tipo_incendio" />
			
				<label for="box-detalle_tipo_explosion" style="text-align:right;width:100px">Explosión</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_explosion" id="box-detalle_tipo_explosion" />
			
				<label for="box-detalle_tipo_danio_carga" style="text-align:right;width:100px">Daño en la carga</label>
				<input type="checkbox" value="1" name="box-detalle_tipo_danio_carga" id="box-detalle_tipo_danio_carga" />
			</p>
			<p>
				<table>
					<tbody>
						<tr>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_autopista" style="text-align:right;width:initial">Autopista</label>
								<input type="checkbox" value="1" name="box-detalle_autopista" id="box-detalle_autopista" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_calle" style="text-align:right;width:initial">Calle</label>
								<input type="checkbox" value="1" name="box-detalle_calle" id="box-detalle_calle" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_avenida" style="text-align:right;width:initial">Avenida</label>
								<input type="checkbox" value="1" name="box-detalle_avenida" id="box-detalle_avenida" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_curva" style="text-align:right;width:initial">Curva</label>
								<input type="checkbox" value="1" name="box-detalle_curva" id="box-detalle_curva" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_pendiente" style="text-align:right;width:initial">Pendiente</label>
								<input type="checkbox" value="1" name="box-detalle_pendiente" id="box-detalle_pendiente" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_tunel" style="text-align:right;width:initial">Túnel</label>
								<input type="checkbox" value="1" name="box-detalle_tunel" id="box-detalle_tunel" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_sobre_puente" style="text-align:right;width:initial">Sobre<br>puente</label>
								<input type="checkbox" value="1" name="box-detalle_sobre_puente" id="box-detalle_sobre_puente" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_otro" style="text-align:right;width:initial">Otro</label>
								<input type="checkbox" value="1" name="box-detalle_otro" id="box-detalle_otro" />
							</td>
						</tr>
						<tr>
							<td style="width:100px;text-align:right">
								<label style="width:initial"><b>Colisión con</b></label>
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_vehiculo" style="text-align:right;width:initial">Vehículo</label>
								<input type="checkbox" value="1" name="box-detalle_colision_vehiculo" id="box-detalle_colision_vehiculo" value="1" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_peaton" style="text-align:right;width:initial">Peatón</label>
								<input type="checkbox" value="1" name="box-detalle_colision_peaton" id="box-detalle_colision_peaton" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_trans_publico" style="text-align:right;width:initial">Trans.<br>Público</label>
								<input type="checkbox" value="1" name="box-detalle_colision_trans_publico" id="box-detalle_colision_trans_publico" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_edificio" style="text-align:right;width:initial">Edificio</label>
								<input type="checkbox" value="1" name="box-detalle_colision_edificio" id="box-detalle_colision_edificio" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_columna" style="text-align:right;width:initial">Columna</label>
								<input type="checkbox" value="1" name="box-detalle_colision_columna" id="box-detalle_colision_columna" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_animal" style="text-align:right;width:initial">Animal</label>
								<input type="checkbox" value="1" name="box-detalle_colision_animal" id="box-detalle_colision_animal" />
							</td>
							<td style="width:100px;text-align:right">
								<label for="box-detalle_colision_otro" style="text-align:right;width:initial">Otro</label>
								<input type="checkbox" value="1" name="box-detalle_colision_otro" id="box-detalle_colision_otro" />
							</td>
						</tr>
					</tbody>
				</table>
			</p>
			<div style="width:30%;float:left" id="croquis">
				<div id="droppable" style="width:200px;height:200px;background: center no-repeat url('siniestros/croquis/plano.png');background-size:200px 200px; position:relative"></div>
				<input type="hidden" name="box-croquis_img-noupper" id="box-croquis_img-noupper" />
				<div style="width:200px">
					<div id="croquis-autos">

					</div>
					<div style="clear:both"></div>
					<div id="croquis-motos">
						<div style="float:left;width:20px;padding:2px">
							<div class="draggable croquis-moto" style="width:20px;height:9px;background: center no-repeat url('siniestros/croquis/moto.png');background-size:20px 9px"></div>
						</div>
					</div>
					<div id="croquis-peatones">
						<div style="float:left;width:9px;padding:2px">
							<div class="draggable croquis-peaton" style="width:9px;height:9px;background: center no-repeat url('siniestros/croquis/peaton.png');background-size:9px 9px"></div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/ns.png');background-size:16px 15px" direction="ns"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/sn.png');background-size:16px 15px" direction="sn"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/eo.png');background-size:16px 15px" direction="eo"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/oe.png');background-size:16px 15px" direction="oe"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/on.png');background-size:16px 15px" direction="on"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/en.png');background-size:16px 15px" direction="en"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/os.png');background-size:16px 15px" direction="os"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/es.png');background-size:16px 15px" direction="es"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/no.png');background-size:16px 15px" direction="no"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/ne.png');background-size:16px 15px" direction="ne"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/so.png');background-size:16px 15px" direction="so"></div>
						</div>
						<div style="float:left;width:16px;padding:2px">
							<div class="draggable croquis-direccion" style="width:16px;height:15px;background: center no-repeat url('siniestros/croquis/se.png');background-size:16px 15px" direction="se"></div>
						</div>
					</div>
				</div>
			</div>
			<div style="width:70%;float:left">
				<textarea id="box-siniestro_detalle" name="box-siniestro_detalle" class="ui-widget-content" style="width:98%" rows="15"></textarea>
			</div>
			
		</fieldset>
		<fieldset id="fieldset-lesiones-terceros" class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Lesiones a terceros</legend>
			<p>
				<button id="btnNuevoLesionesTercero">Agregar lesiones a terceros</button>
			</p>
			<div id="divBoxListLesionesTerceros" style="min-height:30px">
			    Cargando...
			</div>
		</fieldset>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Inspección del vehículo asegurado</legend>
			<p>
				<label for="box-inspeccion_taller">Nombre del taller</label>
				<input type="text" name="box-inspeccion_taller" id="box-inspeccion_taller" class="ui-widget-content" />
			</p>
			<p>
				<label for="box-inspeccion_contacto">Contacto</label>
				<input type="text" name="box-inspeccion_contacto" id="box-inspeccion_contacto" class="ui-widget-content" />
			</p>
			<p>
				<label for="box-inspeccion_calle">Calle</label>
				<input type="text" name="box-inspeccion_calle" id="box-inspeccion_calle" class="ui-widget-content" />
				<label for="box-inspeccion_altura" style="width:initial">Altura</label>
				<input type="text" name="box-inspeccion_altura" id="box-inspeccion_altura" class="ui-widget-content" style="width:40px" />
				<label for="box-inspeccion_localidad" style="width:initial">Localidad</label>
				<input type="text" name="box-inspeccion_localidad" id="box-inspeccion_localidad" class="ui-widget-content" />
			</p>
            <p>
                <label for="box-inspeccion_fecha">Fecha</label>
                <input type="text" name="box-inspeccion_fecha" id="box-inspeccion_fecha" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
				<label for="box-inspeccion_telefono" style="width:initial">Teléfono</label>
				<input type="text" name="box-inspeccion_telefono" id="box-inspeccion_telefono" class="ui-widget-content" />
            </p>
		</fieldset>
		<input type="hidden" name="box-siniestro_id" id="box-siniestro_id" value="" />
		<input type="hidden" name="automotor_id" id="box-automotor_id" />
		<input type="hidden" name="cliente_id" id="box-cliente_id" />
	</form>
	
    <!-- Acciones -->
    <p align="center" style="margin-top:20px">
		<button name="btnBox" id="btnBox">Guardar</button>
		<button name="btnDatos" id="btnDatos">Atrás</button>
    </p>
    <!-- Nota -->
    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>
