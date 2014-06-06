<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Pólizas - Listado</title>

		<?php require_once('inc/library.php'); ?>               
        
        <!-- Data source variables and related functions -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {		        
			
				// IMPORTANT VARIABLES(!)
				sourceURL = "section-<?php echo(getCurrentSection()); ?>-serv.php";							
			
			});
		</script>                   
        
		<!-- Filter initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {	            
				populateListPlizaEstado('poliza_estado_id', 'main');
				populateListSuc('sucursal_id', 'main');
							
				// Filter: Assign listening functions to input-text for Submit
				listenToTxtForSubmit();				

				// Filter: Submit handler
				$('#btnFiltro').click(function() {						
					var filtersource = $('#frmFiltro').serialize();					
					// Get table data
					var newsource = sourceURL+'?action=view&' + filtersource;
					oTable.fnSettings().sAjaxSource = newsource;
					oTable.fnDraw();
				});	
				// Filter: Reset handler							
				$('#btnReset').click(function() {								
					$('#frmFiltro').each(function(){
						this.reset();
					});
				});	
				
				$('#poliza_anulada').change(function() {
					$('#poliza_vigente, #poliza_vigente_a_renovar, #poliza_cumplida, #poliza_cumplida_renovada, #poliza_pendiente, #poliza_mc').prop('disabled', $(this).prop('checked'));
				});
				
				// Filter: Get focus
				$("#patente").focus();				
				
			});	
		</script>   
		
        <!-- DataTables initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {		

				oTable = $('#table1').dataTable({	

					// General
					"sDom": '<"H"l<"#dtDivHeaderIcons">fr>t<"F"i<"#dtDivFooterTotal">p>',
					"oLanguage": {
						"sUrl": "jquery-plugins/dataTables/media/language/es_AR.txt"						
					},
					"bFilter": true,										
					"bJQueryUI": true,				
					"bAutoWidth": true,
					"sPaginationType": "full_numbers",	
					"bServerSide": true,				
					"sAjaxSource": sourceURL+'?action=view',
					"iDisplayLength": 25,
					"aoColumns": [
						// Hidden fields (IDs)
						{"bSearchable": false, "bVisible": false},
						{"bSearchable": false, "bVisible": false},
						// Visible fields (data and actions)
						{"sWidth": "7%"},
						{"sWidth": "7%"},
						{"sWidth": "6%"},
						{"sWidth": "7%"},
						{"sWidth": "9%"},
						{"sWidth": "9%"},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "5%", "bSearchable": false},
						{"sWidth": "6%", "bSearchable": false},
						{"sWidth": "5%"},
						{"sWidth": "4%",  "bSearchable": false},
						{"sWidth": "6%",  "bSearchable": false, "bVisible": false},
						{"sWidth": "10%", "bSearchable": false, "bSortable": false, "fnRender": function (oObj) {
							var returnval = '';
							returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
							returnval += '<li title="Datos de Póliza" onclick="openBoxModPoliza('+oObj.aData[0]+', \'Patrimoniales\');"><span class="ui-icon ui-icon-pencil"></span></li><li title="Detalle de Póliza" onclick="openBoxPolizaDet('+oObj.aData[0]+', false);"><span class="ui-icon ui-icon-document-b"></span></li>';
							returnval += '<li title="Certificados" onclick="openBoxPolizaCert('+oObj.aData[0]+');"><span class="ui-icon ui-icon-print"></span></li><li title="Plan de Pago" onclick="openBoxCuota('+oObj.aData[0]+');"><span class="ui-icon ui-icon-calculator"></span></li>';
							<?php if($_SESSION['ADM_UserGroup']=='master') {?>
							returnval += '<li title="Renovar Póliza" onclick="openBoxPolizaRen('+oObj.aData[0]+');"><span class="ui-icon ui-icon-refresh"></span></li>';
							<?php } ?>
							returnval += '<li title="Endosos" onclick="openBoxEndosos('+oObj.aData[0]+', '+(oObj.aData[2]?oObj.aData[2]:undefined)+');"><span class="ui-icon ui-icon-folder-collapsed"></span></li>';
							returnval += '<li title="Ver detalle de cliente" onclick="openBoxModCliente('+oObj.aData[1]+');"><span class="ui-icon ui-icon-person"></span></li>';
							<?php if($_SESSION['ADM_UserGroup']=="master") { ?>
							returnval += '<li title="Eliminar" onclick="deleteViaLink(\'poliza\','+oObj.aData[0]+');"><span class="ui-icon ui-icon-trash"></span></li>';
							<? } ?>
							returnval += '</ul>';
							return returnval;
						}}
					],
					"aaSorting": [[0,'desc']],
					
					// Avoid session expired errors
					"fnServerData": function (sSource, aoData, fnCallback) {
						$.getJSON(sSource, aoData, function (json) {
							if(json.error == 'expired'){
								document.location.href='index.php';
							} else{
								fnCallback(json)
							}
						});
					},

					// Load icons when initialized
					"fnInitComplete": function(oSettings, json) {
						$('#dtDivHeaderIcons').load('section-<?php echo(getCurrentSection()); ?>-dticons.php', function(data){
							if (data=='Session expired') {
								sessionExpire('main');
							}
						});
						$('#dtDivFooterTotal').load('section-<?php echo(getCurrentSection()); ?>-dttotal.php', function(data){
							if (data=='Session expired') {
								sessionExpire('main');
							}
						});
					},
					"fnRowCallback": function(nRow, aData, iDisplayIndex) {
						switch (aData[12]) {
						case 'CUMPLIDA':
						case 'CUMPLIDA RENOVADA':
							$(nRow).css('color', 'green');
							break;
						case 'ANULADA':
							$(nRow).css('color', 'red');
							break;
						case 'PENDIENTE':
							$(nRow).css('color', 'grey');
							break;
						default:
							$(nRow).css('color', 'black');
							break;
						}
						if (aData[14]=='No') {
							$('td:eq(12)', nRow).addClass('ui-state-error');
						}
						$('td:eq(12)', nRow).html('<span title="'+aData[15]+'">'+aData[14]+'</span>');
						return nRow;
					}
												
				});		
				$('#btnFiltro').click();

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
                            <td width="12%">
                                <label for="poliza_numero">Poliza N°</label>                                
                                <input type="text" name="poliza_numero" id="poliza_numero" maxlength="20" />
                            </td>
                            <td width="12%">
                                <label for="seguro_nombre">Compañía</label>                                
                                <input type="text" name="seguro_nombre" id="seguro_nombre" maxlength="255" />
                            </td>
                            <td width="12%">
                                <label for="sucursal_nombre">Sucursal</label>                                
                                <select name="sucursal_id" id="sucursal_id">
								</select>
                            </td>
                            <td width="12%">
                                <label for="productor_nombre">Productor</label>                                
                                <input type="text" name="productor_nombre" id="productor_nombre" maxlength="255" />
                            </td>
                            <td width="12%">
                                <label for="cliente_nombre">Cliente</label>                                
                                <input type="text" name="cliente_nombre" id="cliente_nombre" maxlength="255" />
                            </td>
                            <td width="12%">
                                <label for="poliza_medio_pago">Forma de pago</label>                                
                                <select name="poliza_medio_pago" id="poliza_medio_pago">
									<option value="">Todos</option>
									<option value="Tarjeta de Crédito">TC</option>
									<option value="Débito Bancario">Débito Bancario</option>
									<option value="Cuponera">Cup</option>
									<option value="Directo">Directo</option>
								</select>
                            </td>
                            <td width="12%">
                                <label for="poliza_al_dia">Pago al día</label>                                
                                <select name="poliza_al_dia" id="poliza_al_dia">
									<option value="">Indistinto</option>
									<option value="1">Sí</option>
									<option value="0">No</option>
								</select>
                            </td>
                            <td width="12%">
								<label for="poliza_vigencia_dia">Día de vigencia</label>
								<input type="text" name="poliza_vigencia_dia" maxlength="2" />
                            </td>
                        </tr>
						<tr>
							<td colspan="8">
								<label for="poliza_vigente">Vigente</label>
								<input type="checkbox" name="poliza_vigente" id="poliza_vigente" value="3" />
							
								<label class="filtros_estado" for="poliza_vigente_a_renovar">Vigente a Renovar</label>
								<input type="checkbox" name="poliza_vigente_a_renovar" id="poliza_vigente_a_renovar" value="4"></input>
								
								<label class="filtros_estado" for="poliza_vigente_renovada">Vigente Renovada</label>
								<input type="checkbox" name="poliza_vigente_renovada" id="poliza_vigente_renovada" value="7"></input>
							
								<label class="filtros_estado" for="poliza_cumplida">Cumplida</label>
								<input type="checkbox" name="poliza_cumplida" id="poliza_cumplida" value="6"></input>
							
								<label class="filtros_estado" for="poliza_cumplida_renovada">Cumplida Renovada</label>
								<input type="checkbox" name="poliza_cumplida_renovada" id="poliza_cumplida_renovada" value="5"></input>
							
								<label class="filtros_estado" for="poliza_pendiente">Pendiente</label>
								<input type="checkbox" name="poliza_pendiente" id="poliza_pendiente" value="2"></input>
							
								<label class="filtros_estado" for="poliza_mc">M/C</label>
								<input type="checkbox" name="poliza_mc" id="poliza_mc" value="1"></input>
								
								<label class="filtros_estado" for="poliza_anulada">Anulada</label>
								<input type="checkbox" name="poliza_anulada" id="poliza_anulada" value="1"></input>
							</td>
						</tr>
                        <tr>                                
                            <td colspan="8" align="center">
                                <input type="button" name="btnFiltro" id="btnFiltro" value="FILTRAR">&nbsp;<input type="button" name="btnReset" id="btnReset" value="Resetear" >                            
                            </td>
                        </tr>                                    
                    </table>
                </form>                 
            </div>                
            <!-- Form End -->             

            <!-- Table Start (custom padding No-Filter) -->                              	
            <div id="divTable" style="padding-top:15px">              
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="table1">                                
                    <thead>
                        <tr>                        
                            <th>Poliza ID (Hide)</th>
							<th>Cliente ID (Hide)</th>
                            <th>Póliza N°</th>                            
                            <th>Tipo</th> 
                            <th>Compañía</th>
                            <th>Sucursal</th>
                            <th>Productor</th>
                            <th>Cliente</th>
                            <th>Vigencia</th>
                            <th>V. Desde</th>
                            <th>V. Hasta</th>
                            <th>Estado Póliza</th> 
							<th>Estado</th>
                            <th>Fotos</th> 
                            <th>Pago al día</th>                                                                                    
                            <th>Al día detalle</th>                                                                                    
                            <th>Acc.</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
	                <tfoot>
                        <tr>
                            <th></th>
                            <th></th>                            
                            <th></th> 
                            <th></th> 
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th> 
                            <th></th>         
							<th></th>                                                
                            <th></th>                                                         
                            <th></th>                                                        
                        </tr>
                    </tfoot>                      
                </table>
            </div>
            <!-- Table End -->
            
    	</div>
	</body>
</html>