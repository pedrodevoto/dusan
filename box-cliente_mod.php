<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Cliente -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Cliente</legend>                                     
            <p>
                <label for="box-cliente_nombre">Razón Social *</label>
                <input type="text" name="box-cliente_nombre" id="box-cliente_nombre" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-cliente_nacimiento">Nacimiento *</label>
                <input type="text" name="box-cliente_nacimiento" id="box-cliente_nacimiento" maxlength="10" class="ui-widget-content box-date" style="width:220px" />
            </p>                        
            <p>
                <label for="box-cliente_sexo">Sexo *</label>
                <select name="box-cliente_sexo" id="box-cliente_sexo" class="ui-widget-content" style="width:65px"></select>
            </p>
            <p>
                <label for="box-cliente_nacionalidad">Nacionalidad *</label>
                <input type="text" name="box-cliente_nacionalidad" id="box-cliente_nacionalidad" maxlength="50" class="ui-widget-content" style="width:220px" />
            </p>
            <p>
                <label for="box-cliente_cf">Condición Fiscal *</label>
                <select name="box-cliente_cf" id="box-cliente_cf" class="ui-widget-content" style="width:180px"></select>
            </p>                                         
            <p>
                <label for="box-cliente_cuit">CUIT</label>
                <input type="text" name="box-cliente_cuit" id="box-cliente_cuit" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>           
            <p>
                <label for="box-cliente_tipo_doc">Tipo Doc. *</label>
                <select name="box-cliente_tipo_doc" id="box-cliente_tipo_doc" class="ui-widget-content" style="width:100px"></select>
            </p>
            <p>
                <label for="box-cliente_nro_doc">Nro de Doc. *</label>
                <input type="text" name="box-cliente_nro_doc" id="box-cliente_nro_doc" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>                                 
            <p>
                <label for="box-cliente_reg_tipo">Tipo de Registro</label>
                <select name="box-cliente_reg_tipo" id="box-cliente_reg_tipo" class="ui-widget-content" style="width:100px"></select>
            </p>  
            <p>
                <label for="box-cliente_registro">Registro de Cond.</label>
                <input type="text" name="box-cliente_registro" id="box-cliente_registro" maxlength="15" class="ui-widget-content" style="width:220px" />
            </p>                                    
            <p>
                <label for="box-cliente_reg_vencimiento">Vencimiento</label>
                <input type="text" name="box-cliente_reg_vencimiento" id="box-cliente_reg_vencimiento" maxlength="10" class="ui-widget-content box-date" style="width:220px" />
            </p>                                    
            <p>
                <label for="box-cliente_email">E-mail</label>
                <input type="text" name="box-cliente_email" id="box-cliente_email" maxlength="255" class="ui-widget-content" style="width:220px" />
            </p>                                    
       	</fieldset>            
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="hidden" name="box-cliente_id" id="box-cliente_id" />            
            <input type="button" name="btnBox" id="btnBox" value="Aceptar" />
			<button name="btnContact" id="btnContact">Ver contactos</button>
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio</p>
	</form>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>