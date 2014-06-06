<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	// Require DB functions
	require_once('inc/db_functions.php');	
	require_once('inc/echo_functions.php');	
?>
<?php
	// Obtain URL parameter
	$poliza_id = intval($_GET['id']);
	
	// Recordset: Poliza
	$query_Recordset1 = sprintf("SELECT subtipo_poliza_nombre, subtipo_poliza_tabla, IF(cliente_tipo_persona=1, CONCAT(IFNULL(cliente_nombre, ''), ' ', IFNULL(cliente_apellido, '')), cliente_razon_social) as cliente_nombre, productor_seguro.seguro_id as seguro_id, seguro_nombre, productor_nombre, poliza_numero, poliza.productor_seguro_id as productor_seguro_id, poliza_plan_flag, poliza_plan_id, poliza_pack_id, poliza_flota FROM poliza JOIN (subtipo_poliza, cliente, productor_seguro, seguro, productor) ON (subtipo_poliza.subtipo_poliza_id=poliza.subtipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.seguro_id=seguro.seguro_id AND productor_seguro.productor_id=productor.productor_id) WHERE poliza.poliza_id=%s", $poliza_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	if ($totalRows_Recordset1 !== 1) {
		die('<p class="txtBox">Error: Poliza no encontrada.</p>');
	}
	mysql_free_result($Recordset1);	
?>
<div class="divBoxContainer" style="width:94%">
	<!-- Progress Menu -->
	<?php require_once('inc/progress.php'); ?>
	<div style="margin-top:20px">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Poliza</legend> 
            <div>
                <table class="tblBox">
                	<tr><td><strong>Cliente: </strong><?php echo($row_Recordset1['cliente_nombre']); ?></tr></td> 
                    <tr><td><strong>Compañía: </strong><?php echo($row_Recordset1['seguro_nombre']); ?></tr></td>
                    <tr><td><strong>Productor: </strong><?php echo($row_Recordset1['productor_nombre']); ?></tr></td>
                    <tr><td><strong>Poliza Nº: </strong><?php echo (is_null($row_Recordset1['poliza_numero']) ? '-' : $row_Recordset1['poliza_numero']); ?></tr></td>
				</table>
            </div>
        </fieldset>
    </div>
	<?php
	// Require form by type
	$exclude_save_button = false;
            require_once('subtipo/'.$row_Recordset1['subtipo_poliza_tabla'].'.php');
        ?>          
	</form>         
	<?php if(!$exclude_save_button): ?>
    <!-- Acciones -->
    <p align="center" style="margin-top:20px">     
        <input type="button" name="btnBox" class="btnBox" value="Cargando" />
    </p>      
    <!-- Nota -->
    <p align="center" style="margin-top:10px" class="txtBox">* Campo obligatorio | ^ Campo no editable</p>  
	<?php endif;?>
    <div id="divBoxMessage" class="ui-state-highlight alert-success ui-corner-all divBoxMessage"> 
        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
        <span id="spnBoxMessage"></span></p>
    </div>   
</div>