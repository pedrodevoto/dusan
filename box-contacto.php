<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">

	<div>
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Cliente Seleccionado</legend> 
            <div id="divBoxInfo" style="height:30px">
                Cargando...
            </div>
        </fieldset>
    </div>
	<div style="margin-top:20px">
	    <fieldset class="ui-widget ui-widget-content ui-corner-all">
	        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Fotos</legend> 
			<form id="fileForm" action="upload-cliente_foto.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="cliente_id" value="<?=$cliente_id?>" />
			    <p>
			        <label for="box-cliente_foto">Subir</label>
			        <input type="file" name="box-cliente_foto" id="box-cliente_foto" class="ui-widget-content" style="width:220px" />
					<input type="submit" value="Subir foto"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
			    </p>
			</form>
			<div id="divBoxFotos" style="width:840px;height:135px;overflow:auto;white-space: nowrap;display:none">
			</div>
		</fieldset>
	</div>
    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Contacto</legend>
                <p>
                    <label for="box-contacto_tipo">Tipo *</label>
                    <select name="box-contacto_tipo" id="box-contacto_tipo" class="ui-widget-content" style="width:110px">
                        <option value="">Cargando</option>                
                    </select>                        
                </p>                                                
                <p>
                    <label for="box-contacto_domicilio">Dirección *</label>
                    <input type="text" name="box-contacto_domicilio" id="box-contacto_domicilio" maxlength="255" class="ui-widget-content" style="width:200px" />
                </p>
				<p>
                    <label for="box-contacto_nro">Número *</label>
                    <input type="text" name="box-contacto_nro" id="box-contacto_nro" maxlength="10" class="ui-widget-content" style="width:80px" />
                </p>
                <p>
					<label for="box-contacto_piso">Piso</label>
                    <input type="text" name="box-contacto_piso" id="box-contacto_piso" maxlength="4" class="ui-widget-content" style="width:40px" />
                </p>
                <p>
					<label for="box-contacto_dpto">Dpto</label>
                    <input type="text" name="box-contacto_dpto" id="box-contacto_dpto" maxlength="3" class="ui-widget-content" style="width:40px" />
                </p>
				<p>
                    <label for="box-contacto_localidad">Localidad *</label>
                    <input type="text" name="box-contacto_localidad" id="box-contacto_localidad" maxlength="255" class="ui-widget-content" style="width:200px" />
                </p>
                <p>
                    <label for="box-contacto_cp">Código Postal *</label>
                    <input type="text" name="box-contacto_cp" id="box-contacto_cp" maxlength="10" class="ui-widget-content" style="width:100px" />
                </p>
				<p>
					<label for="box-contacto_country">Barrio Cerrado / Country </label>
					<input type="text" name="box-contacto_country" id="box-contacto_country" maxlength="255" class="ui-widget-content" style="width:200px" />
				</p>
				<p>
					<label for="box-contacto_lote">Lote </label>
					<input type="text" name="box-contacto_lote" id="box-contacto_lote" maxlength="255" class="ui-widget-content" style="width:200px" />
				</p>
                <p>
                    <label for="box-contacto_telefono1">Teléfono 1</label>
                    <input type="text" name="box-contacto_telefono1" id="box-contacto_telefono1" maxlength="25" class="ui-widget-content" style="width:200px" />
                </p>
                <p>
                    <label for="box-contacto_telefono2">Teléfono 2</label>
                    <input type="text" name="box-contacto_telefono2" id="box-contacto_telefono2" maxlength="25" class="ui-widget-content" style="width:200px" />
                </p>                                                                     
            	<p align="center" style="margin-top:10px">
					<input type="button" name="btnBox" id="btnBox" value="Agregar" />                                    
                </p>
            </fieldset>         
        </form>
    </div>   
	<div style="margin-top:10px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Contactos Relacionados</legend> 
            <div id="divBoxList" style="min-height:30px">
                Cargando...
            </div>
        </fieldset>
    </div>      
    
</div>