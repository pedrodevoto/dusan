<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  
	<?php $progress_bar = 'siniestro'; require_once('inc/progress.php'); ?>
	<!-- Poliza -->
    <form name="frmSelectPoliza" id="frmSelectPoliza" class="frmBoxHead">
        <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Póliza</legend> 
            <div id="divBoxPolizaSearch" style="min-height:30px">
            	<div style="padding:5px">
                	<div style="float:left">   	                        
                        <label for="box0-poliza_numero">Número póliza</label> <input type="text" name="box0-poliza_numero" id="box0-poliza_numero" maxlength="255" class="ui-widget-content" style="width:100px" />
                        <label for="box0-cliente_nombre">Nombre</label> <input type="text" id="box0-cliente_nombre" name="box0-cliente_nombre" maxlength="15" class="ui-widget-content" style="width:200px" />
					</div>
                    <div style="float:right">                        
                        <input type="button" name="BtnSearchPoliza" id="BtnSearchPoliza" value="BUSCAR" />
					</div> 
                    <br clear="all" />                       
                </div>
	            <div id="divBoxPolizaSearchResults" style="min-height:30px; padding: 10px 4px; text-align: center">
                	&nbsp;
                </div>                
            </div>
        </fieldset>
    </form>
	
  	<!-- Siniestro -->        
	<form name="frmBox" id="frmBox" class="frmBoxMain">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
            <legend class="ui-widget ui-widget-header ui-corner-all">Siniestro</legend>  
			<div style="width:33%;float:left">
	            <p>
	                <label for="box-fecha_denuncia">Fecha de denuncia</label>
	                <input type="text" name="box-fecha_denuncia" id="box-fecha_denuncia" maxlength="10" class="ui-widget-content box-date" style="width:80px" />
	            </p>
			</div>
			<div style="width:28%;float:left">
	            <p>
	                <label for="box-hora_denuncia" style="width:initial">Hora</label>
	                <input type="text" name="box-hora_denuncia" id="box-hora_denuncia" maxlength="5" class="ui-widget-content" style="width:80px" />
					(HH:MM)
	            </p>
			</div>
			<div style="width:38%;float:left">
	            <p>
	                <label for="box-lugar_denuncia" style="width:initial">Lugar</label>
	                <input type="text" name="box-lugar_denuncia" id="box-lugar_denuncia" class="ui-widget-content" style="width:200px" />
	            </p>
			</div>
			<div style="clear:both"></div>
			<div style="width:50%;float:left">
	            <p>
	                <label for="box-siniestro_numero"><b>N° DE SINIESTRO</b></label>
	                <input type="text" name="box-siniestro_numero" id="box-siniestro_numero" maxlength="255" class="ui-widget-content" style="width:80px" />
	            </p>
			</div>
			<div style="width:50%;float:left">
	            <p>
	                <label for="box-productor_seguro_codigo"><b>Código de productor ^</b></label>
	                <input type="text" name="productor_seguro_codigo" id="box-productor_seguro_codigo" maxlength="255" class="ui-widget-content" style="width:50px" readonly />
	            </p>
			</div>
			<div style="clear:both"></div>
			<p>
				<label for="box-tipo_siniestro">Tipo de siniestro</label>
				<select name="box-tipo_siniestro" id="box-tipo_siniestro" class="ui-widget-content">
					<option value="1">DENUNCIA DE SINIESTRO (SIN RECLAMO A TERCEROS)</option>
					<option value="2">DENUNCIA DE SINIESTRO (CON RECLAMO A TERCEROS)</option>
					<option value="3">DENUNCIA DE ROBO/DAÑO (CON REPOSICION)</option>
					<option value="4">DENUNCIA DE ROBO/DAÑO (SIN REPOSICION)</option>
				</select>
			</p>
		</fieldset>
        <!-- Acciones -->
        <p align="center" style="margin-top:20px">
			<input type="hidden" name="box-automotor_id" id="box-automotor_id" value="" />
			<input type="button" name="btnBox" id="btnBox" value="Guardar" />
        </p>
        <!-- Nota -->
	    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>
	</form>    
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>
</div>
	