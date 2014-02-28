<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Zona de riesgo -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Zona de riesgo</legend>                                     
            <p>
                <label for="box-zona_riesgo_nombre">Nombre *</label>
                <input type="text" name="box-zona_riesgo_nombre" id="box-zona_riesgo_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
       	</fieldset>            
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="hidden" name="box-zona_riesgo_id" id="box-zona_riesgo_id" value="" />            
            <input type="button" name="btnBox" id="btnBox" value="Aceptar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio</p>
	</form>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>