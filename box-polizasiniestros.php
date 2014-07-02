<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">
	<?php $progress_bar = 'siniestro'; require_once('inc/progress.php'); ?>
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
                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Siniestros</legend> 
				<p>
					<button id="btnNuevoSiniestro">Nuevo siniestro</button>
				</p>
                <div id="divBoxList" style="min-height:30px">
                    Cargando...
                </div>
            </fieldset>
        </div>

</div>
