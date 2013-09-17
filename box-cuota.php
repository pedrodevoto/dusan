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
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Cuotas</legend> 
                <div id="divBoxList" style="min-height:30px">
                    Cargando...
                </div>
            </fieldset>
	        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	            <fieldset class="ui-widget ui-widget-content ui-corner-all">
	                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Observaciones</legend> 
					<p>
						<textarea name="box-poliza_observaciones" id="box-poliza_observaciones" class="ui-widget-content" style="width:100%" rows="5" placeholder="Observaciones"></textarea>
					</p>
	            	<p align="center" style="margin-top:10px">
						<input type="hidden" name="box-poliza_id" id="box-poliza_id" />
						<input type="submit" name="btnBox" id="btnBox" value="Guardar observaciones" />
					</p>
	            </fieldset>
			</form>
		    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
		        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
		        <span id="spnBoxMessage"></span></p>
		    </div>
        </div>

</div>