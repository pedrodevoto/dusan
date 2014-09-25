<?php
	$MM_authorizedUsers = "master";	
?>
<?php require_once('inc/security-html.php'); ?>
<ul class="ui-widget ui-helper-clearfix">			 
	<button class="ui-state-highlight ui-corner-all" onclick="openBoxAltaAutomotorMarca()" title="Crear" style="cursor:pointer;width:220px" id="btnNuevaMarca">Crear marca</button>
	<button class="ui-state-highlight ui-corner-all" onclick="openBoxAltaAutomotorModelo()" title="Crear" style="cursor:pointer;width:220px;display:none" id="btnNuevoModelo">Crear modelo</button>
	<button class="ui-state-highlight ui-corner-all" onclick="openBoxAltaAutomotorVersion()" title="Crear" style="cursor:pointer;width:220px;display:none" id="btnNuevaVersion">Crear versión</button>
</ul>