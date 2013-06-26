<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Productor -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Productor</legend>                                     
            <p>
                <label for="box-productor_nombre">Nombre *</label>
                <input type="text" name="box-productor_nombre" id="box-productor_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-productor_iva">IVA *</label>
                <select name="box-productor_iva" id="box-productor_iva" class="ui-widget-content" style="width:130px"></select>
            </p>                        
            <p>
                <label for="box-productor_cuit">CUIT *</label>
                <input type="text" name="box-productor_cuit" id="box-productor_cuit" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-productor_matricula">Matrícula *</label>
                <input type="text" name="box-productor_matricula" id="box-productor_matricula" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-productor_email">E-mail</label>
                <input type="text" name="box-productor_email" id="box-productor_email" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-productor_telefono">Teléfono</label>
                <input type="text" name="box-productor_telefono" id="box-productor_telefono" maxlength="25" class="ui-widget-content" style="width:220px" />
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