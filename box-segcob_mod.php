<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Cobertura</legend>
	            <p>
	                <label for="box-seguro_nombre">Aseguradora ^</label>
	                <input type="text" name="box-seguro_nombre" id="box-seguro_nombre" class="ui-widget-content" style="width:220px" readonly />
	            </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_nombre">Nombre *</label>
                    <input type="text" name="box-seguro_cobertura_tipo_nombre" id="box-seguro_cobertura_tipo_nombre" maxlength="20" class="ui-widget-content" style="width:220px" />
                </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_limite_rc_id">Límite RC *</label>
                    <select name="box-seguro_cobertura_tipo_limite_rc_id" id="box-seguro_cobertura_tipo_limite_rc_id" maxlength="20" class="ui-widget-content"></select>
                </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_gruas">Cantidad de grúas *</label>
                    <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas" id="box-seguro_cobertura_tipo_gruas" maxlength="20" class="ui-widget-content" style="width:50px" /> KM <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas_km" id="box-seguro_cobertura_tipo_gruas_km" maxlength="4" class="ui-widget-content" style="width:50px" /> Desde <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas_desde" id="box-seguro_cobertura_tipo_gruas_desde" maxlength="4" class="ui-widget-content" style="width:50px" />
                </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_anios_de">Rango de años recomendable *</label>
                    de <input type="text" name="box-seguro_cobertura_tipo_anios_de" id="box-seguro_cobertura_tipo_anios_de" maxlength="20" class="ui-widget-content" style="width:50px" /> a  <input type="text" name="box-seguro_cobertura_tipo_anios_a" id="box-seguro_cobertura_tipo_anios_a" maxlength="20" class="ui-widget-content" style="width:50px" />
                </p>
            	<p align="center" style="margin-top:10px">
		            <input type="hidden" name="box-seguro_cobertura_tipo_id" id="box-seguro_cobertura_tipo_id" />            
					<input type="button" name="btnBox" id="btnBox" value="Guardar" />
					<button id="btnBoxCancelar">Cancelar</button>
                </p>
            </fieldset>
        </form>
	    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
	        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
	        <span id="spnBoxMessage"></span></p>
	    </div>
    </div> 
    
</div>