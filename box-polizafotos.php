<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<div class="divBoxContainer" style="width:94%">
    <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Fotos generales</legend> 
		<form class="fileForm" id="automotor" suffix="Automotor" action="upload-automotor_foto.php" method="post" enctype="multipart/form-data">
		    <p>
		        <label for="box-automotor_foto">Subir</label>
		        <input type="file" name="box-automotor_foto" id="box-automotor_foto" class="ui-widget-content" style="width:220px" />
				<input type="submit" value="Subir foto"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>
		<div id="divBoxFotosAutomotor" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	    <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Archivos generales</legend> 
		<form class="fileForm" id="archivos_generales" suffix="ArchivosGenerales" action="upload-automotor_archivo.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-general_archivo">Archivo</label>
		        <input type="file" name="box-general_archivo" id="box-general_archivo" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir archivo"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>	
		<div id="divBoxArchivosArchivosGenerales">
		</div>
	</fieldset>
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Micrograbado</legend> 
		<form class="fileForm" id="micrograbado" suffix="Micrograbado" action="upload-automotor_foto.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-automotor_micrograbado_foto">Imagen</label>
		        <input type="file" name="box-automotor_micrograbado_foto" id="box-automotor_micrograbado_foto" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir foto"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>
		<div id="divBoxFotosMicrograbado" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">GNC</legend> 
		<form class="fileForm" id="gnc" suffix="GNC" action="upload-automotor_foto.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-automotor_gnc_foto">Imagen</label>
		        <input type="file" name="box-automotor_gnc_foto" id="box-automotor_gnc_foto" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir foto"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>	
		<div id="divBoxFotosGNC" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Certificado de no rodamiento</legend> 
		<form class="fileForm" id="cert_rodamiento" suffix="CertRodamiento" action="upload-automotor_archivo.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-cert_rodamiento_archivo">Archivo</label>
		        <input type="file" name="box-cert_rodamiento_archivo" id="box-cert_rodamiento_archivo" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir archivo"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>	
		<div id="divBoxArchivosCertRodamiento">
		</div>
	</fieldset>
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
        <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Cédula verde</legend> 
		<form class="fileForm" id="cedula_verde" suffix="CedulaVerde" action="upload-automotor_foto.php" method="post" enctype="multipart/form-data">
			<p>
		        <label for="box-automotor_cedula_verde_foto">Imagen</label>
		        <input type="file" name="box-automotor_cedula_verde_foto" id="box-automotor_cedula_verde_foto" class="ui-widget-content" style="width:220px" /> 
				<input type="submit" value="Subir foto"> <span id="fotosLoading" style="display:none"><img title="Subiendo..." src="media/images/fotos-loading.gif" /></span>
		    </p>
		</form>
		<div id="divBoxFotosCedulaVerde" style="width:600px;height:135px;overflow:auto;white-space: nowrap;display:none">
		</div>
	</fieldset>
</div>