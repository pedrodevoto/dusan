<script language="javascript" type="text/javascript">
<!--
	// Functions
	var polDetInit = function() {
		if ($('#box-prendado').is(":checked")) {
			$('#box-acreedor_rs').attr("readonly", false);
			$('#box-acreedor_cuit').attr("readonly", false);			
		}		
		if ($('#box-cobertura_tipo_id').find(':selected').text() === 'D') {
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
	$('#box-cobertura_tipo_id').change(function(){
		if ($(this).find(':selected').text() === 'D') {
			$('#box-franquicia').attr("readonly", false).addClass('required');
		} else {
			$('#box-franquicia').val('').attr("readonly", true).removeClass('required');			
		}
	});
	$('#box-automotor_tipo_id').change(function(){
		$('box-automotor_carroceria_id').html('<option value="">Cargando...</option>');
		populateCarroceria('box-automotor_carroceria_id', 'box', $(this).val());
	});
	$('.calculator').keyup(function() {		
		calculateTotal();
	});	
//--> 
</script>
<form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px" enctype="multipart/form-data">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">    
	    <legend class="ui-widget ui-widget-header ui-corner-all">Resumen</legend>      
	    <p>
	        <label for="box-automotor_marca_id">Marca *</label>
	        <select name="box-automotor_marca_id" id="box-automotor_marca_id" class="ui-widget-content required">
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
	        <label for="box-suma_asegurada">Suma Asegurada ^</label>
	        <input type="text" name="box-suma_asegurada" id="box-suma_asegurada" maxlength="8" class="ui-widget-content required" style="width:120px" readonly="readonly" value="0" />
	    </p> 
		<p>
			<label for="box-castigado">Castigado </label><input type="checkbox" name="box-castigado" id="box-castigado" />
		</p>
	    <p>
	        <label for="box-infoauto">Infoauto *</label><input type="checkbox" name="box-infoauto" id="box-infoauto" value="1" />
	    </p>
	</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">    
    <legend class="ui-widget ui-widget-header ui-corner-all">General</legend>      
    <p>
        <label for="box-patente">Patente *</label>
        <input type="text" name="box-patente" id="box-patente" maxlength="20" class="ui-widget-content required" style="width:110px" />
    </p>
    <p>
        <label for="box-automotor_tipo_id">Tipo *</label>
        <select name="box-automotor_tipo_id" id="box-automotor_tipo_id" class="ui-widget-content required" style="width:110px">    
            <option value="">Seleccione</option>    
            <?php showAutomotorTipo(); ?>
        </select>
    </p>
    <p>
        <label for="box-uso">Uso *</label>
        <select name="box-uso" id="box-uso" class="ui-widget-content required" style="width:180px">    
            <option value="">Seleccione</option>    
            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'uso', 'select', 'Particular'); ?>
        </select>
    </p>

    <p>
        <label for="box-automotor_carroceria_id">Carrocería *</label>
        <select name="box-automotor_carroceria_id" id="box-automotor_carroceria_id" class="ui-widget-content required" style="width:140px">    
            <option value="">Seleccione</option>    
            <?php showCarroceria($poliza_id); ?>        
        </select>
    </p>
    <p>
        <label for="box-combustible">Combustible *</label>
        <select name="box-combustible" id="box-combustible" class="ui-widget-content required" style="width:110px">    
            <option value="">Seleccione</option>    
            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'combustible', 'select', 'Nafta'); ?>        
        </select>
    </p>
    <p>
        <label for="box-0km">0 KM</label>
        <input type="checkbox" name="box-0km" id="box-0km" value="1" />
    </p>
    <p>
        <label for="box-importado">Importado</label>
        <input type="checkbox" name="box-importado" id="box-importado" value="1" />
    </p>
    <p>
        <label for="box-nro_motor">Nº Motor *</label>
        <input type="text" name="box-nro_motor" id="box-nro_motor" maxlength="255" class="ui-widget-content required" style="width:220px" />
    </p>
    <p>
        <label for="box-nro_chasis">Nº Chasis *</label>
        <input type="text" name="box-nro_chasis" id="box-nro_chasis" maxlength="255" class="ui-widget-content required" style="width:220px" />
    </p>
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
        <input type="text" name="box-color" id="box-color" maxlength="35" class="ui-widget-content required" style="width:220px" />
    </p>    
    <p>
        <label for="box-accesorios">Accesorios *</label>
        <input type="checkbox" name="box-accesorios" id="box-accesorios" value="1" />
    </p>
    <p>
        <label for="box-zona_riesgo">Zona de Riesgo *</label>
        <select name="box-zona_riesgo" id="box-zona_riesgo" class="ui-widget-content required" style="width:110px">    
            <option value="">Seleccione</option>    
            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'zona_riesgo', 'select'); ?>   
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
                    <input type="checkbox" name="box-cristales_electricos" id="box-cristales_electricos" value="1" /><label for="box-cristales_electricos" class="secondary">C. Eléctricos</label><br />                
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
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
    <legend class="ui-widget ui-widget-header ui-corner-all">Micrograbado</legend>
    <p>
		<label for="box-micro_grabado">Micro Grabado</label>
		<input type="checkbox" name="box-micro_grabado" id="box-micro_grabado" value="1" />
	<p>
        <label for="box-cupon_vintrak">Nº Cupón Vintrak</label>
        <input type="text" name="box-cupon_vintrak" id="box-cupon_vintrak" maxlength="100" class="ui-widget-content" style="width:220px" />
    </p>
	<p>
		<label for="box-cupon_vintrak_fecha">Fecha de entrega de cupón</label>
        <input type="text" name="box-cupon_vintrak_fecha" id="box-cupon_vintrak_fecha" maxlength="10" class="ui-widget-content box-date dateAR" style="width:80px" />
	</p>
    <p>
        <label for="box-micrograbado_foto">Imagen</label>
        <input type="file" name="box-micrograbado_foto" id="box-micrograbado_foto" class="ui-widget-content" style="width:220px" />
    </p>
	<div id="divBoxFotosMicrograbado" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
	</div>
