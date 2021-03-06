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
					<label for="box-seguro_cobertura_tipo_antiguedad">Antigüedad máxima</label>
					<input type="text" name="box-seguro_cobertura_tipo_antiguedad" id="box-seguro_cobertura_tipo_antiguedad" maxlength="3" style="width:50px" class="ui-widget-content" /> <span id="antiguedad" style="color:grey"></span>
				</p>
                <p>
                    <label for="box-seguro_cobertura_tipo_gruas">Cantidad de grúas</label>
                    <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas" id="box-seguro_cobertura_tipo_gruas" maxlength="20" class="ui-widget-content" style="width:50px" /> KM <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas_km" id="box-seguro_cobertura_tipo_gruas_km" maxlength="4" class="ui-widget-content" style="width:50px" /> Desde <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas_desde" id="box-seguro_cobertura_tipo_gruas_desde" maxlength="4" class="ui-widget-content" style="width:50px" />
                </p>
				<p>
					<label for="box-seguro_cobertura_tipo_todo_riesgo">Cobertura TODO RIESGO?</label>
					<input type="checkbox" name="box-seguro_cobertura_tipo_todo_riesgo" id="box-seguro_cobertura_tipo_todo_riesgo"> <label for="box-seguro_cobertura_tipo_franquicia" style="margin-left:36px;width:initial">Franquicia</label> <input type="text" name="box-seguro_cobertura_tipo_franquicia" id="box-seguro_cobertura_tipo_franquicia" class="ui-widget-content" />
				</p>
				<p>
					<label for="box-seguro_cobertura_tipo_ajuste">Ajuste</label>
					<select name="box-seguro_cobertura_tipo_ajuste" id="box-seguro_cobertura_tipo_ajuste" class="ui-widget-content" style="width:130px">
						<option value="">Seleccione</option>                
						<option value="0">0%</option>
						<option value="10">10%</option>
						<option value="20">20%</option>
						<option value="30">30%</option>
					</select>
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