<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>

<div class="divBoxContainer" style="width:94%">  
	<form class="frmBoxHead" id="frmSelectPoliza" name="frmSelectPoliza">
		<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
			<legend class="ui-widget ui-widget-header ui-corner-all">Póliza</legend>

			<div id="divBoxPolizaSearch" style="min-height:30px">
				<div style="padding:5px">
					<div style="float:left">
						<select style="height:10px" name="box0-cliente_id" id="box0-cliente_id"></select> <input class="ui-widget-content" id="box0-poliza_numero" maxlength="255" name="box0-poliza_numero" style="width:100px" type="text" placeholder="Número de póliza"> <input class="ui-widget-content" id="box0-patente" maxlength="255" name="box0-patente" style="width:100px" type="text" placeholder="Patente">
					</div>

					<div style="float:right">
						<input id="BtnSearchPoliza" name="BtnSearchPoliza" type="button" value="BUSCAR">
					</div><br clear="all">
				</div>

				<div id="divBoxPolizaSearchResults" style="min-height:30px; padding: 10px 4px; text-align: center">
					&nbsp;
				</div>
			</div>
		</fieldset>
	</form>

</div>