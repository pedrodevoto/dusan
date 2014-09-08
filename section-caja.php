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
		
		<!-- Filter initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {	            
				
				$('#guardar, #imprimir').button();
				initDatePickersDaily('box-date', false, null);
				$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
				
				$('#fecha').val(Date.today().clearTime().toString("dd/MM/yy")).change(function() {
					
					$('#leyenda_caja').text('CAJA DEL DÍA '+$(this).val());
				}).change();
				
				// Filter: Assign listening functions to input-text for Submit
				listenToTxtForSubmit();				

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
						$('.box-date, .box-datetime').datepicker('option', 'dateFormat', 'yy-mm-dd');
						populateDiv_CajaIngresosSistema($('#fecha').val());
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
							<td colspan="2">
								<span id="leyenda_caja" style="font-weight:bold"></span>
							</td>
							<td width="20%">
								
							</td>
							<td>
								
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
            <!-- Form End -->
			<div style="float:left;width:50%">
				<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
		            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding: 5px;">Ingresos por sistema</legend>
					<div id="divIngresosSistema" style="height:170px;overflow-y:scroll">a</div>
					<div style="width:100%;text-align:right">Total recibos por sistema: $<span id="totalIngresosSistema"></span></div>
				</fieldset>
			</div>
			<div style="float:left;width:50%">
				<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
		            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding: 5px;">Egresos</legend>
				
				</fieldset>
			</div>
			<div style="clear:both"></div>
			<fieldset class="ui-widget ui-widget-content ui-corner-all" style="margin-top:20px">
	            <legend class="ui-widget ui-widget-header ui-corner-all" style="padding: 5px;">Ingresos manuales</legend>
			
			</fieldset>
			<div id="divFilter" class="ui-corner-all">
				<div style="float:left;width:25%">
					<p>
						Arrastre del día anterior: $<span id="arrastre">999,999</span>
					</p>
					<p>
						Total ingresos: $<span id="total-ingresos">1120</span>
					</p>
					<p>
						Total egresos: $<span id="total-egresos">0</span>
					</p>
					<p>
						Saldo del día: $<span id="saldo-dia">1120</span>
					</p>
				</div>
				<div style="float:left;width:25%">
					<p>
						Total arrastre: $<span id="total-arrastre">1,001,119</span>
					</p>
					<p>
						<label style="display:inline-block;width:80px">Apertura de caja:</label> <input type="text" name="apertura_caja" id="apertura_caja" style="width:60px" />
					</p>
					<p>
						<label style="display:inline-block;width:80px">Cierre de caja:</label> <input type="text" name="cierre_caja" id="cierre_caja" style="width:60px" />
					</p>
					<p>
						Envío de sobre: $<span id="envio-sobre">900</span>
					</p>
				</div>
				<div style="float:left;width:25%">
					<p>
						Observaciones del día
					</p>
					<p>
						<textarea name="observaciones" id="observaciones" rows="4" style="width:240px"></textarea>
					</p>
				</div>
				<div style="float:left;width:25%">
					<p style="padding-left:40px">
						<input type="submit" id="guardar" value="Guardar" style="width:120px" />
					</p>
					<p style="padding-left:40px">
						<button id="imprimir" style="width:120px">Imprimir caja</button>
					</p>
				</div>
				<div style="clear:both"></div>
			</div>
    	</div>
	</body>
</html>
