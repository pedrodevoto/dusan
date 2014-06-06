<script language="javascript" type="text/javascript">
<!--
	// Functions
	var polDetInit = function() {
		if ($('#box-prendado').is(":checked")) {
			$('#box-acreedor_rs').attr("readonly", false);
			$('#box-acreedor_cuit').attr("readonly", false);			
		}		
		if ($('#box-seguro_cobertura_tipo_id').find(':selected').text() == 'DP') {
			$('#box-franquicia').attr("readonly", false);
		};
		$('#box-venc_oblea').datepicker({
			dateFormat: 'yy-mm-dd',
			changeYear: true,									
			yearRange: "c-10:c+10",
			changeMonth: true										
		});				
	}
	function isInt(n) {
	   return n % 1 === 0;
	}		
	var calculateTotal = function() {
		var total = 0;
		$(".calculator").each(function() {	
			var value = $(this).val();
			if (!isNaN(parseInt(value)) && isInt(value) && value>=0) {	
				total += parseInt(value);
			}
		});
		$('#box-valor_total, #box-suma_asegurada').val(total);
	}
	function populateCarroceria(field, context, automotor_tipo) {
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-automotor_carroceria.php?id="+automotor_tipo,
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else {				
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	
	function populateCobertura(cobertura_id) {
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-segcob_info.php?id="+cobertura_id,
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else {				
					var options = ''; 
					var valid = true;
					$.each(j, function(key, value) { 
						switch (key) {
							case 'seguro_cobertura_tipo_anios_de':
								if (value && $("#box-ano").val() < value) {
									valid = false;
								}
								break;
							case 'seguro_cobertura_tipo_anios_a':
								if (value && $("#box-ano").val() > value) {
									valid = false;
								}
								break;
							default:
								$("#box-"+key).val(value);
								break;
						}
					});
					if (!valid) {
						alert('Advertencia: el año del automotor está fuera del rango de años recomendado por la cobertura');
					}
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();
	}
	
	// On Change
	$('#box-prendado').change(function(){
		if ($(this).is(":checked")) {
			$('#box-acreedor_rs').attr("readonly", false);
			$('#box-acreedor_cuit').attr("readonly", false);			
		} else {
			$('#box-acreedor_rs').val('');
			$('#box-acreedor_cuit').val('');			
			$('#box-acreedor_rs').attr("readonly", true);
			$('#box-acreedor_cuit').attr("readonly", true);			
		}
	});	
	$('#box-seguro_cobertura_tipo_id').change(function(){
		if ($(this).find(':selected').text().trim().match(/(^DP|^D2|^D4|^D)[$\s]/g)) {
			$('#box-franquicia').attr("readonly", false).addClass('required');
		} else {
			$('#box-franquicia').val('').attr("readonly", true).removeClass('required');			
		}
		populateCobertura($('#box-seguro_cobertura_tipo_id').val());
	});
	$('#box-automotor_tipo_id').change(function(){
		$('box-automotor_carroceria_id').html('<option value="">Cargando...</option>');
		populateCarroceria('box-automotor_carroceria_id', 'box', $(this).val());
	});
	$('.calculator').keyup(function() {		
		calculateTotal();
	});	
	
	$('.addFoto').click(function() {
		var object = $(this).attr("object");
		var j = 0;
		$('#items-'+object+' p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var item = '<p id="'+j+'"><label for="box-'+object+'_foto"> </label> <input type="file" name="box-'+object+'_foto[]" id="box-'+object+'_foto" class="ui-widget-content" style="width:220px" /></p>';
		$('#items-'+object).append(item);
		return false;
	})
	$('#box-combustible').change(function(){
		$('#box-nro_oblea, #box-nro_regulador, #box-marca_regulador, #box-marca_cilindro, #box-venc_oblea, #box-nro_tubo').prop('readonly', $(this).val() == 'Diesel');
		$('#box-gnc_foto').prop('disabled', $(this).val() == 'Diesel');
	});
	$('#box-pedido_instalacion').change(function() {
		$('#box-pedido_instalacion_direccion, #box-pedido_instalacion_horario, #box-pedido_instalacion_telefono, #box-pedido_instalacion_observaciones').prop('readonly', !$(this).prop('checked'));
		if ($(this).prop('checked')) $('#box-pedido_instalacion_direccion').focus();
	});
	$('#box-cert_rodamiento').change(function() {
		$('#box-cert_rodamiento_foto').prop('disabled', !$(this).prop('checked'));
	});
	$('#box-valor_gnc').keyup(function() {
		if (!isNaN(parseInt($('#box-valor_gnc').val()))) {
			$('#box-valor_gnc2').val(parseInt($('#box-valor_gnc').val()));
		}
	});
//--> 
</script>
<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px" enctype="multipart/form-data">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">    
	    <legend class="ui-widget ui-widget-header ui-corner-all">Detalle del vehículo</legend>      
	    <p>
	        <label for="box-automotor_marca_id">Marca *</label>
	        <select name="box-automotor_marca_id" id="box-automotor_marca_id" class="ui-widget-content required" style="width:223px">
				<option value="">Seleccionar</option>
				<?php showMarcas(); ?>
			</select>
		</p>
	    <p>
	        <label for="box-modelo">Modelo *</label>
	        <input type="text" name="box-modelo" id="box-modelo" maxlength="100" class="ui-widget-content required" style="width:220px" />
	    </p>
	    <p>
	        <label for="box-ano">Año *</label>
	        <select name="box-ano" id="box-ano" class="ui-widget-content required">
				<option value="">Seleccionar</option>
				<?php showYears(1); ?>
			</select>
	    </p>
	    <p>
	        <label for="box-valor_vehiculo">Valor Vehículo *</label>
	        <input type="text" name="box-valor_vehiculo" id="box-valor_vehiculo" maxlength="8" class="ui-widget-content required calculator" style="width:120px" digits="true" min="0" max="16777215" value="0" /> <span style="color:red">(Cargar acá el valor del vehículo)</span>
	    </p>
		<p> 
			<label for="box-castigado">Castigado </label><input type="checkbox" name="box-castigado" id="box-castigado" />
			<label for="box-infoauto" style="margin-left:10px">Infoauto *</label><input type="checkbox" name="box-infoauto" id="box-infoauto" value="1" />
		</p>
	</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">    
    <legend class="ui-widget ui-widget-header ui-corner-all">General</legend>      
    
	<div style="float:left;width:50%">
		<p>
	        <label for="box-patente_0">Patente *</label>
	        <input type="text" name="box-patente_0" id="box-patente_0" maxlength="3" class="ui-widget-content required" style="width:30px" /> 
			<input type="text" name="box-patente_1" id="box-patente_1" maxlength="3" class="ui-widget-content required" style="width:30px" />
			<span id="msg_patente" style="color:red"></span>
	    </p>
	    <p>
	        <label for="box-automotor_tipo_id">Tipo *</label>
	        <select name="box-automotor_tipo_id" id="box-automotor_tipo_id" class="ui-widget-content required" style="width:160px">    
	            <option value="">Seleccione</option>    
	            <?php showAutomotorTipo(); ?>
	        </select>
	    </p>
	    <p>
	        <label for="box-uso">Uso *</label>
	        <select name="box-uso" id="box-uso" class="ui-widget-content required" style="width:160px">    
	            <option value="">Seleccione</option>    
	            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'uso', 'select', 'Particular'); ?>
	        </select>
	    </p>

	    <p>
	        <label for="box-automotor_carroceria_id">Carrocería *</label>
	        <select name="box-automotor_carroceria_id" id="box-automotor_carroceria_id" class="ui-widget-content required" style="width:160px">    
	            <option value="">Seleccione</option>    
	            <?php showCarroceria($poliza_id); ?>        
	        </select>
	    </p>
	    <p>
	        <label for="box-combustible">Combustible *</label>
	        <select name="box-combustible" id="box-combustible" class="ui-widget-content required" style="width:160px">    
	            <option value="">Seleccione</option>    
	            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'combustible', 'select', 'Nafta'); ?>        
	        </select>
	    </p>
		<p>
			<label for="box-cert_rodamiento">Certificado de no rodamiento</label>
			<input type="checkbox" name="box-cert_rodamiento" id="box-cert_rodamiento" value="1" /> 
	        <label for="box-0km" style="width:30px">0 KM</label>
	        <input type="checkbox" name="box-0km" id="box-0km" value="1" />
		</p>
	    <p>
	        <label for="box-importado">Importado</label>
	        <input type="checkbox" name="box-importado" id="box-importado" value="1" />
	    </p>
	    <p>
	        <label for="box-nro_motor">Nº Motor *</label>
	        <input type="text" name="box-nro_motor" id="box-nro_motor" maxlength="255" class="ui-widget-content required" style="width:200px" />
	    </p>
	    <p>
	        <label for="box-nro_chasis">Nº Chasis *</label>
	        <input type="text" name="box-nro_chasis" id="box-nro_chasis" maxlength="255" class="ui-widget-content required" style="width:200px" />
	    </p>
	</div>
	<div style="float:left;width:50%">
	    <p>
	        <label for="box-chapa">Chapa</label>
	        <select name="box-chapa" id="box-chapa" class="ui-widget-content" style="width:110px">    
	            <option value="">No Definido</option>    
	            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'chapa', 'select', 'Bueno'); ?>     
	        </select>
	    </p>
	    <p>
	        <label for="box-pintura">Pintura</label>
	        <select name="box-pintura" id="box-pintura" class="ui-widget-content" style="width:110px">    
	            <option value="">No Definido</option>    
	            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'pintura', 'select', 'Bueno'); ?>   
	        </select>
	    </p>
	    <p>
	        <label for="box-tipo_pintura">Tipo Pintura</label>
	        <select name="box-tipo_pintura" id="box-tipo_pintura" class="ui-widget-content" style="width:110px">    
	            <option value="">No Definido</option>    
	            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'tipo_pintura', 'select', 'Bicapa'); ?>  
	        </select>
	    </p>
	    <p>
	        <label for="box-tapizado">Tapizado</label>
	        <select name="box-tapizado" id="box-tapizado" class="ui-widget-content" style="width:110px">    
	            <option value="">No Definido</option>    
	            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'tapizado', 'select', 'Tela'); ?>   
	        </select>
	    </p>
	    <p>
	        <label for="box-color">Color *</label>
			<select name="box-color" id="box-color" class="ui-widget-content required" style="width:106px">
				<option>Seleccione</option>
				<option value="BLANCO">BLANCO</option>
				<option value="NEGRO">NEGRO</option>
				<option value="GRIS">GRIS</option>
				<option value="ROJO">ROJO</option>
				<option value="AZUL">AZUL</option>
				<option value="AMARILLO">AMARILLO</option>
				<option value="VERDE">VERDE</option>
				<option value="NARANJA">NARANJA</option>
				<option value="MARRON">MARRON</option>
				<option value="VIOLETA">VIOLETA</option>
				<option value="CELESTE">CELESTE</option>
			</select>
	    </p>
	    <p>
	        <label for="box-zona_riesgo_id">Zona de Riesgo *</label>
	        <select name="box-zona_riesgo_id" id="box-zona_riesgo_id" class="ui-widget-content required" style="width:110px">    
	            <option value="">Seleccione</option>    
	            <?php //enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'zona_riesgo', 'select'); ?>   
				<?php showZonasRiesgo($row_Recordset1['seguro_id']); ?>
	        </select>
	    </p>
	    <p>
	        <label for="box-prendado">Prendado *</label>
	        <input type="checkbox" name="box-prendado" id="box-prendado" value="1" />
	    </p>
	    <p>
	        <label for="box-acreedor_rs">Razón Social</label>
	        <input type="text" name="box-acreedor_rs" id="box-acreedor_rs" maxlength="75" class="ui-widget-content" style="width:220px" readonly="readonly" />
	    </p>
	    <p>
	        <label for="box-acreedor_cuit">CUIT Nº</label>
	        <input type="text" name="box-acreedor_cuit" id="box-acreedor_cuit" maxlength="15" class="ui-widget-content" style="width:220px" readonly="readonly" />
	    </p>
	    <p>
	        <label for="box-observaciones">Observaciones</label>
	        <textarea name="box-observaciones" id="box-observaciones" rows="3" class="ui-widget-content" style="width:220px"></textarea>
	    </p>                            
	</div>
