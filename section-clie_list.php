<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Admin - Clientes - Listado</title>

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
					"bAutoWidth": false,
					"sPaginationType": "full_numbers",	
					"bServerSide": true,				
					"sAjaxSource": sourceURL+'?action=view',
					"iDisplayLength": 25,
					"aoColumns": [	
						// Hidden fields (IDs)
						{"bSearchable": false, "bVisible": false},																																																																
						// Visible fields (data and actions)						
						{"sWidth": "20%"},					
						{"sWidth": "10%"},
						{"sWidth": "25%", "bSearchable": false},
						{"sWidth": "15%", "bSearchable": false},
						{"sWidth": "15%", "bSearchable": false},
						{"sWidth": "10%", "bSearchable": false, "bSortable": false, "fnRender": function (oObj) { return '<ul class="dtInlineIconList ui-widget ui-helper-clearfix"><li title="Modificar" onclick="openBoxModCliente('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li><li title="Asignar Contactos" onclick="openBoxContacto('+oObj.aData[0]+');"><span class="ui-icon ui-icon-person"></span></li></li><li title="Ver Pólizas" onclick="openBoxPolizas('+oObj.aData[0]+');"><span class="ui-icon ui-icon-document"></span></li></ul>'; }}
					],	
					"aaSorting": [[1,'asc']],					
					
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

            <!-- Table Start (custom padding No-Filter) -->                              	
            <div id="divTable" style="padding-top:15px">              
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="table1">
                    <thead>
                        <tr>                        
                            <th>Cliente ID (Hide)</th>
                            <th>Nombre</th>                            
                            <th>Documento</th> 
                            <th>E-mail</th>
                            <th>Teléfono 1</th>
                            <th>Teléfono 2</th>
                            <th>Acc.</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
	                <tfoot>
                        <tr>
                            <th></th>
                            <th>Nombre</th>                            
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