<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Sucursal -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Sucursal</legend>                                     
            <p>
                <label for="box-sucursal_nombre">Nombre *</label>
                <input type="text" name="box-sucursal_nombre" id="box-sucursal_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-sucursal_direccion">Dirección</label>
                <input type="text" name="box-sucursal_direccion" id="box-sucursal_direccion" maxlength="500" class="ui-widget-content" style="width:220px" />
            </p>                        
            <p>
                <label for="box-sucursal_telefono">Teléfono</label>
                <input type="text" name="box-sucursal_telefono" id="box-sucursal_telefono" maxlength="25" class="ui-widget-content" style="width:220px" />
            </p>      
            <p>
                <label for="box-sucursal_email">E-mail</label>
                <input type="text" name="box-sucursal_email" id="box-sucursal_email" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>                     
       	</fieldset>            
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="hidden" name="box-insert" id="box-insert" value="1" />            
            <input type="button" name="btnBox" id="btnBox" value="Aceptar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio</p>
	</form>
    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>