</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">  
    <legend class="ui-widget ui-widget-header ui-corner-all">Equipamiento *</legend>    
        <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top">
                    <input type="checkbox" name="box-alarma" id="box-alarma" value="1" /><label for="box-alarma" class="secondary">Alarma</label><br />                
                    <input type="checkbox" name="box-corta_corriente" id="box-corta_corriente" value="1" /><label for="box-corta_corriente" class="secondary">Corta Corriente</label><br />                
                    <input type="checkbox" name="box-corta_nafta" id="box-corta_nafta" value="1" /><label for="box-corta_nafta" class="secondary">Corta Nafta</label><br />                
                    <input type="checkbox" name="box-traba_volante" id="box-traba_volante" value="1" /><label for="box-traba_volante" class="secondary">Traba Volante</label><br />                
                    <input type="checkbox" name="box-matafuego" id="box-matafuego" value="1" /><label for="box-matafuego" class="secondary">Matafuego</label><br />                
                    <input type="checkbox" name="box-tuercas" id="box-tuercas" value="1" /><label for="box-tuercas" class="secondary">Tuercas</label><br />                
                </td>
                <td valign="top"> 
                    <input type="checkbox" name="box-antena" id="box-antena" value="1" /><label for="box-antena" class="secondary">Antena</label><br />                
                    <input type="checkbox" name="box-estereo" id="box-estereo" value="1" /><label for="box-estereo" class="secondary">Estereo</label><br />                
                    <input type="checkbox" name="box-parlantes" id="box-parlantes" value="1" /><label for="box-parlantes" class="secondary">Parlantes</label><br />                
                    <input type="checkbox" name="box-aire" id="box-aire" value="1" /><label for="box-aire" class="secondary">Aire</label><br />                
                    <input type="checkbox" name="box-cristales_electricos" id="box-cristales_electricos" value="1" /><label for="box-cristales_electricos" class="secondary">Cristales Elec.</label><br />                
                    <input type="checkbox" name="box-faros_adicionales" id="box-faros_adicionales" value="1" /><label for="box-faros_adicionales" class="secondary">Faros Adic.</label><br />                
                    <input type="checkbox" name="box-cierre_sincro" id="box-cierre_sincro" value="1" /><label for="box-cierre_sincro" class="secondary">Cierre Sincro</label><br />                
                </td>
                <td valign="top">
                    <input type="checkbox" name="box-techo_corredizo" id="box-techo_corredizo" value="1" /><label for="box-techo_corredizo">Techo Corredizo</label><br />                
                    <input type="checkbox" name="box-direccion_hidraulica" id="box-direccion_hidraulica" value="1" /><label for="box-direccion_hidraulica">Dir. Hidráulica</label><br />                
                    <input type="checkbox" name="box-frenos_abs" id="box-frenos_abs" value="1" /><label for="box-frenos_abs">Frenos ABS</label><br />                
                    <input type="checkbox" name="box-airbag" id="box-airbag" value="1" /><label for="box-airbag">Airbag</label><br />                
                    <input type="checkbox" name="box-cristales_tonalizados" id="box-cristales_tonalizados" value="1" /><label for="box-cristales_tonalizados">C. Tonalizados</label><br />                
                    <input type="checkbox" name="box-gps" id="box-gps" value="1" /><label for="box-gps">GPS</label>
                </td>
            </tr>
        </table>
