<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<div>
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Productor Seleccionado</legend> 
            <div id="divBoxInfo" style="height:30px">
                Cargando...
            </div>
        </fieldset>
    </div>
    
    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Seguro</legend>
                <p>
                    <label for="box-seguro_id">Seguro *</label>
                    <select name="box-seguro_id" id="box-seguro_id" class="ui-widget-content" style="width:180px">
                        <option value="">Cargando</option>                
                    </select>                        
                </p>                                                
                <p>
                    <label for="box-productor_seguro_codigo">Código *</label>
                    <input type="text" name="box-productor_seguro_codigo" id="box-productor_seguro_codigo" maxlength="20" class="ui-widget-content" style="width:220px" />
                </p>     
            	<p align="center" style="margin-top:10px">
					<input type="button" name="btnBox" id="btnBox" value="Agregar" />                                    
                </p>
            </fieldset>         
        </form>
    </div>   
	<div style="margin-top:10px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Seguros Relacionados</legend> 
            <div id="divBoxList" style="min-height:30px">
                Cargando...
            </div>
        </fieldset>
    </div>      
    
</div>