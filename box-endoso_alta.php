<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  

	<!-- Poliza -->
    <form name="frmSelectPoliza" id="frmSelectPoliza" class="frmBoxHead">
        <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Póliza</legend> 
            <div id="divBoxPolizaSearch" style="min-height:30px">
            	<div style="padding:5px">
                	<div style="float:left">   	                        
                        <label for="box0-poliza_numero">Número póliza</label> <input type="text" name="box0-poliza_numero" id="box0-poliza_numero" maxlength="255" class="ui-widget-content" style="width:100px" />
                        <label for="box0-cliente_nombre">Nombre</label> <input type="text" id="box0-cliente_nombre" name="box0-cliente_nombre" maxlength="15" class="ui-widget-content" style="width:200px" />
					</div>
                    <div style="float:right">                        
                        <input type="button" name="BtnSearchPoliza" id="BtnSearchPoliza" value="BUSCAR" />
					</div> 
                    <br clear="all" />                       
                </div>
	            <div id="divBoxPolizaSearchResults" style="min-height:30px; padding: 10px 4px; text-align: center">
                	&nbsp;
                </div>                
            </div>
        </fieldset>
    </form>
	
  	<!-- Endoso -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Endoso</legend>  
			<p>
                <label for="box-poliza_numero">Número de póliza ^</label>
                <input type="text" name="box-poliza_numero" id="box-poliza_numero" maxlength="255" class="ui-widget-content" style="width:80px" readonly="readonly" />
			</p>
            <p>
                <label for="box-endoso_fecha_pedido">Fecha pedido</label>
                <input type="text" name="box-endoso_fecha_pedido" id="box-endoso_fecha_pedido" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
            </p>
            <p>
                <label for="box-endoso_tipo_id">Motivo *</label>
                <select name="box-endoso_tipo_id" id="box-endoso_tipo_id" class="ui-widget-content" style="width:180px"></select>
            </p>
			<p>
				<label for-"box-endoso_cuerpo">Detalle</label>
				<textarea name="box-endoso_cuerpo" id="box-endoso_cuerpo" class="ui-widget-content" style="width:215px" rows="5"></textarea>
			</p>
            <p>
                <label for="box-endoso_premio">Premio</label>
                <input type="text" name="box-endoso_premio" id="box-endoso_premio" maxlength="255" class="ui-widget-content" style="width:180px" /> (este campo no podrá modificarse)
            </p>
            <p>
                <label for="box-endoso_numero">Número de endoso</label>
                <input type="text" name="box-endoso_numero" id="box-endoso_numero" maxlength="255" class="ui-widget-content" style="width:180px" />
            </p>
            <p>
                <label for="box-endoso_fecha_compania">Fecha de la compañía</label>
                <input type="text" name="box-endoso_fecha_compania" id="box-endoso_fecha_compania" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
            </p>
			<p>
				<label for="box-endoso_completo">Endoso completo</label>
				<input type="checkbox" name="box-endoso_completo" id="box-endoso_completo" />
			</p>
		</fieldset>
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">
			<input type="hidden" name="box-poliza_id" id="box-poliza_id" value="" />
			<input type="button" name="btnBox" id="btnBox" value="Guardar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
	</form>    
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>
	