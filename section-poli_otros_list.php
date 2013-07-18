<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Admin - Pólizas - Listado</title>

		<?php require_once('inc/library.php'); ?>               
        
        <!-- Data source variables and related functions -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {		        
			
				// IMPORTANT VARIABLES(!)
				sourceURL = "section-<?php echo(getCurrentSection()); ?>-serv.php";							
			
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
					"bAutoWidth": true,
					"sPaginationType": "full_numbers",	
					"bServerSide": true,				
					"sAjaxSource": sourceURL+'?action=view',
					"aoColumns": [
						// Hidden fields (IDs)
						{"bSearchable": false, "bVisible": false},
						// Visible fields (data and actions)
						{"sWidth": "8%"},
						{"sWidth": "7%"},
						{"sWidth": "12%"},
						{"sWidth": "12%"},
						{"sWidth": "11%"},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "7%", "bSearchable": false},
						{"sWidth": "8%"},
						{"sWidth": "6%",  "bSearchable": false, "fnRender": function(oObj) {
								return '<span title="'+oObj.aData[11]+'">'+oObj.aData[10]+'</span>';
							}
						},
						{"sWidth": "6%",  "bSearchable": false, "bVisible": false},
						{"sWidth": "10%", "bSearchable": false, "bSortable": false, "fnRender": function (oObj) {
							var returnval = '';
							returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
							returnval += '<li title="Datos de Póliza" onclick="openBoxModPoliza('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li><li title="Detalle de Póliza" onclick="openBoxPolizaDet('+oObj.aData[0]+', false);"><span class="ui-icon ui-icon-document-b"></span></li>';
							if (oObj.aData[9] == 'Si') {
								returnval += '<li title="Certificados" onclick="openBoxPolizaCert('+oObj.aData[0]+');"><span class="ui-icon ui-icon-print"></span></li><li title="Cuotas" onclick="openBoxCuota('+oObj.aData[0]+');"><span class="ui-icon ui-icon-calculator"></span></li><li title="Renovar Póliza" onclick="openBoxPolizaRen('+oObj.aData[0]+');"><span class="ui-icon ui-icon-refresh"></span></li>';
							}
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
					}							
												
				}).columnFilter({			
					aoColumns: [
						null,
						{type: "text"},
						{type: "text"},
						{type: "text"},
						{type: "text"},
						{type: "text"},
						null,
						null,
						null,
						null,
						{type: "select", values: ['PENDIENTE', 'VIGENTE', 'A RENOVAR', 'RENOVADA', 'FINALIZADA']},
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

            <!-- Table Start (custom padding No-Filter) -->                              	
            <div id="divTable" style="padding-top:15px">              
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="table1">                                
                    <thead>
                        <tr>                        
                            <th>Poliza ID (Hide)</th>
                            <th>Póliza N°</th>                            
                            <th>Tipo</th> 
                            <th>Compañía</th>
                            <th>Productor</th>
                            <th>Cliente</th>
                            <th>Vigencia</th>
                            <th>V. Desde</th>
                            <th>V. Hasta</th>
							<th>Estado Póliza</th>
                            <th>Estado</th> 
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
                            <th>Número</th>                            
                            <th>Tipo</th> 
                            <th>Compañía</th>
                            <th>Productor</th>
                            <th>Cliente</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Estado</th> 
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