</fieldset>
<div style="float:left;width:50%">
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">    
	    <legend class="ui-widget ui-widget-header ui-corner-all">Equipo Rastreo</legend>      
	    <p>
	        <label for="box-equipo_rastreo">Email</label><input type="checkbox" name="box-equipo_rastreo" id="box-equipo_rastreo" />
		</p>
		<p>
			<label for="box-equipo_rastreo_pedido_id">Pedir</label>
			<select name="box-equipo_rastreo_pedido_id" id="box-equipo_rastreo_pedido_id" class="ui-widget-content" style="width:110px">
				<option value="">Ninguno</option>
				<?php showEquipoRastreoPedido(); ?>
			</select>
		<p>
	        <label for="box-equipo_rastreo_id">Marca</label>
	        <select name="box-equipo_rastreo_id" id="box-equipo_rastreo_id" class="ui-widget-content" style="width:110px">    
	            <option value="">Ninguno</option>    
	            <?php showEquipoRastreo(); ?>
	        </select>
	    </p>
    
	</fieldset>	
</div>
<div style="float:left;width:50%">
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
	    <legend class="ui-widget ui-widget-header ui-corner-all">Micrograbado</legend>
	    <p>
			<label for="box-micro_grabado">Micro Grabado</label>
			<input type="checkbox" name="box-micro_grabado" id="box-micro_grabado" value="1" />
		<p>
	        <label for="box-cupon_vintrak">Nº Cupón Vintrak</label>
	        <input type="text" name="box-cupon_vintrak" id="box-cupon_vintrak" maxlength="100" class="ui-widget-content" style="width:200px" />
	    </p>
		<p>
			<label for="box-cupon_vintrak_fecha">Fecha de entrega de cupón</label>
	        <input type="text" name="box-cupon_vintrak_fecha" id="box-cupon_vintrak_fecha" maxlength="10" class="ui-widget-content box-date dateAR" style="width:80px" />
		</p>
	</fieldset>
