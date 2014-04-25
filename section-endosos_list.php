<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Endosos - Listado</title>

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
					$("#endoso_completo").val(0);
				});	
				$("#endoso_completo").val(0);
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
						{"bSearchable": false, "bVisible": false},
						// Visible fields (data and actions)						
						{"sWidth": "10%"},					
						null,
						null,
						null,
						null,
						{"sWidth": "10%"},
						{"sWidth": "8%", "bSearchable": false},
						{"sWidth": "8%", "bSearchable": false, "fnRender": function (oObj) {
							var returnval = '';
							returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
							returnval += '<li title="Editar" onclick="openBoxModEndoso('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li>';
							// returnval += '<li title="Certificados" onclick="openBoxEndosoCert('+oObj.aData[0]+');"><span class="ui-icon ui-icon-print"></span></li>';
							
							returnval += '<li title="Ver detalle de cliente" onclick="openBoxModCliente('+oObj.aData[1]+');"><span class="ui-icon ui-icon-person"></span></li>';
							<?php if($_SESSION['ADM_UserGroup']=="master") { ?>
							returnval += '<li title="Eliminar" onclick="deleteViaLink(\'endoso\','+oObj.aData[0]+');"><span class="ui-icon ui-icon-trash"></span></li>';
							<? } ?>
							returnval += '</ul>';
							return returnval;
						}}
					],	
					"aaSorting": [[7,'desc']],					
					
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
                                <label for="cliente_nro_doc">Completo</label>                                
                                <select name="endoso_completo" id="endoso_completo">
									<option value="">Todos</option>
									<option value="0">No completo</option>
									<option value="1">Completo</option>
								</select>
                            </td>      
							<td width="20%">
								
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
                            <th>Endoso ID (Hide)</th>
							<th>Cliente ID (Hide)</th>
                            <th>Póliza</th>
                            <th>Tipo</th> 
							<th>Nombre del Asegurado</th>
							<th>Compañía</th>
							<th>Sucursal</th>
							<th>Fecha de pedido</th>
                            <th>Completo</th>
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
                        </tr>
                    </tfoot>                      
                </table>
            </div>
            <!-- Table End -->
            
    	</div>
	</body>
</html>
