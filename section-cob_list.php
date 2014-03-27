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
				});	
				// Filter: Get focus
				$("#seguro_nombre").focus();				
				
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
						null,					
						null,
						null,
						null,
						null,
						{"sWidth": "8%", "bSearchable": false, "fnRender": function (oObj) {
							var returnval = '';
							returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
							returnval += '<li title="Editar" onclick="openBoxModCob('+oObj.aData[0]+');"><span class="ui-icon ui-icon-pencil"></span></li>';							
							<?php if($_SESSION['ADM_UserGroup']=="master") { ?>
							returnval += '<li title="Eliminar" onclick="deleteViaLink(\'segcob\','+oObj.aData[0]+');"><span class="ui-icon ui-icon-trash"></span></li>';
							<? } ?>
							returnval += '</ul>';
							return returnval;
						}}
					],	
					"aaSorting": [[2,'asc']],					
					
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
		<style>
		table.display tbody tr {
		    height: 16px;
		}
		</style>
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
                                <label for="seguro_nombre">Compañía</label>                                
                                <input type="text" name="seguro_nombre" id="seguro_nombre" maxlength="255" />
                            </td>
							<td width="20%">
								
							</td>  
							<td width="20%">
								
							</td>  
							<td width="20%">
								
							</td>  
                        </tr>
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
                            <th>Cobertura ID (Hide)</th>
							<th>Seguro ID (Hide)</th>
                            <th>Nombre</th>
                            <th>Seguro</th> 
							<th>Límite RC</th>
							<th>Grúas</th>
							<th>Rango años</th>
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
                        </tr>
                    </tfoot>                      
                </table>
            </div>
            <!-- Table End -->
            
    	</div>
	</body>
</html>