</div>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
    <legend class="ui-widget ui-widget-header ui-corner-all">Cubiertas</legend>
    <p>
        <label for="box-cubiertas_marca">Marca</label>
        <input type="text" name="box-cubiertas_marca" id="box-cubiertas_marca" maxlength="100" class="ui-widget-content" style="width:215px" />
    </p>           
    <p>
        <label for="box-cubiertas_medidas">Medidas</label>
        <input type="text" name="box-cubiertas_medidas" id="box-cubiertas_medidas" maxlength="50" class="ui-widget-content" style="width:215px" />
    </p>
	<div style="float:left;width30%">
	    <p>
	        <label for="box-cubiertas_desgaste_di">Desg. Del/Izq *</label>
	        <select name="box-cubiertas_desgaste_di" id="box-cubiertas_desgaste_di" class="ui-widget-content required" style="width:90px">
	        	<option value="">Seleccione</option>
	            <option value="0">0%</option>
	            <option value="10">10%</option>
	            <option value="20" selected>20%</option>
	            <option value="30">30%</option>
	            <option value="40">40%</option>
	            <option value="50">50%</option>            
	        </select>        
	    </p>
	    <p>
	        <label for="box-cubiertas_desgaste_dd">Desg. Del/Der *</label>
	        <select name="box-cubiertas_desgaste_dd" id="box-cubiertas_desgaste_dd" class="ui-widget-content required" style="width:90px">
	        	<option value="">Seleccione</option>
	            <option value="0">0%</option>
	            <option value="10">10%</option>
	            <option value="20" selected>20%</option>
	            <option value="30">30%</option>
	            <option value="40">40%</option>
	            <option value="50">50%</option>            
	        </select>        
	    </p>
	</div>
	<div style="float:left;width30%">
	    <p>
	        <label for="box-cubiertas_desgaste_ti">Desg. Tra/Izq *</label>
	        <select name="box-cubiertas_desgaste_ti" id="box-cubiertas_desgaste_ti" class="ui-widget-content required" style="width:90px">
	        	<option value="">Seleccione</option>
	            <option value="0">0%</option>
	            <option value="10">10%</option>
	            <option value="20" selected>20%</option>
	            <option value="30">30%</option>
	            <option value="40">40%</option>
	            <option value="50">50%</option>            
	        </select>        
	    </p>
	    <p>
	        <label for="box-cubiertas_desgaste_td">Desg. Tra/Der *</label>
	        <select name="box-cubiertas_desgaste_td" id="box-cubiertas_desgaste_td" class="ui-widget-content required" style="width:90px">
	        	<option value="">Seleccione</option>
	            <option value="0">0%</option>
	            <option value="10">10%</option>
	            <option value="20" selected>20%</option>
	            <option value="30">30%</option>
	            <option value="40">40%</option>
	            <option value="50">50%</option>            
	        </select>        
	    </p>
	</div>
	<div style="float:left;width30%">
	    <p>
	        <label for="box-cubiertas_desgaste_1ei">Desg. 1E/Izq</label>
	        <select name="box-cubiertas_desgaste_1ei" id="box-cubiertas_desgaste_1ei" class="ui-widget-content" style="width:90px">
	        	<option value="">N/A</option>
	            <option value="0">0%</option>
	            <option value="10">10%</option>
	            <option value="20">20%</option>
	            <option value="30">30%</option>
	            <option value="40">40%</option>
	            <option value="50">50%</option>            
	        </select>        
	    </p>
	    <p>
	        <label for="box-cubiertas_desgaste_1ed">Desg. 1E/Der</label>
	        <select name="box-cubiertas_desgaste_1ed" id="box-cubiertas_desgaste_1ed" class="ui-widget-content" style="width:90px">
	        	<option value="">N/A</option>
	            <option value="0">0%</option>
	            <option value="10">10%</option>
	            <option value="20">20%</option>
	            <option value="30">30%</option>
	            <option value="40">40%</option>
	            <option value="50">50%</option>            
	        </select>        
	    </p>
	</div>
    <p>
        <label for="box-cubiertas_desgaste_auxilio">Desg. Aux. *</label>
        <select name="box-cubiertas_desgaste_auxilio" id="box-cubiertas_desgaste_auxilio" class="ui-widget-content required" style="width:90px">
        	<option value="">Seleccione</option>
            <option value="0">0%</option>
            <option value="10">10%</option>
            <option value="20" selected>20%</option>
            <option value="30">30%</option>
            <option value="40">40%</option>
            <option value="50">50%</option>            
        </select>        
    </p>                    
