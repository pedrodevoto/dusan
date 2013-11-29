<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Facturación</title>

		<?php require_once('inc/library.php'); ?>               
		
		<!-- Filter initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {	            
				populateListSuc('sucursal_id', 'main');
				
				initDatePickersDaily('box-date', false, null);
				$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
				// Filter: Assign listening functions to input-text for Submit
				listenToTxtForSubmit();				

				var validateForm = $("#frmFiltro").validate({
					rules: {
						"fecha_desde": {required: true, dateAR: true},
						"fecha_hasta": {required: true, dateAR: true},
						"sucursal_id": {required: true}
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
						window.open('export-facturacion.php?'+filtersource);
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
                            <td width="20%">
                                <label for="poliza_numero">Fecha desde</label>                                
                                <input type="text" name="fecha_desde" id="fecha_desde" class="box-date" />
                            </td>
                            <td width="20%">
                                <label for="poliza_numero">Fecha hasta</label>                                
                                <input type="text" name="fecha_hasta" id="fecha_hasta" class="box-date" />
                            </td>
                              
							<td width="20%">
                                <label for="sucursal_nombre">Sucursal</label>                                
                                <select name="sucursal_id" id="sucursal_id">
								</select>
							</td>  
							<td width="20%">
								
							</td>  
							<td width="20%">
								
							</td>  
                        </tr>
                        <tr>                                
                            <td colspan="5" align="center">
                            	<label for="export2">Exportar</label><input name="export" id="export2" type="radio" value="1" checked />
                            </td>  
                        </tr>                                 
                        <tr>                                
                            <td colspan="5" align="center">
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
