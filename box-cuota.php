<?php
	$MM_authorizedUsers = "administrativo,master";	
?>
<?php require_once('inc/security-colorbox.php'); ?>
<?php
require_once('Connections/connection.php');
$poliza_id = intval(mysql_real_escape_string($_GET['id']));
$sql = 'SELECT cliente_email FROM poliza JOIN (cliente) ON poliza.cliente_id = cliente.cliente_id WHERE poliza_id='.$poliza_id;
$res = mysql_query($sql, $connection);
list($cliente_email) = mysql_fetch_array($res);
?>
<div class="divBoxContainer" style="width:94%">

        <div>
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Poliza Seleccionada</legend> 
                <div id="divBoxInfo" style="min-height:90px">
                    Cargando...
                </div>
            </fieldset>
        </div>
        <div style="margin-top:10px">
            <fieldset class="ui-widget ui-widget-content ui-corner-all">
                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Cuotas</legend> 
                <div id="divBoxList" style="min-height:30px">
                    Cargando...
                </div>
            </fieldset>
	        <form name="frmBox" id="frmBox" class="frmBoxMain" style="margin-top:20px">
	            <fieldset class="ui-widget ui-widget-content ui-corner-all">
	                <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Observaciones</legend> 
					<p>
						<textarea name="box-poliza_observaciones" id="box-poliza_observaciones" class="ui-widget-content" style="width:100%" rows="5" placeholder="Observaciones"></textarea>
					</p>
	            	<p align="center" style="margin-top:10px">
						<input type="hidden" name="box-poliza_id" id="box-poliza_id" />
						<input type="submit" name="btnBox" id="btnBox" value="Guardar observaciones" />
					</p>
	            </fieldset>
			</form>
		    <div id="divBoxMessage" class="ui-state-highlight ui-corner-all divBoxMessage"> 
		        <p><span class="ui-icon spnBoxMessage" id="spnBoxIcon"></span>
		        <span id="spnBoxMessage"></span></p>
		    </div>
        </div>
	    <form name="frmBox1" id="frmBox1" class="frmBoxMain" style="margin-top:20px">
	        <fieldset class="ui-widget ui-widget-content ui-corner-all">
	            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Enviar por email</legend> 
				<p>
					Para: <span id="default-email"><?=$cliente_email?></span>
				</p>	
				<p>
					Recibo: <span id="recibo-id"></span>
				</p>
				<p>
					<textarea name="email" id="email" class="ui-widget-content" style="width:100%" rows="5" placeholder="Direcciones de email (CC), separadas por coma"></textarea>
				</p>
	        	<p align="center" style="margin-top:10px">
					<input type="hidden" name="cuota-id" id="cuota-id" value="" />
					<input type="submit" name="btnBox1" id="btnBox1" value="Enviar email" disabled />
				</p>
			</fieldset>
		</form>
</div>