</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">  
    <legend class="ui-widget ui-widget-header ui-corner-all">GNC</legend> 
	<p>
		<label for="box-gnc_flag"><b>Tiene GNC?</b></label>
		<input type="checkbox" name="box-gnc_flag" id="box-gnc_flag" class="ui-widget-content" />
	</p>
    <div style="float:left;width:50%">
		<p>
	        <label for="box-nro_oblea">Nº Oblea</label>
	        <input type="text" name="box-nro_oblea" id="box-nro_oblea" maxlength="50" class="ui-widget-content" style="width:220px" />
	    </p>
	    <p>
	        <label for="box-marca_cilindro">Marca Cilindro</label>
	        <input type="text" name="box-marca_cilindro" id="box-marca_cilindro" maxlength="50" class="ui-widget-content" style="width:220px" />
	    </p>
	    <p>
	        <label for="box-venc_oblea">Vencimiento Oblea</label>
	        <input type="text" name="box-venc_oblea" id="box-venc_oblea" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	    </p>
	</div>
	<div style="float:left;width:50%">
	    <p>
	        <label for="box-nro_regulador">Nº Regulador</label>
	        <input type="text" name="box-nro_regulador" id="box-nro_regulador" maxlength="50" class="ui-widget-content" style="width:220px" />
	    </p>
	    <p>
	        <label for="box-marca_regulador">Marca Regulador</label>
	        <input type="text" name="box-marca_regulador" id="box-marca_regulador" maxlength="50" class="ui-widget-content" style="width:220px" />
	    </p>
	    <p>
	        <label for="box-nro_tubo">Nº Tubo</label>
	        <input type="text" name="box-nro_tubo" id="box-nro_tubo" maxlength="50" class="ui-widget-content" style="width:220px" />
	    </p>                        
	</div>
    <p>
        <label for="box-valor_gnc">Valor GNC *</label>
        <input type="text" name="box-valor_gnc" id="box-valor_gnc" maxlength="8" class="ui-widget-content required calculator" style="width:120px" digits="true" min="0" max="16777215" value="0" /> <span style="color:red">(Cargar acá el valor asegurador del GNC)</span>
    </p>
