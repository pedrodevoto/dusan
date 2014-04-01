<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  

	<!-- Progress Menu -->
	<?php require_once('inc/progress.php'); ?>

	<!-- Cliente -->
    <form name="frmSelectClient" id="frmSelectClient" class="frmBoxHead">
        <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Cliente</legend> 
            <div id="divBoxClienteSearch" style="min-height:30px">
            	<div style="padding:5px">
                	<div style="float:left">   	                        
                        <label for="box0-cliente_nombre">Razón Social</label> <input type="text" name="box0-cliente_nombre" id="box0-cliente_nombre" maxlength="255" class="ui-widget-content" style="width:200px" />
                        <label for="box0-cliente_nro_doc">Nº Doc.</label> <input type="text" id="box0-cliente_nro_doc" name="box0-cliente_nro_doc" maxlength="15" class="ui-widget-content" style="width:100px" />            
					</div>
                    <div style="float:right">                        
                        <input type="button" name="BtnSearchCliente" id="BtnSearchCliente" value="BUSCAR" />
					</div> 
                    <br clear="all" />                       
                </div>
	            <div id="divBoxClienteSearchResults" style="min-height:30px; padding: 10px 4px; text-align: center">
                	&nbsp;
                </div>                
            </div>
        </fieldset>
    </form>

  	<!-- Poliza -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Poliza (General)</legend>  
			<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
	            <legend class="ui-widget ui-widget-header ui-corner-all">Cliente y Tipo Póliza</legend>
				<p>
	                <label for="box-cliente_nombre">Cliente ^</label>
	                <input type="text" name="box-cliente_nombre" id="box-cliente_nombre" maxlength="255" class="ui-widget-content" style="width:220px" readonly="readonly">
				</p>
				<p>
	                <label for="box-tipo_poliza_id">Tipo Poliza *</label>
	                <select name="box-tipo_poliza_id" id="box-tipo_poliza_id" class="ui-widget-content" style="width:130px"></select>
	            </p>
	            <p>
	                <label for="box-subtipo_poliza_id">Sección *</label>
	                <select name="box-subtipo_poliza_id" id="box-subtipo_poliza_id" class="ui-widget-content" style="width:180px">
	                	<option value="">Todos</option>
	                </select>
	            </p>
			</fieldset>
			<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
	            <legend class="ui-widget ui-widget-header ui-corner-all">Compañía</legend>
	            <p>
	                <label for="box-sucursal_id">Sucursal</label>
	                <select name="box-sucursal_id" id="box-sucursal_id" class="ui-widget-content" style="width:222px">
	                	<option value="">Cargando</option>                
	                </select>
	            </p>     
	            <p>
	                <label for="box-seguro_id">Aseguradora *</label>
	                <select name="box-seguro_id" id="box-seguro_id" class="ui-widget-content" style="width:180px"></select>
	            </p>
	            <p>
	                <label for="box-productor_seguro_id">Productor *</label>
	                <select name="box-productor_seguro_id" id="box-productor_seguro_id" class="ui-widget-content" style="width:180px">
	                	<option value="">Todos</option>
	                </select>
	            </p>
			</fieldset>
			<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
	            <legend class="ui-widget ui-widget-header ui-corner-all">Nº de Poliza y Vigencia</legend>
	            <p>
	                <label for="box-poliza_numero">Nº de Poliza</label>
	                <input type="text" name="box-poliza_numero" id="box-poliza_numero" maxlength="20" class="ui-widget-content" style="width:220px" />
					<input type="checkbox" name="box-poliza_flota" id="box-poliza_flota" value="1" disabled /><label for="box-poliza_flota" class="secondary">Flota</label>
	            </p>
				<p class="poliza_plan" style="display:none">
					<label for="box-poliza_plan_id">Plan *</label>
					<select name="box-poliza_plan_id" id="box-poliza_plan_id" class="ui-widget-content" style="width:180px">
						<option value="">Seleccione</option>
					</select>
				</p>
				<p class="poliza_plan" style="display:none">
					<label for="box-poliza_pack_id">Pack *</label>
					<select name="box-poliza_pack_id" id="box-poliza_pack_id" class="ui-widget-content" style="width:180px">
						<option value="">Seleccione</option>
					</select>
				</p>
	            <p>
	                <label for="box-poliza_validez_desde">Vigencia Desde *</label>
	                <input type="text" name="box-poliza_validez_desde" id="box-poliza_validez_desde" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>            
	            <p>
	                <label for="box-poliza_vigencia">Vigencia *</label>
	                <select name="box-poliza_vigencia" id="box-poliza_vigencia" class="ui-widget-content" style="width:130px"></select>
					<input type="number" name="box-poliza_vigencia_dias" id="box-poliza_vigencia_dias" class="ui-widget-content" style="width:40px" readonly />
	            </p> 
	            <p>
	                <label for="box-poliza_validez_hasta">Vigencia Hasta *</label>
	                <input type="text" name="box-poliza_validez_hasta" id="box-poliza_validez_hasta" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
	            <p>
	                <label for="box-poliza_fecha_solicitud">Fecha Solicitud</label>
	                <input type="text" name="box-poliza_fecha_solicitud" id="box-poliza_fecha_solicitud" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
	            <p>
	                <label for="box-poliza_fecha_emision">Fecha Emisión</label>
	                <input type="text" name="box-poliza_fecha_emision" id="box-poliza_fecha_emision" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
	            <p>
	                <label for="box-poliza_fecha_recepcion">Fecha Recepción</label>
	                <input type="text" name="box-poliza_fecha_recepcion" id="box-poliza_fecha_recepcion" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
	            <p>
	                <label for="box-poliza_fecha_entrega">Fecha Envío/Entrega</label>
	                <input type="text" name="box-poliza_fecha_entrega" id="box-poliza_fecha_entrega" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	                <input type="checkbox" name="box-poliza_correo" id="box-poliza_correo" value="1" /><label for="box-poliza_correo" class="secondary">Correo</label>&nbsp;<input type="checkbox" name="box-poliza_email" id="box-poliza_email" value="1" /><label for="box-poliza_email" class="secondary">Email</label>&nbsp;<input type="checkbox" name="box-poliza_entregada" id="box-poliza_entregada" value="1" /><label for="box-poliza_entregada" class="secondary">Entregada</label>
	            </p>
			</fieldset>
			<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:10px">
	            <legend class="ui-widget ui-widget-header ui-corner-all">Precio y Forma de Pago</legend>
	            <p>
	                <label for="box-poliza_prima">Prima</label>
	                <input type="text" name="box-poliza_prima" id="box-poliza_prima" maxlength="11" class="ui-widget-content" style="width:100px" />
	            </p>
	            <p>
	                <label for="box-poliza_premio">Premio *</label>
	                <input type="text" name="box-poliza_premio" id="box-poliza_premio" maxlength="11" class="ui-widget-content" style="width:100px" />
	            </p>
	            <p>
	                <label for="box-poliza_cuotas">Plan de Pago *</label>
	                <select name="box-poliza_cuotas" id="box-poliza_cuotas" class="ui-widget-content" style="width:130px"></select>
	            </p> 
	            <p>
	                <label for="box-poliza_medio_pago">Medio de Pago *</label>
	                <select name="box-poliza_medio_pago" id="box-poliza_medio_pago" class="ui-widget-content" style="width:180px"></select>
	                Cant. Cuotas * 
	                <input type="text" name="box-poliza_cant_cuotas" id="box-poliza_cant_cuotas" maxlength="3" class="ui-widget-content" style="width:80px" /> <span id="pfc"><input type="checkbox" name="box-sucursal_pfc" id="box-sucursal_pfc" class="ui-widget-content" />PFC</span>
				</p>
	            <p>
	                <label for="box-poliza_pago_detalle">Det. de Pago</label>
					<textarea name="box-poliza_pago_detalle" id="box-poliza_pago_detalle" rows="5" class="ui-widget-content" style="width:220px"></textarea>
	            </p>
	            <p>
	                <label for="box-poliza_recargo">Recargo (%) *</label>
					<input type="text" name="box-poliza_recargo" id="box-poliza_recargo" maxlength="5" class="ui-widget-content" style="width:45px" />
					<span id="poliza_descuento" style="display:none">
		                <label for="box-poliza_descuento" style="vertical-align:middle">Descuento (%)</label>
						<input type="text" name="box-poliza_descuento" id="box-poliza_descuento" maxlength="2" class="ui-widget-content" style="width:45px" />
					</span>
				</p>
			</fieldset>
       	</fieldset>                   
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">
			<input type="hidden" name="box-cliente_id" id="box-cliente_id" />			
			<input type="hidden" name="box-poliza_plan_flag" id="box-poliza_plan_flag" value="0" />
			<input type="button" name="btnBox" id="btnBox" value="Siguiente" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
	</form>    
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>