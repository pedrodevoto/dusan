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
						{"sWidth": "4%",  "bSearchable": false, "fnRender": function(oObj) {
								return '<span title="'+oObj.aData[14]+'">'+oObj.aData[13]+'</span>';
							}
						},
						{"sWidth": "6%",  "bSearchable": false, "bVisible": false},
						{"sWidth": "10%", "bSearchable": false, "bSortable": false, "fnRender": function (oObj) {
							var returnval = '';
							returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
							returnval += '<li title="Datos de Póliza" onclick="openBoxModPoliza('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li><li title="Detalle de Póliza" onclick="openBoxPolizaDet('+oObj.aData[0]+', false);"><span class="ui-icon ui-icon-document-b"></span></li>';
							returnval += '<li title="Certificados" onclick="openBoxPolizaCert('+oObj.aData[0]+');"><span class="ui-icon ui-icon-print"></span></li><li title="Cuotas" onclick="openBoxCuota('+oObj.aData[0]+');"><span class="ui-icon ui-icon-calculator"></span></li><li title="Renovar Póliza" onclick="openBoxPolizaRen('+oObj.aData[0]+');"><span class="ui-icon ui-icon-refresh"></span></li>';
							
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
					}							
												
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
                            <td width="16%">
                                <label for="poliza_numero">Poliza N°</label>                                
                                <input type="text" name="poliza_numero" id="poliza_numero" maxlength="20" />
                            </td>
                            <td width="16%">
                                <label for="seguro_nombre">Compañía</label>                                
                                <input type="text" name="seguro_nombre" id="seguro_nombre" maxlength="255" />
                            </td>
                            <td width="16%">
                                <label for="sucursal_nombre">Sucursal</label>                                
                                <input type="text" name="sucursal_nombre" id="sucursal_nombre" maxlength="255" />
                            </td>
                            <td width="16%">
                                <label for="productor_nombre">Productor</label>                                
                                <input type="text" name="productor_nombre" id="productor_nombre" maxlength="255" />
                            </td>
                            <td width="16%">
                                <label for="cliente_nombre">Cliente</label>                                
                                <input type="text" name="cliente_nombre" id="cliente_nombre" maxlength="255" />
                            </td>
                            <td width="16%">
                                <label for="poliza_estado_id">Estado</label>                                
                                <select name="poliza_estado_id" id="poliza_estado_id">
								</select>
                            </td>
                        </tr>
                        <tr>                                
                            <td colspan="6" align="center">
                            	<label for="export2">Mostrar resultados</label><input name="export" id="export2" type="radio" value="0" checked />
                            </td>  
                        </tr>                                 
                        <tr>                                
                            <td colspan="6" align="center">
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
                            <th>Al día</th>                                                                                    
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