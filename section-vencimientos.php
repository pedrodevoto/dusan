<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Vencimientos</title>

		<?php require_once('inc/library.php'); ?>               
		
		<!-- Filter initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {	            
				
				initDatePickersDaily('box-date', false, null);
				$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
				
				var years = getYears().reverse();
				var options = '';
				$.each(years, function(i,e) {
					options += '<option value="' + e + '">' + e + '</option>';
				});
				$('#ano').html(options);
				
				var d = new Date();
				var n = d.getMonth();
				$('#mes').val(n+1);
				
				// Filter: Assign listening functions to input-text for Submit
				listenToTxtForSubmit();				

				var validateForm = $("#frmFiltro").validate({
					rules: {
						"ano": {required: true},
						"mes": {required: true},
					},
					errorPlacement: function(error, element) {
						error.insertAfter(element.parent("p").children().last());
					}
				});

				// Filter: Submit handler
				$('#btnFiltro').click(function() {						
					if (validateForm.form()) {
						$('.box-date, .box-datetime').datepicker('option', 'dateFormat', 'yy-mm-dd');
						var filtersource = $('#frmFiltro').serialize();
						$.get('export-facturacion.php', filtersource);
						window.open('export-vencimientos.php?'+filtersource);
						$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					}
				});	
				// Filter: Reset handler							
				$('#btnReset').click(function() {								
					$('#frmFiltro').each(function(){
						this.reset();
					});
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
                            <td width="25%">
                                <label for="ano">Fecha hasta</label>                                
                                <select name="ano" id="ano" style="width:60px"></select>
								<select name="mes" id="mes" style="width:80px">
									<option value="1">Enero</option>
									<option value="2">Febrero</option>
									<option value="3">Marzo</option>
									<option value="4">Abril</option>
									<option value="5">Mayo</option>
									<option value="6">Junio</option>
									<option value="7">Julio</option>
									<option value="8">Agosto</option>
									<option value="9">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
                            </td>
                            <td width="75%">
								<label for="directo">Directo</label>
								<input type="checkbox" name="directo" id="directo" checked />
								<label for="cuponera">Cuponera</label>
								<input type="checkbox" name="cuponera" id="cuponera" checked />
								<label for="domicilio">Cobranza a domicilio</label>
								<input type="checkbox" name="domicilio" id="domicilio" checked />								
							</td>  
                        </tr>
                        <tr>                                
                            <td colspan="3" align="center">
                            	<label for="export2">Exportar</label><input name="export" id="export2" type="radio" value="1" checked />
                            </td>  
                        </tr>                                 
                        <tr>                                
                            <td colspan="3" align="center">
                                <input type="button" name="btnFiltro" id="btnFiltro" value="EXPORTAR">&nbsp;<input type="button" name="btnReset" id="btnReset" value="Resetear" >                            
                            </td>
                        </tr>                                    
                    </table>
                </form>                 
            </div>                
            <!-- Form End -->
    	</div>
	</body>
</html>
