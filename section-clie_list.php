<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Clientes - Listado</title>

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
				populateListSuc('sucursal_id', 'main');
				
				$.when(populateListClientes('cliente_id', 'main')).then(function() {
					$('#cliente_id').chosen().change(function() {
						$('#btnFiltro').click();
					});
					$('#cliente_id_chosen .chosen-drop .chosen-search input').focus();
				});
							
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
						{"sWidth": "20%", "fnRender": function(oObj) {
							return [oObj.aData[1], oObj.aData[2]].join(' ');
							}
						},					
						{"bSearchable": false, "bVisible": false},					
						{"sWidth": "10%"},
						{"sWidth": "15%", "bSearchable": false},
						{"sWidth": "15%", "bSearchable": false},
						{"sWidth": "15%", "bSearchable": false},
						{"sWidth": "10%", "bSearchable": false, "bSortable": false, "fnRender": function (oObj) { 
								var returnval = '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
								
								returnval += '<li title="Modificar" onclick="openBoxModCliente('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li>';
								returnval += '<li title="Asignar Contactos" onclick="openBoxContacto('+oObj.aData[0]+');"><span class="ui-icon ui-icon-person"></span></li>';
								returnval += '<li title="Ver Pólizas" onclick="openBoxPolizas('+oObj.aData[0]+');"><span class="ui-icon ui-icon-document"></span></li>';
								
								<?php if($_SESSION['ADM_UserGroup']=="master") { ?>
								returnval += '<li title="Eliminar" onclick="deleteViaLink(\'cliente\','+oObj.aData[0]+');"><span class="ui-icon ui-icon-trash"></span></li>';
								<? } ?>
								
								returnval += '</ul>'; 
								return returnval;
							}
						}
					],	
					"aaSorting": [[1,'asc'],[2,'asc']],
					
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
												
				}).columnFilter({
					"sPlaceHolder": "head:foot",					
					aoColumns: [
						null,
						{type: "text"},
						{type: "text"},
						{type: "text"},
						null,
						null,
						null,
						null
					]
				}); // Enable column filtering										

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
								<label for="cliente_id">Cliente</label>
								<select style="height:10px" name="cliente_id" id="cliente_id">
								</select>
                            </td>
                            <td width="25%">
                                <label for="cliente_nro_doc">Documento</label>                                
                                <input type="text" name="cliente_nro_doc" id="cliente_nro_doc" maxlength="15" />
                            </td>
                            <td width="25%">
								<label for="sucursal_id">Sucursal</label>                                
								<select name="sucursal_id" id="sucursal_id">
								</select>
                            </td>          
							<td width="25%">
								<label for="cliente_tipo_persona">Tipo de Persona</label>
								<select name="cliente_tipo_persona" id="cliente_tipo_persona">
									<option value="">Elegir</option>
									<option value="1">Persona Física</option>
									<option value="2">Persona Jurídica</option>
								</select>
							</td>
                        </tr>
                        <tr>                                
                            <td colspan="4" align="center">
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
                            <th>Cliente ID (Hide)</th>
                            <th>Nombre</th>                            
                            <th>Apellido</th>                            
                            <th>Documento</th> 
                            <th>Teléfono</th>
                            <th>Celular</th>
							<th>Sucursal</th>
                            <th>Acc.</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
	                <tfoot>
                        <tr>
                            <th></th>
                            <th>Nombre</th>                            
                            <th></th>
							<th>Documento</th> 
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