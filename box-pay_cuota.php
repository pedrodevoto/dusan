<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

        <div>
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Poliza Seleccionada</legend> 
                <div id="divBoxInfo" style="min-height:90px">
                    Cargando...
                </div>
            </fieldset>
        </div>
        <div style="margin-top:10px">
	        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	            <fieldset class="ui-widget ui-widget-content ui-corner-all">
	                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Cuota</legend> 
		            <p>
		                <label for="box-cuota_fe_pago">Fecha de pago</label>
<input type="text" name="box-cuota_fe_pago" id="box-cuota_fe_pago" class="ui-widget-content box-datetime" style="width:120px" />
		            </p>
                	<p>
						<label for="box-cuota_monto">Monto</label>
						<input type="number" step="any" min="0" name="box-cuota_monto" id="box-cuota_monto" class="ui-widget-content" style="width:120px" />
					</p>
		            <p>
		                <label for="box-cuota_vencimiento">Próximo vencimiento</label>
<input type="text" name="box-cuota_vencimiento" id="box-cuota_vencimiento" class="ui-widget-content box-date" style="width:120px" />
		            </p>
	            	<p align="center" style="margin-top:10px">
						<input type="hidden" name="box-cuota_id" id="box-cuota_id" />
						<input type="button" name="btnBox" id="btnBox" value="Procesar pago" /> <button id="btnCancel">Cancelar</button>
	                </p>
	            </fieldset>
			</form>
        </div>
	    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
	        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
	        <span id="spnBoxMessage"></span></p>
	    </div>
</div>