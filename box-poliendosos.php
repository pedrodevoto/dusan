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
                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Endosos</legend> 
				<p>
					<button id="btnNuevoEndoso">Nuevo endoso</button>
				</p>
                <div id="divBoxList" style="min-height:30px">
                    Cargando...
                </div>
            </fieldset>
        </div>

</div>