</fieldset>
<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
    <legend class="ui-widget ui-widget-header ui-corner-all">Cubiertas</legend>
    <p>
        <label for="box-cubiertas_marca">Marca</label>
        <input type="text" name="box-cubiertas_marca" id="box-cubiertas_marca" maxlength="100" class="ui-widget-content" style="width:220px" />
    </p>           
    <p>
        <label for="box-cubiertas_medidas">Medidas</label>
        <input type="text" name="box-cubiertas_medidas" id="box-cubiertas_medidas" maxlength="50" class="ui-widget-content" style="width:220px" />
    </p>
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
        <label for="box-nro_oblea">Nº Oblea</label>
        <input type="text" name="box-nro_oblea" id="box-nro_oblea" maxlength="50" class="ui-widget-content" style="width:220px" />
    </p>
    <p>
        <label for="box-nro_regulador">Nº Regulador</label>
        <input type="text" name="box-nro_regulador" id="box-nro_regulador" maxlength="50" class="ui-widget-content" style="width:220px" />
    </p>
    <p>
        <label for="box-marca_regulador">Marca Regulador</label>
        <input type="text" name="box-marca_regulador" id="box-marca_regulador" maxlength="50" class="ui-widget-content" style="width:220px" />
    </p>
    <p>
        <label for="box-marca_cilindro">Marca Cilindro</label>
        <input type="text" name="box-marca_cilindro" id="box-marca_cilindro" maxlength="50" class="ui-widget-content" style="width:220px" />
    </p>
    <p>
        <label for="box-venc_oblea">Vencimiento Oblea</label>
        <input type="text" name="box-venc_oblea" id="box-venc_oblea" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
    </p>
    <p>
        <label for="box-nro_tubo">Nº Tubo</label>
        <input type="text" name="box-nro_tubo" id="box-nro_tubo" maxlength="50" class="ui-widget-content" style="width:220px" />
    </p>                        
    <p>
        <label for="box-gnc_foto">Imagen</label>
        <input type="file" name="box-gnc_foto" id="box-gnc_foto" class="ui-widget-content" style="width:220px" />
    </p>
	<div id="divBoxFotosGNC" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
	</div>
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
    <legend class="ui-widget ui-widget-header ui-corner-all">Cobertura</legend>
    <p>
        <label for="box-cobertura_tipo_id">Tipo *</label>
        <select name="box-cobertura_tipo_id" id="box-cobertura_tipo_id" class="ui-widget-content required" style="width:90px">
        	<option value="">Seleccione</option>
			<?php showCobertura($row_Recordset1['seguro_id']); ?>
        </select>        
    </p>
    <p>
        <label for="box-franquicia">Franquicia</label>
        <input type="text" name="box-franquicia" id="box-franquicia" maxlength="5" class="ui-widget-content" style="width:120px" digits="true" min="0" max="99999" readonly="readonly" />
    </p>    
    <p>
        <label for="box-limite_rc">Límite Resp. Civil *</label>
        <select name="box-limite_rc" id="box-limite_rc" class="ui-widget-content required" style="width:120px">
        	<option value="">Seleccione</option>
            <?php enumToForm($row_Recordset1['subtipo_poliza_tabla'], 'limite_rc', 'select', '$3.000.000'); ?>
        </select>        
    </p>    
    <p>
        <label for="box-servicio_grua">Servicio de Grúa</label>
        <input type="text" name="box-servicio_grua" id="box-servicio_grua" maxlength="3" class="ui-widget-content" style="width:60px" digits="true" min="1" max="255" />
    </p> 
    <p>
        <label for="box-valor_vehiculo">Valor Vehículo *</label>
        <input type="text" name="box-valor_vehiculo" id="box-valor_vehiculo" maxlength="8" class="ui-widget-content required calculator" style="width:120px" digits="true" min="0" max="16777215" value="0" />
    </p>
    <p>
        <label for="box-valor_gnc">Valor GNC *</label>
        <input type="text" name="box-valor_gnc" id="box-valor_gnc" maxlength="8" class="ui-widget-content required calculator" style="width:120px" digits="true" min="0" max="16777215" value="0" />
    </p>
    <p>
        <label for="box-valor_accesorios">Valor Accesorios *</label>
        <input type="number" name="box-valor_accesorios" id="box-valor_accesorios" class="ui-widget-content required calculator" style="width:120px" min="0" max="16777215" value="0" readonly />
    </p>
    <p>
        <label for="box-valor_total">Valor Total ^</label>
        <input type="text" name="box-valor_total" id="box-valor_total" maxlength="8" class="ui-widget-content required" style="width:120px" readonly="readonly" value="0" />
    </p>                            
</fieldset>    