<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">
    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Código</legend>
	            <p>
	                <label for="box-productor_id">Productor *</label>
	                <select name="box-productor_id" id="box-productor_id" class="ui-widget-content" style="width:180px"></select>
	            </p>
				<p>
                    <label for="box-seguro_id">Seguro *</label>
                    <select name="box-seguro_id" id="box-seguro_id" class="ui-widget-content" style="width:180px">
                        <option value="">Cargando</option>                
                    </select>                        
                </p>
                <p>
                    <label for="box-sucursal_id">Sucursal *</label>
                    <select name="box-sucursal_id" id="box-sucursal_id" class="ui-widget-content" style="width:180px">
                        <option value="">Cargando</option>                
                    </select>                        
                </p>                                                     
                <p>
                    <label for="box-productor_seguro_codigo">Código *</label>
                    <input type="text" name="box-productor_seguro_codigo" id="box-productor_seguro_codigo" maxlength="20" class="ui-widget-content" style="width:180px" />
                </p>
				<p>
					<label for="box-zona_riesgo_id">Zona de riesgo</label>
					<select name="box-zona_riesgo_id" id="box-zona_riesgo_id" class="ui-widget-content" style="width:180px">
						<option value="">Cargando</option>
					</select>
				</p>
			    <p>
			        <label for="box-seguro_cobertura_tipo_id[]">Coberturas *</label>
	                <select multiple="multiple" name="box-seguro_cobertura_tipo_id[]" id="box-seguro_cobertura_tipo_id" class="ui-widget-content" style="width:180px">
	                	<option value="">Cargando</option>                
	                </select>
			    </p>
            	<p align="center" style="margin-top:10px">
					<input type="button" name="btnBox" id="btnBox" value="Agregar" />                                    
                </p>
            </fieldset>         
        </form>
	    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
	        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
	        <span id="spnBoxMessage"></span></p>
	    </div>
    </div>   
   
</div>