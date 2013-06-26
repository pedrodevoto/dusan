<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Usuario -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Usuario</legend>                                     
            <p>
                <label for="box-usuario_nombre">Nombre *</label>
                <input type="text" name="box-usuario_nombre" id="box-usuario_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-usuario_email">E-mail *</label>
                <input type="text" name="box-usuario_email" id="box-usuario_email" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>            
            <p>
                <label for="box-usuario_usuario">Usuario *</label>
                <input type="text" name="box-usuario_usuario" id="box-usuario_usuario" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-usuario_clave">Clave *</label>
                <input type="password" name="box-usuario_clave" id="box-usuario_clave" maxlength="32" class="ui-widget-content" style="width:220px" />
            </p> 
            <p>
                <label for="box-usuario_clave2">Repetir Clave *</label>
                <input type="password" name="box-usuario_clave2" id="box-usuario_clave2" maxlength="32" class="ui-widget-content" style="width:220px" />
            </p>      
            <p>
                <label for="box-usuario_acceso">Nivel Acceso *</label>
                <select name="box-usuario_acceso" id="box-usuario_acceso" class="ui-widget-content" style="width:222px">
                	<option value="">Cargando</option>                
                </select>
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