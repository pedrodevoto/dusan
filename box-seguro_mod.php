<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Seguro -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Seguro</legend>                                     
            <p>
                <label for="box-seguro_nombre">Nombre *</label>
                <input type="text" name="box-seguro_nombre" id="box-seguro_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-seguro_email_siniestro">E-mail Siniestro</label>
                <input type="text" name="box-seguro_email_siniestro" id="box-seguro_email_siniestro" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>            
            <p>
                <label for="box-seguro_email_emision">E-mail Emisión/MC</label>
                <input type="text" name="box-seguro_email_emision" id="box-seguro_email_emision" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>
            <p>
                <label for="box-seguro_email_emision_vida">E-mail Emisión Vida</label>
                <input type="text" name="box-seguro_email_emision_vida" id="box-seguro_email_emision_vida" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>
            <p>
                <label for="box-seguro_email_patrimoniales_otras">E-mail Patrimoniales</label>
                <input type="text" name="box-seguro_email_patrimoniales_otras" id="box-seguro_email_patrimoniales_otras" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>
            <p>
                <label for="box-seguro_email_endosos">E-mail Endosos</label>
                <input type="text" name="box-seguro_email_endosos" id="box-seguro_email_endosos" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>
            <p>
                <label for="box-seguro_email_rastreador">E-mail Rastreador</label>
                <input type="text" name="box-seguro_email_rastreador" id="box-seguro_email_rastreador" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>
            <p>
                <label for="box-seguro_email_fotos">E-mail Fotos</label>
                <input type="text" name="box-seguro_email_fotos" id="box-seguro_email_fotos" maxlength="255" class="ui-widget-content" style="width:220px" placeholder="Separados por coma" />
            </p>
            <p>
                <label for="box-seguro_cuit">CUIT</label>
                <input type="text" name="box-seguro_cuit" id="box-seguro_cuit" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-seguro_direccion">Dirección</label>
                <input type="text" name="box-seguro_direccion" id="box-seguro_direccion" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-seguro_localidad">Localidad</label>
                <input type="text" name="box-seguro_localidad" id="box-seguro_localidad" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-seguro_cp">Código Postal</label>
                <input type="text" name="box-seguro_cp" id="box-seguro_cp" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>
       	</fieldset>            
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="hidden" name="box-seguro_id" id="box-seguro_id" />            
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