</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">  
    <legend class="ui-widget ui-widget-header ui-corner-all">Accesorios</legend> 
	<p>
		<input type="button" id="box-automotor_accesorios_add" value="Agregar" /> 
		Suma asegurada total: <span id="automotor_accesorios_total"></span>
	</p>
	<div id="automotor_accesorios">
	</div>
</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">    
    <legend class="ui-widget ui-widget-header ui-corner-all">Cobertura y sumas aseguradas</legend>
	<div style="float:left;width:50%">
	    <?php if($row_Recordset1['seguro_id']==4):?>
		<p>
	        <label for="box-producto_id">Producto *</label>
	        <select name="box-producto_id" id="box-producto_id" class="ui-widget-content required" style="width:160px">
	        	<option value="">Seleccione</option>
				<?php showProducto(); ?>
	        </select>        
	    </p>
		<?php endif;?>
		<p>
	        <label for="box-seguro_cobertura_tipo_id">Cobertura *</label>
	        <select name="box-seguro_cobertura_tipo_id" id="box-seguro_cobertura_tipo_id" class="ui-widget-content required" style="width:160px">
	        	<option value="">Seleccione</option>
				<?php showCobertura($row_Recordset1['seguro_id']); ?>
	        </select>        
	    </p>
	    <p>
	        <label for="box-franquicia">Franquicia</label>
	        <input type="text" name="box-franquicia" id="box-franquicia" maxlength="5" class="ui-widget-content" style="width:120px" digits="true" min="0" max="99999" readonly="readonly" />
	    </p>    
	    <p>
	        <label for="box-seguro_cobertura_tipo_limite_rc_id">Límite Resp. Civil *</label>
	        <select name="box-seguro_cobertura_tipo_limite_rc_id" id="box-seguro_cobertura_tipo_limite_rc_id" class="ui-widget-content required" style="width:120px">
	        	<option value="">Seleccione</option>
	            <?php showLimiteRC(); ?>
	        </select>        
	    </p>    
	    <p>
	        <label for="box-servicio_grua">Servicio de Grúa</label>
	        <input type="text" name="box-servicio_grua" id="box-servicio_grua" maxlength="3" class="ui-widget-content" style="width:60px" digits="true" min="1" max="255" />
	    </p> 
	</div>
	<div style="float:left;width:50%">
        <p>
            <label for="box-ajuste">Ajuste *</label>
            <select name="box-ajuste" id="box-ajuste" class="ui-widget-content required" style="width:130px">
                <option value="">Seleccione</option>                
                <option value="0">0%</option>
                <option value="10">10%</option>
                <option value="20" selected>20%</option>
                <option value="30">30%</option>
			</select>				
        </p>
	    <p>
	        <label for="box-suma_asegurada">Valor Vehículo ^</label>
	        <input type="text" name="box-suma_asegurada" id="box-suma_asegurada" maxlength="8" class="ui-widget-content required" style="width:120px" readonly="readonly" value="0" />
	    </p>
	    <p>
	        <label for="box-valor_gnc2">Valor GNC ^</label>
	        <input type="text" name="box-valor_gnc2" id="box-valor_gnc2" maxlength="8" class="ui-widget-content" style="width:120px" digits="true" min="0" max="16777215" value="0" readonly />
	    </p>
	    <p>
	        <label for="box-valor_accesorios">Valor Accesorios ^</label>
	        <input type="number" name="box-valor_accesorios" id="box-valor_accesorios" class="ui-widget-content required calculator" style="width:120px" min="0" max="16777215" value="0" readonly />
	    </p>
	    <p>
	        <label for="box-valor_total"><strong>Suma Asegurada Total ^</strong></label>
	        <input type="text" name="box-valor_total" id="box-valor_total" maxlength="8" class="ui-widget-content required" style="width:120px" readonly="readonly" value="0" />
	    </p>                            
	</div>
