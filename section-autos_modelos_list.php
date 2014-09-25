<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Modelos de autos - Listado</title>

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
				var mostrar = '';
				populateListAutoMarca('marca_id', 'main');
				$('#marca_id').change(function() {
					populateListAutoModelo('modelo_id', 'box', $(this).val());
				});
				
				$('#autos_view').buttonset();
				$('.autos_view').click(function() {
					switch ($(this).prop('id')) {
					case 'autos_view1':
						mostrar = 'marcas';
						$('#frmFiltro').hide();
						$('#btnNuevoModelo, #btnNuevaVersion').hide();
						$('#btnNuevaMarca').show();
						break;
					case 'autos_view2':
						mostrar = 'modelos';
						$('#frmFiltro').show();
						$('#btnNuevaMarca, #btnNuevaVersion').hide();
						$('#btnNuevoModelo').show();
						break;
					case 'autos_view3':
						mostrar = 'versiones';
						$('#frmFiltro').show();
						$('#btnNuevaMarca, #btnNuevoModelo').hide();
						$('#btnNuevaVersion').show();
						break;
					}
					var newsource = sourceURL+'?action=view&mostrar='+mostrar;
					oTable.fnSettings().sAjaxSource = newsource;
					oTable.fnDraw();
				});
				
				// Filter: Assign listening functions to input-text for Submit
				listenToTxtForSubmit();				

				// Filter: Submit handler
				$('#btnFiltro').click(function() {						
					var filtersource = $('#frmFiltro').serialize();					
					// Get table data
					var newsource = sourceURL+'?action=view&mostrar='+mostrar+'&'+filtersource;
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
					"bAutoWidth": true,
					"sPaginationType": "full_numbers",	
					"bServerSide": true,				
					"sAjaxSource": sourceURL+'?action=view&mostrar=marcas',
					"aoColumns": [	
						// Hidden fields (IDs)
						{"bSearchable": false, "bVisible": false},
						{"bSearchable": false, "bVisible": false},
						// Visible fields (data and actions)
						null,						
						{"bSearchable": false, "bSortable": false, "fnRender": function (oObj) { 
							var f = '';
							switch (oObj.aData[1]) {
							case 'marcas':
								f = 'openBoxModAutoMarca('+oObj.aData[0]+');';
								break;
							case 'modelos':
								f = 'openBoxModAutoModelo('+oObj.aData[0]+');';
								break;
							case 'versiones':
								f = 'openBoxModAutoVersion('+oObj.aData[0]+');';
								break;
							}
							return '<ul class="dtInlineIconList ui-widget ui-helper-clearfix"><li title="Modificar" onclick="'+f+'"><span class="ui-icon ui-icon-pencil"></span></li></ul>'; 
						}}
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
						null,
						{type: "text"},
						null
					]
				}); // Enable column filtering										
				$('#btnNuevo').button().button('option', 'label', 'Crear marca').click(function() {
					openBoxAltaAutomotorMarca();
				});
			});	
		</script>      

	</head>
	<body>
		<div id="divContainer">
        
            <!-- Include Header -->
            <?php include('inc/header.php'); ?>
			
			<div id="autos_view">
				<input type="radio" id="autos_view1" name="autos_view" class="autos_view" checked="checked"><label for="autos_view1">Marcas</label>
				<input type="radio" id="autos_view2" name="autos_view" class="autos_view" ><label for="autos_view2">Modelos</label>
				<input type="radio" id="autos_view3" name="autos_view" class="autos_view" ><label for="autos_view3">Versiones</label>
			</div>
			
            <!-- Form Start -->
            <div id="divFilter" class="ui-corner-all" style="min-height:67px">                
                <form id="frmFiltro" name="frmFiltro" style="display:none">
                    <table cellpadding="5" cellspacing="0" border="0" width="100%">
                        <tr>                   
                            <td width="25%">
								<label for="marca_id">Marca</label>
								<select style="height:10px" name="marca_id" id="marca_id">
								</select>
                            </td>
                            <td width="25%">
								<label for="modelo_id">Modelo</label>
								<select style="height:10px" name="modelo_id" id="modelo_id">
								</select>
                            </td>
                            <td width="50%">
								
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
                            <th>Sucursal ID (Hide)</th>
                            <th>Tipo registro (Hide)</th>
                            <th>Nombre</th>                            
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
                        </tr>
                    </tfoot>                      
                </table>
            </div>
            <!-- Table End -->
            
    	</div>
	</body>
</html>