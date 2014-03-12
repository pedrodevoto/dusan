<div class="divBoxContainer" style="width:94%">
	<div style="margin-top:20px">
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
			<legend class="ui-widget ui-widget-header ui-corner-all" style="padding:5px">Flota</legend>
			<p>
				<!-- botones -->
				<?php
				require_once('Connections/connection.php');
				$poliza_id = mysql_real_escape_string($_GET['id']);
				$sql = 'SELECT CONCAT(patente_0, patente_1), automotor_id FROM automotor WHERE poliza_id = '.$poliza_id;
				$res = mysql_query($sql, $connection) or die(mysql_error());
				while ($row = mysql_fetch_array($res)) {
					?>
					<input class="flotaedit" polizadet="<?=$row[1]?>" type="button" value="<?=$row[0]?>" />
					<?php
				}
				?>
			</p>
			<p>
				<?php if ($_GET['tipo']=='detalle'):?>
				<input type="button" id="create" value="Agregar vehículo" /> <input type="button" id="certificados" value="Finalizar" />
				<?php endif;?>
			</p>
		</fieldset>
	</div>
</div>