</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
	<legend class="ui-widget ui-widget-header ui-corner-all">Pedido de inspección</legend>
    <p>
        <label for="box-pedido_instalacion">Pedir inspección?</label>
        Sí <input type="checkbox" name="box-pedido_instalacion" id="box-pedido_instalacion" value="1" />
    </p>
	<p>
		<label for="box-pedido_instalacion_direccion">Dirección</label>
		<input type="text" name="box-pedido_instalacion_direccion" id="box-pedido_instalacion_direccion" class="ui-widget-content" style="width:220px" readonly />
	</p>
	<p>
		<label for="box-pedido_instalacion_horario">Horario de atención</label>
		<input type="text" name="box-pedido_instalacion_horario" id="box-pedido_instalacion_horario" class="ui-widget-content" style="width:120px" readonly />
	</p>
	<p>
		<label for="box-pedido_instalacion_telefono">Teléfono de contacto</label>
		<input type="text" name="box-pedido_instalacion_telefono" id="box-pedido_instalacion_telefono" class="ui-widget-content" style="width:120px" readonly />
	</p>
	<p>
		<label for="box-pedido_instalacion_observaciones">Observaciones</label>
		<textarea name="box-pedido_instalacion_observaciones" id="box-pedido_instalacion_observaciones" class="ui-widget-content" style="width:220px" maxlength="500" readonly /> </textarea>
	</p>
</fieldset>  