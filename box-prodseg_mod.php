<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">
    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Modificar Código</legend>
	            <p>
	                <label for="box-productor_nombre">Productor ^</label>
	                <input type="text" name="box-productor_nombre" id="box-productor_nombre" class="ui-widget-content" style="width:180px" readonly />
	            </p>
				<p>
                    <label for="box-seguro_nombre">Seguro ^</label>
                    <input type="text" name="box-seguro_nombre" id="box-seguro_nombre" class="ui-widget-content" style="width:180px" readonly />                   
                </p>
                <p>
                    <label for="box-sucursal_nombre">Sucursal ^</label>
                    <input type="text" name="box-sucursal_nombre" id="box-sucursal_nombre" class="ui-widget-content" style="width:180px" readonly />                    
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
		            <input type="hidden" name="box-productor_seguro_id" id="box-productor_seguro_id" />            
					<input type="button" name="btnBox" id="btnBox" value="Guardar" />                                    
                </p>
            </fieldset>         
        </form>
	    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
	        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
	        <span id="spnBoxMessage"></span></p>
	    </div>
    </div>   
   
</div>