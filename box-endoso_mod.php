<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  
	
	<!-- Fotos -->	
	<div style="margin-top:20px">
	    <fieldset class="ui-widget ui-widget-content ui-corner-all">
	        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Fotos</legend> 
			<form id="fileForm" action="upload-endoso_foto.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="endoso_id" id="endoso_id" value="" />
			    <p>
			        <label for="box-endoso_foto">Subir</label>
			        <input type="file" name="box-endoso_foto" id="box-endoso_foto" class="ui-widget-content" style="width:220px" />
					<input type="submit" value="Subir foto"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
			    </p>
			</form>
			<div id="divBoxFotos" style="width:840px;height:135px;overflow:auto;white-space: nowrap;display:none">
			</div>
		</fieldset>
	</div>
	
  	<!-- Endoso -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Endoso</legend>  
			<p>
                <label for="box-poliza_numero">Número de póliza ^</label>
                <input type="text" name="box-poliza_numero" id="box-poliza_numero" maxlength="255" class="ui-widget-content" style="width:80px" readonly="readonly" />
			</p>
            <p>
                <label for="box-endoso_fecha_pedido">Fecha pedido</label>
                <input type="text" name="box-endoso_fecha_pedido" id="box-endoso_fecha_pedido" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
            </p>
            <p>
                <label for="box-endoso_tipo_id">Motivo *</label>
                <select name="box-endoso_tipo_id" id="box-endoso_tipo_id" class="ui-widget-content" style="width:180px"></select>
            </p>
			<p>
				<label for-"box-endoso_cuerpo">Cuerpo *</label>
				<textarea name="box-endoso_cuerpo" id="box-endoso_cuerpo" class="ui-widget-content" style="width:215px" rows="5"></textarea>
			</p>
            <p>
                <label for="box-endoso_numero">Número de endoso</label>
                <input type="text" name="box-endoso_numero" id="box-endoso_numero" maxlength="255" class="ui-widget-content" style="width:180px" />
            </p>
            <p>
                <label for="box-endoso_fecha_compania">Fecha de la compañía</label>
                <input type="text" name="box-endoso_fecha_compania" id="box-endoso_fecha_compania" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
            </p>
			<p>
				<label for="box-endoso_completo">Endoso completo</label>
				<input type="checkbox" name="box-endoso_completo" id="box-endoso_completo" />
			</p>
		</fieldset>
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">
			<input type="hidden" name="box-endoso_id" id="box-endoso_id" value="" />
			<input type="button" name="btnBox" id="btnBox" value="Guardar" /> <input type="button" name="btnBoxExport" id="btnBoxExport" value="Exportar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
	</form>    
    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>
	