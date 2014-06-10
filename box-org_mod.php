<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Organizador -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Organizador</legend>                                     
            <p>
                <label for="box-organizador_nombre">Nombre *</label>
                <input type="text" name="box-organizador_nombre" id="box-organizador_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-organizador_iva">IVA *</label>
                <select name="box-organizador_iva" id="box-organizador_iva" class="ui-widget-content" style="width:130px">
                	<option value="1">CF</option>
					<option value="2">RI</option>
                </select>
            </p>                        
            <p>
                <label for="box-organizador_cuit">CUIT *</label>
                <input type="text" name="box-organizador_cuit" id="box-organizador_cuit" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-organizador_matricula">Matrícula *</label>
                <input type="text" name="box-organizador_matricula" id="box-organizador_matricula" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-organizador_email">E-mail</label>
                <input type="text" name="box-organizador_email" id="box-organizador_email" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-organizador_telefono">Teléfono</label>
                <input type="text" name="box-organizador_telefono" id="box-organizador_telefono" maxlength="25" class="ui-widget-content" style="width:220px" />
            </p>
       	</fieldset>            
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="hidden" name="box-organizador_id" id="box-organizador_id" />            
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