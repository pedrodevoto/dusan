<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<form name="frmBox" id="frmBox" class="frmBoxMain">
    	<!-- Modelo -->    
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">Modelo</legend>                                     
            <p>
				<label for="box-automotor_marca_id">Marca *</label>
				<select name="box-automotor_marca_id" id="box-automotor_marca_id" class="ui-widget-content">
					
				</select>
			</p>
            <p>
				<label for="box-automotor_modelo_id">Modelo *</label>
				<select name="box-automotor_modelo_id" id="box-automotor_modelo_id" class="ui-widget-content">
					
				</select>
			</p>
			<p>
                <label for="box-automotor_version_nombre">Nombre *</label>
                <input type="text" name="box-automotor_version_nombre" id="box-automotor_version_nombre" maxlength="100" class="ui-widget-content" style="width:220px" />
            </p>
			<p>
				<label for="box-automotor_anos">Años *</label>
				<select multiple="multiple" name="box-automotor_anos[]" id="box-automotor_anos" class="ui-widget-content" style="width:400px;height:200px">
					
				</select>
			</p>
       	</fieldset>            
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">     
            <input type="submit" name="btnBox" id="btnBox" value="Aceptar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio</p>
	</form>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
    
</div>