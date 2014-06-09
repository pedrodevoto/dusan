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
    <div>
        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
			<input type="hidden" id="box-action" name="box-action" value="insert" />
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all">Agregar Contacto</legend>
				<div style="float:left;width:50%">
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
				</div>
				<div style="float:left;width:50%">
	                <p>
	                    <label for="box-contacto_telefono1">Tel. Particular</label>
	                    <input type="text" name="box-contacto_telefono1" id="box-contacto_telefono1" maxlength="25" class="ui-widget-content" style="width:200px" />
	                </p>
	                <p>
	                    <label for="box-contacto_telefono2">Tel. Celular</label>
	                    <input type="text" name="box-contacto_telefono2" id="box-contacto_telefono2" maxlength="25" class="ui-widget-content" style="width:200px" />
	                </p>                                                                     
					<p>
						<label for="box-contacto_telefono2_compania">Compañía</label>
						<select name="box-contacto_telefono2_compania" id="box-contacto_telefono2_compania"><option value="">Elegir</option></select>	
					</p>
	                <p>
	                    <label for="box-contacto_telefono_laboral">Tel. Laboral</label>
	                    <input type="text" name="box-contacto_telefono_laboral" id="box-contacto_telefono_laboral" maxlength="25" class="ui-widget-content" style="width:200px" />
	                </p>
	                <p>
	                    <label for="box-contacto_telefono_alt">Tel Alternativo</label>
	                    <input type="text" name="box-contacto_telefono_alt" id="box-contacto_telefono_alt" maxlength="25" class="ui-widget-content" style="width:200px" />
	                </p>
	                <p>
	                    <label for="box-contacto_observaciones">Observaciones</label>
	                    <textarea name="box-contacto_observaciones" id="box-contacto_observaciones" maxlength="500" class="ui-widget-content" style="width:200px"></textarea>
	                </p>                                                                     
				</div>
				<div style="clear:both">
	            	<p align="center" style="margin-top:10px">
						<input type="reset" name="btnBoxReset" id="btnBoxReset" value="Borrar" class="alert-error" /> <input type="button" name="btnBox" id="btnBox" value="Agregar" class="alert-success" />                                    
	                </p>
				</div>
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
    <div style="margin-top:10px">
		<p align="center">
			<button id="btnAtras">Atrás</button> <button id="btnAcciones" class="alert-success">Hecho</button>
		</p>
	</div>
</div>