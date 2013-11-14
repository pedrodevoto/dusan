<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<div>
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Seguro Seleccionado</legend> 
            <div id="divBoxInfo" style="height:30px">
                Cargando...
            </div>
        </fieldset>
    </div>
    
    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
			<input type="hidden" id="box-action" name="box-action" value="insert" />
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Cobertura</legend>
                <p>
                    <label for="box-seguro_cobertura_tipo_nombre">Nombre *</label>
                    <input type="text" name="box-seguro_cobertura_tipo_nombre" id="box-seguro_cobertura_tipo_nombre" maxlength="20" class="ui-widget-content" style="width:220px" />
                </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_limite_rc">Límite RC *</label>
                    <input type="number" min="0" step="any" name="box-seguro_cobertura_tipo_limite_rc" id="box-seguro_cobertura_tipo_limite_rc" maxlength="20" class="ui-widget-content" style="width:220px" />
                </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_gruas">Cantidad de grúas *</label>
                    <input type="number" min="0" step="1" name="box-seguro_cobertura_tipo_gruas" id="box-seguro_cobertura_tipo_gruas" maxlength="20" class="ui-widget-content" style="width:220px" />
                </p>
                <p>
                    <label for="box-seguro_cobertura_tipo_anios_de">Rango de años recomendable *</label>
                    de <input type="text" name="box-seguro_cobertura_tipo_anios_de" id="box-seguro_cobertura_tipo_anios_de" maxlength="20" class="ui-widget-content" style="width:50px" /> a  <input type="text" name="box-seguro_cobertura_tipo_anios_a" id="box-seguro_cobertura_tipo_anios_a" maxlength="20" class="ui-widget-content" style="width:50px" />
                </p>
            	<p align="center" style="margin-top:10px">
					<input type="reset" name="btnBoxReset" id="btnBoxReset" value="Borrar" /> <input type="button" name="btnBox" id="btnBox" value="Agregar" />
                </p>
            </fieldset>         
        </form>
    </div>   
	<div style="margin-top:10px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Coberturas</legend> 
            <div id="divBoxList" style="min-height:30px">
                Cargando...
            </div>
        </fieldset>
    </div>      
    
</div>