<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Siniestros - Listado</title>

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
				$("#poliza_numero").focus();				
				
			});	
		</script>   

        <!-- DataTables initialization -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {		

				oTable = $('#table1').dataTable({	

					// General
					"sDom": '<"H"l<"#dtDivHeaderIcons">fr>t<"F"ip>',
					"oLanguage": {
						"sUrl": "jquery-plugins/dataTables/media/language/es_AR.txt"						
					},
					"bFilter": true,										
					"bJQueryUI": true,				
					"bAutoWidth": false,
					"sPaginationType": "full_numbers",	
					"bServerSide": true,				
					"sAjaxSource": sourceURL+'?action=view',
					"iDisplayLength": 25,
					"aoColumns": [	
						// Hidden fields (IDs)
						{"bSearchable": false, "bVisible": false},
						// Visible fields (data and actions)						
						{"sWidth": "10%"},					
						{"sWidth": "8%"},
						null,
						{"sWidth": "20%", "bSearchable": false, "fnRender": function (oObj) {
							switch (oObj.aData[4]) {
							case '1':
								return 'SIN RECLAMO A TERCEROS';
								break;
							case '2':
								return 'CON RECLAMO A TERCEROS';
								break;
							case '3':
								return 'REPOSICION';
								break;
							case '4':
								return 'INSPECCIÓN';
								break;
							case '5':
								return 'ROBO TOTAL DE UNIDAD';
								break;
							case '6':
								return 'INCENDIO TOTAL DE UNIDAD';
								break;
							case '7':
								return 'REINTEGRO';
								break;
							default:
								return '';
								break;
							}
						}},
						null,
						null,
						null,
						{"sWidth": "10%"},
						null,
						{"sWidth": "8%", "bSearchable": false, "fnRender": function (oObj) {
							var returnval = '';
							returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
							returnval += '<li title="Datos de siniestro" onclick="openBoxModSiniestro('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li>';
							returnval += '<li title="Certificados" onclick="openBoxSiniestroCert('+oObj.aData[0]+');"><span class="ui-icon ui-icon-print"></span></li>';
							
							returnval += '<li title="Eliminar" onclick="deleteViaLink(\'siniestro\','+oObj.aData[0]+');"><span class="ui-icon ui-icon-trash"></span></li>';
							returnval += '</ul>';
							return returnval;
						}}
					],	
					"aaSorting": [[1,'desc']],					
					
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
                            <td width="20%">
                                <label for="poliza_numero">Número de póliza</label>                                
                                <input type="text" name="poliza_numero" id="poliza_numero" maxlength="255" />
                            </td>
                            <td width="20%">
								<label for="tipo_siniestro">Tipo de siniestro</label>
								<select name="tipo_siniestro" id="tipo_siniestro" class="ui-widget-content">
									<option value="">Todos</option>
									<option value="1">SIN RECLAMO A TERCEROS</option>
									<option value="2">CON RECLAMO A TERCEROS</option>
									<option value="3">REPOSICION</option>
									<option value="4">INSPECCIÓN</option>
									<option value="5">ROBO TOTAL DE UNIDAD</option>
									<option value="6">INCENDIO TOTAL DE UNIDAD</option>
									<opiton value="7">REINTEGRO</option>
								</select>
                            </td>      
							<td width="20%">
								<label class="filtros_estado" for="estudio_juridico">Estudio jurídico</label>
								<input type="checkbox" name="estudio_juridico" id="estudio_juridico" />
							</td>  
							<td width="20%">
								
							</td>  
							<td width="20%">
								
							</td>  
                        </tr>
                        <tr>                                
                        <tr>                                
                            <td colspan="5" align="center">
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
                            <th>Siniestro ID (Hide)</th>
							<th>Fecha de la denuncia</th>
                            <th>Póliza</th>
							<th>Nombre del Asegurado</th>
							<th>Tipo</th>
							<th>Patente</th>
							<th>Vigencia</th>
                            <th>Número de siniestro</th>
							<th>Mensaje</th>
							<th>Estudio jurídico</th>
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
                        </tr>
                    </tfoot>                      
                </table>
            </div>
            <!-- Table End -->
            
    	</div>
	</body>
</html>
