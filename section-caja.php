<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Caja diaria</title>

		<?php require_once('inc/library.php'); ?>               
		<style>
		label {
			display:inline-block;
			width:120px;
		}
		input.readonly {
			width:50px;
			background-color:transparent;
		}
		</style>
		<!-- Filter initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {	            
				
				$('#btnCajaDiaria, #imprimirCajaDiaria').button();
				initDatePickersDaily('box-date', false, null);
				$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
				
				$('#fecha').val(Date.today().clearTime().toString("dd/MM/yy"));
				
				// Filter: Assign listening functions to input-text for Submit
				listenToTxtForSubmit();				

				$('#box-caja_diaria_cierre').keyup(function() {
					calculateCajaDiaria();
				});

				var validateForm = $("#frmFiltro").validate({
					rules: {
						"sucursal_id": {required: true},
						"fecha": {required: true},
					},
					errorPlacement: function(error, element) {
						error.insertAfter(element.parent("p").children().last());
					}
				});

				// Filter: Submit handler
				$('#btnFiltro').click(function() {						
					if (validateForm.form()) {
						$('#divIngresosSistema, #divIngresos, #divEgresos').html('');
						$('#totalIngresosSistema, #totalIngresos, #totalEgresos').text('0.00');
						$('#leyenda_caja').text(' ');
						$('form').not('#frmFiltro').each(function () {
							this.reset();
						});
						
						var leyenda = 'Caja del día '+$('#fecha').val()+', sucursal '+$("#sucursal_id option:selected").text();
						$('.box-date, .box-datetime').datepicker('option', 'dateFormat', 'yy-mm-dd');
						var fecha1 = $('#fecha').val();
						var sucursal_id1 = $('#sucursal_id').val();
						
						$.when(
							populateDiv_CajaIngresosSistema($('#sucursal_id').val(), $('#fecha').val()),
							populateDiv_CajaEgresos($('#sucursal_id').val(), $('#fecha').val()),
							populateDiv_CajaIngresos($('#sucursal_id').val(), $('#fecha').val()),
							populateFormCajaDiaria($('#sucursal_id').val(), $('#fecha').val())
						).then(function() {
							$('#leyenda_caja').text(leyenda);
							$('#fecha1').val(fecha1);
							$('#sucursal_id1').val(sucursal_id1);
						});
						
						$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					}
				});	
				// Filter: Reset handler							
				$('#btnReset').click(function() {								
					$('#frmFiltro').each(function(){
						this.reset();
						$('#fecha').val(Date.today().clearTime().toString("dd/MM/yy"));
						$('#sucursal_id').val(2); 
					});
				});
				
				$('#btnEgreso, #btnIngreso').button();
				$('#frmEgreso, #frmIngreso').submit(function(event) {
					var form = $(this);
					// Disable button
					$('#btnEgreso, #btnIngreso').button("option", "disabled", true);
					// Set form parameters
					var param = form.serializeArray();
					param.push({
						name: "fecha",
						value: $('#fecha1').val()
					}, 
					{
						name: "sucursal_id", 
						value: $('#sucursal_id1').val()
					});
					// Post
					$.post("insert-caja_"+form.attr('tipo')+".php", param, function (data) {
						if (data == 'Session expired') {
							sessionExpire('main');
						} else {
							// Show message if error ocurred
							if (data.toLowerCase().indexOf("error") != -1) {
								alert($.trim(data));
							} else {
								// Clear form
								form.each(function () {
									this.reset();
								});
								populateDiv_CajaEgresos($('#sucursal_id1').val(), $('#fecha1').val());
								populateDiv_CajaIngresos($('#sucursal_id1').val(), $('#fecha1').val());
							}
							// Enable button
							$('#btnEgreso, #btnIngreso').button("option", "disabled", false);
						}
					});
					event.preventDefault();
				});
				
				$('#frmCajaDiaria').submit(function(event) {
					$('#btnCajaDiaria').button('option', 'disabled', true);
					var param = $(this).serializeArray();
					param.push({
						name: "fecha",
						value: $('#fecha1').val()
					}, 
					{
						name: "sucursal_id", 
						value: $('#sucursal_id1').val()
					});
					param = $.merge(param, $('#frmCuotas').serializeArray());
					$.post('process-caja_diaria.php', param, function(data) {
						if (data == 'Session expired') {
							sessionExpire('main');
						} else {
							$('#frmCajaDiaria').each(function() {
								this.reset();
							});
							populateFormCajaDiaria($('#sucursal_id1').val(), $('#fecha1').val());
							populateDiv_CajaEgresos($('#sucursal_id1').val(), $('#fecha1').val());
							populateDiv_CajaIngresos($('#sucursal_id1').val(), $('#fecha1').val());
							populateDiv_CajaIngresosSistema($('#sucursal_id1').val(), $('#fecha1').val());
						}
						$('#btnCajaDiaria').button('option', 'disabled', false);
					});
					event.preventDefault();
				});
				
				$.when(populateListSuc('sucursal_id', 'main')).then(function(){
					$('#sucursal_id').val(2);
					$('#btnFiltro').click();
				});
			});	
		</script>   
	</head>
	<body>     
		<div id="divContainer">
        
            <!-- Include Header -->
            <?php include('inc/header.php'); ?>

            <!-- Form Start -->
            <div id="divFilter" class="ui-corner-all">                
                <form id="frmFiltro" name="frmFiltro">
                    <table cellpadding="5" cellspacing="0" border="0" width="100%">
                        <tr>                   
							<td width="20%">
                                <label for="sucursal_nombre">Sucursal</label>                                
                                <select name="sucursal_id" id="sucursal_id">
								</select>
							</td>  
                            <td width="20%">
                                <label for="fecha">Fecha</label>                                
                                <input type="text" name="fecha" id="fecha" class="box-date" />
                            </td>
							<td colspan="2">
								
							</td>
                        </tr>
                        <tr>
                            <td colspan="4" align="center">
                                <input type="button" name="btnFiltro" id="btnFiltro" value="Ver caja">&nbsp;<input type="button" name="btnReset" id="btnReset" value="Resetear" >
                            </td>
                        </tr>
                    </table>
                </form>                 
            </div>
			<div style="width:100%;clear:both;margin-top:20px"><span id="leyenda_caja" style="text-align:center;font-size:2em;font-weight:bold"></span></div>
			<input type="hidden" id="fecha1" />
			<input type="hidden" id="sucursal_id1" />
            <!-- Form End -->
			<div style="float:left;width:50%">
				<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
		            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding: 5px;">Ingresos por sistema</legend>
					<form id="frmCuotas">
						<div id="divIngresosSistema" style="height:170px;overflow-y:scroll"></div>
					</form>
					<div style="width:100%;text-align:right">Total recibos por sistema: $<span id="totalIngresosSistema"></span></div>
				</fieldset>
			</div>
			<div style="float:left;width:50%">
				<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
		            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding: 5px;">Egresos</legend>
					<form id="frmEgreso" tipo="egreso">
						<input type="text" name="egreso_detalle" id="egreso_detalle" placeholder="Detalle" style="width:310px" />
						<input type="text" name="egreso_valor" id="egreso_valor" placeholder="Valor" style="width:60px" />
						<input type="submit" id="btnEgreso" value="Guardar" />
					</form>
					<div id="divEgresos" style="height:143px;overflow-y:scroll"></div>
					<div style="width:100%;text-align:right">Total egresos: $<span id="totalEgresos"></span></div>
				</fieldset>
			</div>
			<div style="clear:both"></div>
			<div style="float:left;width:50%">
				<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
		            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding: 5px;">Ingresos manuales</legend>
					<form id="frmIngreso" tipo="ingreso">
						<input type="text" name="ingreso_recibo" id="ingreso_recibo" placeholder="Recibo" style="width:80px" />
						<input type="text" name="ingreso_cliente" id="ingreso_cliente" placeholder="Apellido y nombre de cliente" style="width:220px" />
						<input type="text" name="ingreso_valor" id="ingreso_valor" placeholder="Valor" style="width:60px" />
						<input type="submit" id="btnIngreso" value="Guardar" />
					</form>
					<div id="divIngresos" style="height:143px;overflow-y:scroll"></div>
					<div style="width:100%;text-align:right">Total ingresos: $<span id="totalIngresos"></span></div>			
				</fieldset>
			</div>
			<div style="clear:both"></div>
			<div id="divFilter" class="ui-corner-all" style="margin-top:10px">
				<form id="frmCajaDiaria">
					<div style="float:left;width:25%">
						<p>
							<label>Arrastre del día anterior:</label>$<input type="text" name="box-caja_arrastre_anterior" id="box-caja_arrastre_anterior" class="readonly" value="" readonly />
						</p>
						<p>
							<label>Total ingresos:</label>$<input type="text" name="box-caja_ingresos" id="box-caja_ingresos" class="readonly" value="" readonly />
						</p>
						<p>
							<label>Total egresos:</label>$<input type="text" name="box-caja_egresos" id="box-caja_egresos" class="readonly" value="" readonly />
						</p>
						<p>
							<label>Saldo del día:</label>$<input type="text" name="box-caja_saldo" id="box-caja_saldo" class="readonly" value="" readonly />
						</p>
					</div>
					<div style="float:left;width:25%">
						<p>
							<label>Total arrastre:</label>$<input type="text" name="box-caja_arrastre_total" id="box-caja_arrastre_total" class="readonly" value="" readonly />
						</p>
						<p>
							<label>Apertura de caja:</label>$<input type="text" name="box-caja_diaria_apertura" id="box-caja_diaria_apertura" style="width:50px" />
						</p>
						<p>
							<label>Cierre de caja:</label>$<input type="text" name="box-caja_diaria_cierre" id="box-caja_diaria_cierre" style="width:50px" />
						</p>
						<p>
							<label>Envío de sobre:</label>$<input type="text" name="box-caja_sobre" id="box-caja_sobre" class="readonly" value="" readonly />
						</p>
					</div>
					<div style="float:left;width:25%">
						<p>
							Observaciones del día
						</p>
						<p>
							<textarea name="box-caja_diaria_observaciones" id="box-caja_diaria_observaciones" rows="5" style="width:240px"></textarea>
						</p>
					</div>
					<div style="float:left;width:25%">
						<p style="padding-left:40px">
							<input type="submit" id="btnCajaDiaria" value="Guardar" style="width:120px" />
						</p>
						<p style="padding-left:40px">
							<button id="imprimirCajaDiaria" style="width:120px">Imprimir caja</button>
						</p>
					</div>
					<div style="clear:both"></div>
				</form>
			</div>
    	</div>
	</body>
</html>
