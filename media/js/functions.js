$(document).ready(function() {

	/* ---------------------------- FILTER AND REUSABLE FUNCTIONS ---------------------------- */

	<!-- Session functions -->
	sessionExpire = function(type) {
		switch(type) {
			case 'main':
				document.location.href='index.php';
				break;
			case 'box':
				$.colorbox.close();
				document.location.href='index.php';
				break;	
		}
	}
	
	<!-- Formatting functions -->
	nullToSpace = function(value) {
		if (value==null) {
			return '&nbsp;';
		} else {
			return value;
		}
	}
		
	<!-- List functions -->
	sortListAlpha = function(field) {
		$("select#"+field).html($("select#"+field+" option").sort(function (a, b) {
			return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
		}));			
	}
	sortListValue = function (field) {
		$("#"+field).html($("#"+field+" option").sort(function (a, b) {
			var aValue = parseInt(a.value);
			var bValue = parseInt(b.value);
			return aValue == bValue ? 0 : aValue < bValue ? -1 : 1;
		}));
	}
	appendListItem = function (field, optionvalue, optiontext) {
		$("select#"+field).prepend($('<option />').attr('value', optionvalue).text(optiontext));
	}
	selectFirstItem = function (field) {
		$("select#"+field).val($("select#"+field+" option:first").val());		
	}
	
	<!-- Initialize Special Field functions -->
	initDatePickersDaily = function (clase, clear, maxdate) {
		$("."+clase).each(function() {
			var date = $(this).datepicker({
				dateFormat: 'yy-mm-dd'					
			});	
			if (clear==true) {				
				date.click(function () {
					$(this).val("");
				});
			}
			if (maxdate!=null) {				
				date.datepicker("option", "maxDate", maxdate);			
			}			
		});
	}
	initDatePickersWeekly = function (clase, clear, weekday) {
		$("."+clase).each(function() {
			var date = $(this).datepicker({
				dateFormat: 'yy-mm-dd',
				beforeShowDay: function(date){ return [date.getDay() == weekday,""]}
			});					
			if (clear==true) {				
				date.click(function () {
					$(this).val("");
				});
			}			
		});
	}
	initAutocompleteCliente = function (field, context) {
		$("#"+field).autocomplete({
			source: "get-json-cliente_nombre.php",
			minLength: 2,
            select: function (event, ui) {
				if (ui.item.value=='Session expired') {
					sessionExpire(context);
				}
            }
		});
	}
	
	<!-- Filter functions -->
	disableFilters = function(disabled) {					
		$(".tobedisabled").each(function() {
			if (disabled==true) {
				$(this).attr("disabled","disabled");			
			} else {
				$(this).removeAttr('disabled');
			}
		}); 					
	}									
	checkIfDateFieldIsEmpty = function() {
		var validate = true;
		$(".datedisabler").each(function() {
			if ($(this).val() != "") {
				validate = false;
			}
		}); 
		return validate;
	}
	checkIfTextFieldIsEmpty = function() {
		var validate = true;
		$(".txtdisabler").each(function() {
			if ($(this).val() != "") {
				validate = false;
			}
		}); 
		return validate;
	}				
	checkFilters = function() {
		if (checkIfDateFieldIsEmpty() && checkIfTextFieldIsEmpty()) {
			disableFilters(false);
		} else {
			disableFilters(true);
		}
	}	
	listenToTxtForDisable = function() {
		$(".txtdisabler").each(function() {							
			$(this).keyup(function () {
				checkFilters();				
			});					
		}); 
	}
	listenToDateForDisable = function() {	
		$(".datedisabler").each(function() {
			$(this).click(function () {
				checkFilters();
			});		
			$(this).change(function() {
				checkFilters();
			});
		});	
	}	
	listenToTxtForSubmit = function () {
		$("form#frmFiltro :input[type=text]").each(function() {
			$(this).keypress(function(e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					$('#btnFiltro').click();
					return false;
				}
			});
		});		
	}
	
	<!-- Form Disable/Enable/Clear functions -->
	formDisable = function (form, type, disabled) {
		// Enable-disable general inputs
        $("#"+form+" textarea").attr("disabled", disabled);		
        $("#"+form+" select").attr("disabled", disabled);		
        $("#"+form+" input[type='text']").attr("disabled", disabled);
        $("#"+form+" input[type='password']").attr("disabled", disabled);		
        $("#"+form+" input[type='radio']").attr("disabled", disabled);
        $("#"+form+" input[type='checkbox']").attr("disabled", disabled);	
		// Enable-disable buttons
		if (type=='ui') {
			$("#"+form+" input[type='button']").button("option", "disabled", disabled);	
			$("#"+form+" input[type='submit']").button("option", "disabled", disabled);						
		} else {
			$("#"+form+" input[type='button']").attr("disabled", disabled);
			$("#"+form+" input[type='submit']").attr("disabled", disabled);			
		}
	}
	
	<!-- Populate List functions -->
	populateListUsuario_Acceso = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-usr_acceso.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options by index value
					sortListValue(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListUsuario_Sucursal = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-suc.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options by index value
					sortListValue(field);
					// Append option: "all"
					// appendListItem(field, '', 'Todos');
					// Select first item
					// selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListProductor_IVA = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-pro_iva.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	
	populateListSuc = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-suc.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	
	populateListSeguro = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-seguro.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListCliente_Sexo = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-clie_sexo.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListCliente_TipoDoc = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-clie_tipodoc.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListCliente_RegTipo = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-clie_regtipo.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListCliente_CF = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-clie_cf.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListContacto_Tipo = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-contacto_tipo.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListTipoPoliza = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-tipopoliza.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListSubtipoPoliza = function(parent_id, field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-subtipopoliza.php?id="+parent_id,
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else {				
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}	
	populateListPoliza_Vigencia = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-poliza_vigencia.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListPoliza_Cuotas = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-poliza_cuotas.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListPoliza_MP = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-poliza_mp.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListProductorSeguro_Productor = function(parent_id, field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-productorseguro_productor.php?id="+parent_id,
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else {				
					var options = ''; 
					$.each(j, function(key, value) { 
						options += '<option value="' + key + '">' + value + '</option>';
					});		
					$('#'+field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Todos');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}												
	
	<!-- Delete via Link functions -->	
	deleteViaLink = function(section, id){	
		var dfd = new $.Deferred();						
		if (confirm('Está seguro que desea eliminar el registro?\n\nEsta acción no puede deshacerse.')) {
			$.post('delete-'+section+'.php', {id: id}, function(data){
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}		
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));						
				}
				dfd.resolve();				
			});
		} else {
			dfd.resolve();			
		}
		return dfd.promise();		
	}		
	
	/* ---------------------------- DIALOG FUNCTIONS ----------------------------------- */
	
	// Use this space for dialog related functions			
	
	/* --------------------------------- BOX FUNCTIONS --------------------------------- */	
	
	<!-- General functions -->
	showBoxConf = function(data, autoscroll, hide, delay, callback){
		// Hide previous message before showing new one
		$("#divBoxMessage").hide();
		// Check for errors
		var ok = (data.toLowerCase().indexOf("error") === -1);
		// Set message icon
		if (ok) {
			$("#spnBoxIcon").removeClass("ui-icon-alert");
			$("#spnBoxIcon").addClass("ui-icon-info");
		} else {
			$("#spnBoxIcon").removeClass("ui-icon-info");
			$("#spnBoxIcon").addClass("ui-icon-alert");
		}
		// Set message
		$("#spnBoxMessage").html(data);
		// Show DIV
		$("#divBoxMessage").show("fast", function(){
			// If autoscroll was set, scroll to bottom			
			if (autoscroll == true) {				
				$("#cboxLoadedContent").scrollTop($("#cboxLoadedContent")[0].scrollHeight);				
			}
		});		
		// Determine hide method
		switch (hide) {
			case 'always':
				// Delay and hide			
				$("#divBoxMessage").delay(delay).hide("fast", function(){
					// If no error ocurred, execute callback function
					if (ok) { callback(ok); }
					// Enable button
					$('#btnBox').button("option", "disabled", false);
				});								
				break;
			case 'onerror':
				// If an error occurred
				if (!ok) {
					// Delay and hide			
					$("#divBoxMessage").delay(delay).hide("fast", function(){
						// Enable button
						$('#btnBox').button("option", "disabled", false);						
					});
				} else {
					// Execute callback
					callback(ok);					
					// Enable button
					$('#btnBox').button("option", "disabled", false);					
				}
				break;
			case 'never':
				// If no error ocurred, execute callback function
				if (ok) { callback(ok); }
				// Enable button
				$('#btnBox').button("option", "disabled", false);				
				break;
		}
	}	
		
	<!-- Populate form functions -->
	populateFormGeneric = function (j, target) {
		$.each(j, function(key, value) {
			var element = '#'+target+'-'+key;
			if ($(element).length>0) {
				switch ($(element).attr('type')) {
					case 'checkbox':
						if(value==1) {
							$(element).attr('checked', true);
						} else if (value==0) {
							$(element).attr('checked', false);
						}
						break;
					default:
						$(element).val($(element).prop("multiple")?value.split(','):value);
						break;
				}
			}
		});
	}		
	populateFormBoxUsuario = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_usr.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListUsuario_Acceso('box-usuario_acceso','box'),
						populateListUsuario_Sucursal('box-usuario_sucursal','box')
					).then(function(){						
						// Populate Form
						populateFormGeneric(j, "box");															
						// Resolve
						dfd.resolve();														
					});
				}
			}
		});	
		return dfd.promise();				
	}
	populateFormBoxSeguro = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_seguro.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate Form
					populateFormGeneric(j, "box");																
					// Resolve
					dfd.resolve();														
				}
			}
		});	
		return dfd.promise();				
	}
	populateFormBoxProd = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_prod.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListProductor_IVA('box-productor_iva','box')
					).then(function(){						
						// Populate Form
						populateFormGeneric(j, "box");															
						// Resolve
						dfd.resolve();														
					});													
				}
			}
		});	
		return dfd.promise();				
	}
	
	populateFormBoxSuc = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_suc.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate Form
					populateFormGeneric(j, "box");																
					// Resolve
					dfd.resolve();														
				}
			}
		});	
		return dfd.promise();				
	}
	
	populateFormBoxCliente = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_cliente.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListCliente_Sexo('box-cliente_sexo', 'box'),
						populateListCliente_CF('box-cliente_cf', 'box'),
						populateListCliente_TipoDoc('box-cliente_tipo_doc', 'box'),
						populateListCliente_RegTipo('box-cliente_reg_tipo', 'box')
					).then(function(){	
						// Populate Form
						populateFormGeneric(j, "box");																
						// Resolve
						dfd.resolve();														
					});
				}
			}
		});	
		return dfd.promise();				
	}
	populateFormBoxPoliza = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_poliza.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListSeguro('box-seguro_id', 'box'),
						populateListProductorSeguro_Productor(j.seguro_id, 'box-productor_seguro_id', 'box'),
						populateListPoliza_MP('box-poliza_medio_pago', 'box')
					).then(function(){	
						// Populate Form
						populateFormGeneric(j, "box");
						$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');						
						// Resolve
						dfd.resolve();														
					});
				}
			}
		});	
		return dfd.promise();				
	}
	populateFormBoxPolizaRen = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_polizaren.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListSeguro('box-seguro_id', 'box'),
						populateListProductorSeguro_Productor(j.seguro_id, 'box-productor_seguro_id', 'box'),
						populateListPoliza_Vigencia('box-poliza_vigencia', 'box'),
						populateListPoliza_Cuotas('box-poliza_cuotas', 'box'),						
						populateListPoliza_MP('box-poliza_medio_pago', 'box')
					).then(function(){	
						// Populate Form
						populateFormGeneric(j, "box");																
						// Resolve
						dfd.resolve();														
					});
				}
			}
		});	
		return dfd.promise();				
	}		
	populateFormBoxPolizaDet = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_polizadet.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired'){
					// Session expired
					sessionExpire('box');				
				} else {
					// Populate Form
					populateFormGeneric(j, "box");
					// Call post function
					if (typeof(polDetInit) == "function") {
						polDetInit();
					}
					// Resolve
					dfd.resolve();														
				}
			}
		});	
		return dfd.promise();				
	}
	
	<!-- Other form functions -->
	assignClientToPoliza = function(id){
		$.ajax({
			url: "get-json-fich_poliza-cliente_nombre.php?id="+id,
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire('box');
				} else {		
					if (j.empty != true) {				
						// Populate main form
						$('#box-cliente_nombre').val(j.cliente_nombre);
						$('#box-cliente_id').val(j.cliente_id);
						// Clear search form
						$('#frmSelectClient').each(function(){
							this.reset();
						});						
						// Clear search results
						$('#divBoxClienteSearchResults').html('');						
						// Enable main form
						formDisable('frmBox','ui',false);
						// Set focus
						$("#box-tipo_poliza_id").focus();															
					}
				}
			}
		});
	}
	
	<!-- Populate DIV functions -->
	populateDiv_Prod_Info = function(id){
		$.getJSON("get-json-prod_info.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				// Session expired				
				sessionExpire('box');
			} else {			
				if (j.empty == true) {			
					// Record not found
					$.colorbox.close();													
				} else {
					var result = '';
					// Open Table and Row
					result += '<table class="tblBox">';			
					result += '<tr>';							
					// Table Data
					result += '<td><strong>Nombre:</strong> ' + j.productor_nombre + '</td>';
					result += '<td><strong>CUIT:</strong> ' + j.productor_cuit + '</td>';
					result += '<td><strong>Matrícula:</strong> ' + j.productor_matricula + '</td>';
					// Close Row and Table
					result += '</tr>';											
					result += '</table>';
					// Populate DIV					
					$('#divBoxInfo').html(result);						
				}
			}
		});						
	}
	populateDiv_ProdSeg = function(id){		
		$.getJSON("get-json-fich_prodseg.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				var result = '';			
				// Check if empty
				if (j.length>0) {
					// Sort data
					j.sort(function(a,b) { return a.seguro_nombre == b.seguro_nombre ? 0 : a.seguro_nombre < b.seguro_nombre ? -1 : 1; } );						
					// Open Table
					result += '<table class="tblBox">';			
					// Table Head
					result += '<tr>';
					result += '<th width="40%">Seguro</th>';
					result += '<th width="45%">Código</th>';
					result += '<th width="15%">Acciones</th>';					
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td>'+object.seguro_nombre+'</td>';
						result += '<td><span class="jeditrow1" id="prodseg_'+object.productor_seguro_id+'">'+object.productor_seguro_codigo+'</span></td>';														
						result += '<td><span onClick="javascript:deleteProdSeg('+object.productor_seguro_id+', '+id+')" style="cursor: pointer;" class="ui-icon ui-icon-trash" title="Eliminar"></span></td>';
						result += '</tr>';									
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'El Productor no posee Seguros asignados.';
				}
				// Populate DIV					
				$('#divBoxList').html(result);
				// Make rows editable	
				$('.jeditrow1').editable('update-prodseg_code.php', { 
					indicator: 'Guardando...',
         			tooltip: 'Click para editar...',
				    width: '200',
					height: '10'
				});								
			}
		});						
	}
	populateDiv_Cliente_Info = function(id){
		$.getJSON("get-json-cliente_info.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				// Session expired				
				sessionExpire('box');
			} else {			
				if (j.empty == true) {			
					// Record not found
					$.colorbox.close();													
				} else {
					var result = '';
					// Open Table and Row
					result += '<table class="tblBox">';			
					result += '<tr>';							
					// Table Data
					result += '<td><strong>Nombre:</strong> <a title="Ir a Cliente" href="#" onclick="openBoxModCliente(\''+j.cliente_id+'\')">' + j.cliente_nombre + '</a></td>';
					result += '<td><strong>Tipo Doc:</strong> ' + j.cliente_tipo_doc + '</td>';
					result += '<td><strong>Nº Doc:</strong> ' + j.cliente_nro_doc + '</td>';
					// Close Row and Table
					result += '</tr>';											
					result += '</table>';
					// Populate DIV					
					$('#divBoxInfo').html(result);						
				}
			}
		});						
	}
	
	populateDiv_Contacto = function(id){		
		$.getJSON("get-json-fich_contacto.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				var result = '';			
				// Check if empty
				if (j.length>0) {
					// Sort data
					j.sort(function (a, b) {
						var aValue = parseInt(a.contacto_id);
						var bValue = parseInt(b.contacto_id);
						return aValue == bValue ? 0 : aValue < bValue ? -1 : 1;
					});					
					// Open Table
					result += '<table class="tblBox">';			
					// Table Head
					result += '<tr>';
					result += '<th height="22">Prim.</th>';					
					result += '<th>Tipo</th>';
					result += '<th>Dirección</th>';
					result += '<th>Nro.</th>';
					result += '<th>Piso</th>';
					result += '<th>Dpto</th>';
					result += '<th>Localidad</th>';
					result += '<th>CP</th>';
					result += '<th>Teléfono 1</th>';
					result += '<th>Teléfono 2</th>';
					result += '<th>Acción</th>';																				
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						result += '<tr>';
						if (object.contacto_default == 1) {
							result += '<td><strong>X</strong></td>';
						} else {
							result += '<td>&nbsp;</td>';
						}
						result += '<td>'+object.contacto_tipo+'</td>';
						result += '<td>'+object.contacto_domicilio+'</td>';
						result += '<td>'+object.contacto_nro+'</td>';
						result += '<td>'+object.contacto_piso+'</td>';
						result += '<td>'+object.contacto_dpto+'</td>';
						result += '<td>'+object.contacto_localidad+'</td>';
						result += '<td>'+object.contacto_cp+'</td>';
						result += '<td>'+object.contacto_telefono1+'</td>';
						result += '<td>'+object.contacto_telefono2+'</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Eliminar" onClick="javascript:deleteContacto('+object.contacto_id+', '+id+');"><span class="ui-icon ui-icon-trash"></span></li>';
						if (object.contacto_default == 0) {
							result += '<li title="Establecer por defecto" onClick="javascript:updateLinkContacto_Default('+object.contacto_id+', '+id+');"><span class="ui-icon ui-icon-star"></span></li>';
						}
						result += '</ul></td>';						
						result += '</tr>';									
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'El Cliente no posee Contactos asignados.';
				}
				// Populate DIV					
				$('#divBoxList').html(result);								
			}
		});						
	}
	populateDiv_Polizas = function(id){		
		$.getJSON("get-json-fich_cliepoli.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				var result = '';			
				// Check if empty
				if (j.length>0) {
					
					// Open Table
					result += '<table class="tblBox">';			
					// Table Head
					result += '<tr>';
					result += '<th width="25%">Póliza N˚</th>';
					result += '<th width="25%">Tipo</th>';
					result += '<th width="25%">Al día</th>';	
					result += '<th width="25%">Acciones</th>';									
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td>'+object.poliza_numero+'</td>';
						result += '<td>'+object.subtipo_poliza_nombre+'</td>';	
						result += '<td><span title="'+object.poliza_al_dia_detalle+'">'+object.poliza_al_dia+'</span></td>';														
						result += '<td><span onClick="openBoxPolizaDet('+object.poliza_id+')" style="cursor: pointer;" class="ui-icon ui-icon-extlink" title="Ir a Póliza"></span></td>';
						result += '</tr>';									
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'El cliente no posee Pólizas.';
				}
				// Populate DIV					
				$('#divBoxList').html(result);					
			}
		});			
	}
	populateDiv_Poliza_Info = function(id){
		$.getJSON("get-json-poliza_info.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				// Session expired				
				sessionExpire('box');
			} else {			
				if (j.empty == true) {			
					// Record not found
					$.colorbox.close();													
				} else {
					var result = '';
					// Open Table and Row
					result += '<table class="tblBox">';			
					result += '<tr>';							
					// Table Data
					result += '<tr><td><strong>Cliente:</strong> ' + j.cliente_nombre + '</td></tr>';
					result += '<tr><td><strong>Compañía:</strong> ' + j.seguro_nombre + '</td></tr>';
					result += '<tr><td><strong>Productor:</strong> ' + j.productor_nombre + '</td></tr>';
					result += '<tr><td><strong>Poliza Nº:</strong> ';
					if (j.poliza_numero == '') {
						result += '-';
					} else {
						result += j.poliza_numero;
					}
					result += '</td></tr>';					
					// Close Row and Table
					result += '</tr>';											
					result += '</table>';
					// Populate DIV					
					$('#divBoxInfo').html(result);						
				}
			}
		});						
	}
	populateDiv_Poliza_Fotos = function(id){
		$.getJSON("get-json-poliza_fotos.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// General variables
					var result = '';			
					result += "<table><tbody><tr>";		
					// Table Data
					$.each(j, function(i, object) {
						
						
						result += '<td align="center" class="ui-state-default ui-corner-all" style="width:100px;height:115px;overflow: hidden;white-space: nowrap"><a href="'+object.poliza_foto_url+'" onclick="divShowFoto(\''+object.poliza_foto_url+'\', \''+object.poliza_foto_width+'\', \''+object.poliza_foto_height+'\');return false;"><img width="100" height="100" style="vertical-align:middle;" src="' + object.poliza_foto_thumb_url + '" /></a>';	
						result += '<br />';
						result += '<span style="float:right"><ul class="dtInlineIconList ui-widget ui-helper-clearfix"><li title="Abrir en nueva ventana" onclick="window.open(\''+object.poliza_foto_url+'\');"><span class="ui-icon ui-icon-newwin"></span></li><li title="Eliminar" onclick="deleteViaLink(\'poliza_foto\', \''+object.poliza_foto_id+'\');$(\'#divShowFoto\').hide();populateDiv_Poliza_Fotos('+id+');"><span class="ui-icon ui-icon-trash"></span></li></ul></span>';				
						result += '</td>';
					});
					// Close Table									
					result += '</tr></tbody></table>';
					// Populate DIV					
					$('#divBoxFotos').html(result);
				}
			}
		});							
	}
	divShowFoto = function(url, width, height) {
		if ($("#divShowFoto").prop("showing") != url) {
			$("#divShowFoto").prop("showing", url);
			$("#divShowFoto").html('<img src="'+url+'" width="'+width+'" height="'+height+'" />');
			$("#divShowFoto").css({"overflow": "auto", "height": height+"px"});
			$("#divShowFoto").show({easing: "swing"});
		}
		else {
			$("#divShowFoto").toggle({easing: "swing"});
		}
	}
	populateDiv_Cliente_Results = function() {
		$.getJSON("get-json-fich_poliza-cliente_search.php", $("#frmSelectClient").serialize(), function(j) {
			if(j.error == 'expired') {
				sessionExpire('box');
			} else {			
				if (j.empty == true) {
					$('#divBoxClienteSearchResults').html('Cliente no encontrado. Intente nuevamente.');
				} else {
					var result = '';
					<!-- Open Table and Row -->
					result += '<table class="tblBox2">';
					result += '<tr>';
					<!-- Table Data -->
					result += '<td>' + j.cliente_nombre + '</td>';
					result += '<td><strong>Documento:</strong> ' + j.cliente_tipo_doc + ' ' + j.cliente_nro_doc + '</td>';
					result += '<td><a href="javascript:assignClientToPoliza(' + j.cliente_id + ')">SELECCIONAR</a></td>';
					<!-- Close Row and Table -->
					result += '</tr>';
					result += '</table>';
					$('#divBoxClienteSearchResults').html(result);
				}
			}			
		});		
	}
	populateDiv_Cuotas = function(id){
		$.getJSON("get-json-fich_cuota.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Sort data
					j.sort(function (a, b) {
						var aValue = parseInt(a.cuota_nro);
						var bValue = parseInt(b.cuota_nro);
						return aValue == bValue ? 0 : aValue < bValue ? -1 : 1;
					});
					// General variables
					var result = '';					
					// Open Table and Headers
					result += '<table class="tblBox">';
					result += '<tr>';
					result += '<th height="21">Cuota</th>';
					result += '<th>Período</th>';
					result += '<th width="85">Monto</th>';
					result += '<th width="95">F. Venc.</th>';					
					result += '<th width="160">Estado</th>';
					result += '<th>F. de Pago</th>';
					result += '<th>Recibo</th>';
					result += '<th>PFC</th>';					
					result += '<th>Acc.</th>';					
					result += '</tr>';
					// Table Data
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td height="21">' + object.cuota_nro + '</td>';
						result += '<td>' + object.cuota_periodo + '</td>';	
						result += '<td><span class="jeditrow1" id="monto_'+object.cuota_id+'">'+object.cuota_monto+'</span></td>';
						result += '<td><span class="jeditrow2" id="vencimiento_'+object.cuota_id+'">'+object.cuota_vencimiento+'</span></td>';
						result += '<td><span class="jeditrow3" id="estado_' + object.cuota_id + '">' + object.cuota_estado + '</span></td>';
						result += '<td>' + object.cuota_fe_pago + '</td>';	
						result += '<td>' + object.cuota_recibo + '</td>';
						result += '<td>';
						if (object.cuota_nro == 1) {
							if (object.cuota_pfc == 1) {
								result += '<span onClick="javascript:updateLinkCuota_PFC('+object.cuota_id+', '+id+');" style="cursor: pointer;" class="ui-icon ui-icon-circle-check" title="Cambiar"></span>';
							} else {
								result += '<span onClick="javascript:updateLinkCuota_PFC('+object.cuota_id+', '+id+');" style="cursor: pointer;" class="ui-icon ui-icon-circle-close" title="Cambiar"></span>';
							}
						} else {
							result += '&nbsp;';
						}
						result += '</td>';
						result += '<td>';						
						if (object.cuota_estado === '2 - Pagado') {
							result += '<span onClick="javascript:window.open(\'print-cuota.php?id='+object.cuota_id+'&print\');" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-print" title="Imprimir"></span>';
							result += '<span onClick="javascript:window.open(\'print-cuota.php?id='+object.cuota_id+'\');" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-mail-closed" title="Digital"></span>';
						} else {
							result += '&nbsp;';
						}
						result += '</td>';						
						result += '</tr>';
					});
					// Close Table									
					result += '</table>';
					// Populate DIV					
					$('#divBoxList').html(result);
					// Initialize JEditable
					$('.jeditrow1').editable('update-cuota_monto.php', { 
						indicator: 'Guardando...',
						tooltip: 'Click para editar...',
						width: '70',
						height: '10'
					});	
					$('.jeditrow2').editable('update-cuota_venc.php', { 
						indicator: 'Guardando...',
						tooltip: 'Click para editar...',
						width: '80',
						height: '10'
					});										
					$('.jeditrow3').editable('update-cuota_estado.php', { 
						indicator: 'Guardando...',
						tooltip: 'Click para editar...',
						type: 'select',
     					loadurl: 'get-json-cuota_estado.php',
						callback: function(value, settings) {
							populateDiv_Cuotas(id);
						}
					});					
				}
			}
		});						
	}	
	
	<!-- Insert via form functions -->
	insertFormUsuario = function(){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post				
		$.post("insert-usuario.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}				
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){
					// Clear form
					$('#frmBox').each(function(){
						this.reset();
					});
				});
			}
		});		
	}		
	insertFormSeguro = function(){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post				
		$.post("insert-seguro.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}				
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){
					// Clear form
					$('#frmBox').each(function(){
						this.reset();
					});
				});
			}
		});		
	}
	insertFormProd = function(){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post				
		$.post("insert-prod.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}				
				// Show message
				showBoxConf(data, false, 'onerror', 3000, function(){
					// Clear form
					$('#frmBox').each(function(){
						this.reset();
					});
				});
			}
		});		
	}
	insertFormProdSeg = function(id){	
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBox").serializeArray();	
		param.push({ name: "box-productor_id", value: id });		
		// Post				
		$.post("insert-prodseg.php", param, function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));						
				} else {
					// Clear form
					$('#box-productor_seguro_codigo').val('');					
					// Refresh DIVs
					populateDiv_ProdSeg(id);					
				}
				// Enable button
				$('#btnBox').button("option", "disabled", false);				
			}
		});
	}
	insertFormSuc = function(){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post				
		$.post("insert-suc.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}				
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){
					// Clear form
					$('#frmBox').each(function(){
						this.reset();
					});
				});
			}
		});		
	}
	insertFormCliente = function(){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post				
		$.post("insert-cliente.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}				
				// Show message
				showBoxConf(data, true, 'onerror', 3000, function(){
					// Clear form
					$('#frmBox').each(function(){
						this.reset();
					});
				});
			}
		});		
	}
	insertFormContacto = function(id){	
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBox").serializeArray();	
		param.push({ name: "box-cliente_id", value: id });		
		// Post				
		$.post("insert-contacto.php", param, function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));						
				} else {
					// Clear form
					$('#frmBox').each(function(){
						this.reset();
					});					
					// Refresh DIVs
					populateDiv_Contacto(id);					
				}
				// Enable button
				$('#btnBox').button("option", "disabled", false);				
			}
		});
	}
	insertFormPoliza = function(){	
		// Disable button
		$('#btnBox').button("option", "disabled", true);			
		// Post				
		$.post("insert-poliza.php", $("#frmBox").serializeArray(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					var id = parseInt(data);
					openBoxPolizaDet(id, true);
				} else {
					showBoxConf(data, true, 'always', 3000, function(){});
				}
			}
		});
	}							
	
	<!-- Update via form functions -->	
	updateFormUsuario = function(){
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-usuario.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){					
					// Clear password fields
					$("#box-usuario_clave").val('');
					$("#box-usuario_clave2").val('');
					// Repopulate form
					populateFormBoxUsuario($('#box-usuario_id').val());					
				});
			}
		});									
	}
	updateFormSeguro = function(){
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-seguro.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){					
					// Repopulate form
					populateFormBoxSeguro($('#box-seguro_id').val());					
				});
			}
		});									
	}
	updateFormProd = function(){
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-prod.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){					
					// Repopulate form
					populateFormBoxProd($('#box-productor_id').val());					
				});
			}
		});									
	}
	updateFormSuc = function(){
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-suc.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function(){					
					// Repopulate form
					populateFormBoxSuc($('#box-sucursal_id').val());					
				});
			}
		});									
	}
	updateFormCliente = function(){
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-cliente.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Show message
				showBoxConf(data, true, 'always', 3000, function(){					
					// Repopulate form
					populateFormBoxCliente($('#box-cliente_id').val());					
				});
			}
		});
	}
	updateFormPoliza = function(){
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-poliza.php", $("#frmBox").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Show message
				showBoxConf(data, true, 'always', 3000, function(){					
					// Repopulate form
					populateFormBoxPoliza($('#box-poliza_id').val());					
				});
			}
		});
	}
	
	<!-- Update via Link functions -->
	updateLinkContacto_Default = function(id, cliente_id){	
		if (confirm('Está seguro que desea establecer este contacto como primario?')) {
			$.post("update-contacto_default.php", {id: id}, function(data){
				if (data=='Session expired') {
					sessionExpire('main');
				} else {
					// Table standing redraw
					if (typeof oTable != 'undefined') {
						oTable.fnStandingRedraw();									
					}
					// Refresh DIV
					populateDiv_Contacto(cliente_id);					
					// Check for errors
					var ok = (data.toLowerCase().indexOf("error") == -1);
					// If error ocurred, alert
					if (!ok) {
						alert(data);
					}
				}
			});
		}
	}				
	updateLinkCuota_PFC = function(id, poliza_id){	
		$.post("update-cuota_pfc.php", {id: id}, function(data){
			if (data=='Session expired') {
				sessionExpire('main');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}
				// Refresh DIV
				populateDiv_Cuotas(poliza_id);
				// Check for errors
				var ok = (data.toLowerCase().indexOf("error") == -1);
				// If error ocurred, alert
				if (!ok) {
					alert(data);
				}
			}
		});
	}	
	
	<!-- Process via form functions -->	
	processFormPolizaDet = function(id, fromcreate){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBox").serializeArray();	
		param.push({ name: "box-poliza_id", value: id });		
		// Post				
		$.post("process-polizadet.php", param, function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}								
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					// If user comes from creation process
					if (fromcreate === true) {
						// Open next box
						openBoxPolizaCert(id);						
					} else {
						// Show message
						showBoxConf(data, true, 'always', 3000, function(){						
							// Populate form
							populateFormBoxPolizaDet(id);
							// Enable button
							$('#btnBox').button("option", "disabled", false);						
						});
					}					
				} else {
					// Show message
					showBoxConf(data, true, 'always', 3000, function(){
						// Enable button
						$('#btnBox').button("option", "disabled", false);						
					});					
				}				
			}
		});		
	}
	processFormPolizaRen = function(){													
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post				
		$.post("process-polizaren.php", $("#frmBox").serializeArray(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();									
				}								
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					// Open next box
					var newid = parseInt(data);					
					openBoxPolizaDet(newid, true);						
				} else {
					// Show message
					showBoxConf(data, true, 'always', 3000, function(){
						// Enable button
						$('#btnBox').button("option", "disabled", false);						
					});					
				}				
			}
		});		
	}			
	
	<!-- Delete via Link functions -->
	deleteProdSeg = function (id, productor_id) {		
		$.when(
			deleteViaLink('prodseg', id)
		).then(function(){
			populateDiv_ProdSeg(productor_id);	
		})
	}
	deleteContacto = function (id, cliente_id) {		
		$.when(
			deleteViaLink('contacto', id)
		).then(function(){
			populateDiv_Contacto(cliente_id);	
		})
	}			
	
	<!-- Box functions -->
	openBoxAltaUsuario = function () {
		$.colorbox({
			title:'Registro',
			href:'box-usuario_alta.php',												
			width:'700px',
			height:'450px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate selects, then initialize
				$.when(
					populateListUsuario_Acceso('box-usuario_acceso','box'),
					populateListUsuario_Sucursal('box-usuario_sucursal','box')
				).then(function(){	
								
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-usuario_nombre": {required: true},
							"box-usuario_email": {required: true, email: true},
							"box-usuario_usuario": {required: true, minlength: 6},
							"box-usuario_clave": {required: true, minlength: 8},
							"box-usuario_clave2": {required: true, equalTo: "#box-usuario_clave"},														
							"box-usuario_acceso": {required: true},
							"box-usuario_sucursal[]": {required: function() { return $("#box-usuario_acceso").val()=="administrativo"; } }
						}
					});		
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								insertFormUsuario();
							}
						};
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);	
					$("#box-usuario_sucursal").prop("disabled", true);
					$("#box-usuario_acceso").change(function() { 
						$("#box-usuario_sucursal").prop("disabled", !($("#box-usuario_acceso").val() == "administrativo")); 
					});				
				});
				
			}
		});		
	}		
	openBoxModUsuario = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-usuario_mod.php',												
			width:'700px',
			height:'500px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate form, then initialize
				$.when(populateFormBoxUsuario(id)).then(function(){	
																						
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-usuario_nombre": {required: true},
							"box-usuario_email": {required: true, email: true},
							"box-usuario_usuario": {required: true, minlength: 6},
							"box-usuario_clave": {minlength: 8},
							"box-usuario_clave2": {equalTo: "#box-usuario_clave"},														
							"box-usuario_acceso": {required: true},
							"box-usuario_sucursal[]": {required: function() { return $("#box-usuario_acceso").val()=="administrativo"; } }
						}
					});	
								
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormUsuario();
							}
						};
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);					
					$("#box-usuario_sucursal").prop("disabled", !($("#box-usuario_acceso").val() == "administrativo")); 
					$("#box-usuario_acceso").change(function() { 
						$("#box-usuario_sucursal").prop("disabled", !($("#box-usuario_acceso").val() == "administrativo")); 
					});				
				});		
				
			}
		});		
	}
	openBoxAltaSeguro = function () {
		$.colorbox({
			title:'Registro',
			href:'box-seguro_alta.php',												
			width:'700px',
			height:'450px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-seguro_nombre": {required: true},
						"box-seguro_email_siniestro": {email: true},
						"box-seguro_email_emision": {email: true}
					}
				});		
						
				// Button action	
				$("#btnBox").click(function() {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormSeguro();
						}
					};
				});	
				
				// Enable form							
				formDisable('frmBox','ui',false);					
				
			}
		});		
	}		
	openBoxModSeguro = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-seguro_mod.php',												
			width:'700px',
			height:'500px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate form, then initialize
				$.when(populateFormBoxSeguro(id)).then(function(){	
																						
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_nombre": {required: true},
							"box-seguro_email_siniestro": {email: true},
							"box-seguro_email_emision": {email: true}
						}
					});		
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormSeguro();
							}
						};
					});
					
					// Enable form							
					formDisable('frmBox','ui',false);						
								
				});		
				
			}
		});		
	}
	openBoxAltaProd = function () {
		$.colorbox({
			title:'Registro',
			href:'box-prod_alta.php',												
			width:'700px',
			height:'520px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);
				
				// Populate drop-downs, then initialize form
				$.when(
					populateListProductor_IVA('box-productor_iva', 'box')
				).then(function(){
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-productor_nombre": {required: true},
							"box-productor_iva": {required: true},
							"box-productor_cuit": {required: true},							
							"box-productor_matricula": {required: true},														
							"box-productor_email": {email: true}
						}
					});	
								
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								insertFormProd();
							}
						};
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);										
					
				});					
				
			}
		});		
	}
	openBoxModProd = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-prod_mod.php',												
			width:'700px',
			height:'520px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate form, then initialize
				$.when(populateFormBoxProd(id)).then(function(){
																								
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-productor_nombre": {required: true},
							"box-productor_iva": {required: true},
							"box-productor_cuit": {required: true},							
							"box-productor_matricula": {required: true},														
							"box-productor_email": {email: true}
						}
					});		
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormProd();
							}
						};
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);					
								
				});		
				
			}
		});		
	}
	openBoxProdSeg = function (id) {
		$.colorbox({
			title:'Productor/Seguros',
			href:'box-prodseg.php',												
			width:'700px',
			height:'600px',
			onComplete: function() {	
			
				// -------------------- GENERAL ---------------------				
							
				// Initialize buttons
				$("#btnBox").button();		
				
				// Disable forms
				formDisable('frmBox','ui',true);					
				
				// Populate DIVs
				populateDiv_Prod_Info(id);		
				populateDiv_ProdSeg(id);													
					
				// -------------------- FORM 1 ----------------------

				// Populate drop-downs, then initialize form
				$.when(
					populateListSeguro('box-seguro_id', 'box')
				).then(function(){							
								
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-productor_seguro_codigo": {required: true}
						}
					});																	
					
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							insertFormProdSeg(id);
						};
					});		
										
					// Enable form					
					formDisable('frmBox','ui',false);
					
				});
																						
			}
		});	
	}
	
	openBoxAltaSuc = function () {
		$.colorbox({
			title:'Registro',
			href:'box-suc_alta.php',												
			width:'700px',
			height:'450px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-sucursal_nombre": {required: true},
						"box-sucursal_email": {email: true}
					}
				});		
						
				// Button action	
				$("#btnBox").click(function() {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormSuc();
						}
					};
				});	
				
				// Enable form							
				formDisable('frmBox','ui',false);					
				
			}
		});		
	}		

	openBoxModSuc = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-suc_mod.php',												
			width:'700px',
			height:'500px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate form, then initialize
				$.when(populateFormBoxSuc(id)).then(function(){	
																						
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-sucursal_nombre": {required: true},
							"box-sucursal_email": {email: true}
						}
					});		
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormSuc();
							}
						};
					});
					
					// Enable form							
					formDisable('frmBox','ui',false);						
								
				});		
				
			}
		});		
	}

	
	openBoxAltaCliente = function () {
		$.colorbox({
			title:'Registro',
			href:'box-cliente_alta.php',												
			width:'700px',
			height:'600px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);
				
				// Populate drop-downs, then initialize form
				$.when(
					populateListCliente_Sexo('box-cliente_sexo', 'box'),
					populateListCliente_CF('box-cliente_cf', 'box'),
					populateListCliente_TipoDoc('box-cliente_tipo_doc', 'box'),
					populateListCliente_RegTipo('box-cliente_reg_tipo', 'box')
				).then(function(){
					
					// Init Datepickers
					$("#box-cliente_nacimiento").datepicker({
						dateFormat: 'yy-mm-dd',
						changeYear: true,									
						yearRange: "-100:+0",
						changeMonth: true										
					});
					$("#box-cliente_reg_vencimiento").datepicker({
						dateFormat: 'yy-mm-dd',
						changeYear: true,									
						yearRange: "c-10:c+10",
						changeMonth: true										
					});						

					// Set default values
					$("#box-cliente_sexo").val('M');
					$("#box-cliente_nacionalidad").val('Argentino');
					$("#box-cliente_cf").val('Consumidor Final');					
					$("#box-cliente_tipo_doc").val('DNI');
				    $("#box-cliente_reg_tipo").val('B1');
					
					// On Change: Input text
					$("#box-cliente_nro_doc").keyup(function(){
						$('#box-cliente_registro').val($(this).val());
					});	
										
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-cliente_nombre": {required: true},
							"box-cliente_nacimiento": {required: true, dateISO: true},
							"box-cliente_sexo": {required: true},
							"box-cliente_nacionalidad": {required: true},
							"box-cliente_cf": {required: true},
							"box-cliente_tipo_doc": {required: true},
							"box-cliente_nro_doc": {required: true},
							"box-cliente_reg_vencimiento": {dateISO: true},
							"box-cliente_email": {email: true}
						}
					});		
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								insertFormCliente();
							}
						};
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);										
					
				});					
				
			}
		});		
	}
	openBoxModCliente = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-cliente_mod.php',												
			width:'700px',
			height:'600px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate form, then initialize
				$.when(populateFormBoxCliente(id)).then(function(){
					
					// Init Datepickers
					$("#box-cliente_nacimiento").datepicker({
						dateFormat: 'yy-mm-dd',
						changeYear: true,									
						yearRange: "-100:+0",
						changeMonth: true										
					});
					$("#box-cliente_reg_vencimiento").datepicker({
						dateFormat: 'yy-mm-dd',
						changeYear: true,									
						yearRange: "c-10:c+10",
						changeMonth: true										
					});						

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-cliente_nombre": {required: true},
							"box-cliente_nacimiento": {required: true, dateISO: true},
							"box-cliente_sexo": {required: true},
							"box-cliente_nacionalidad": {required: true},
							"box-cliente_cf": {required: true},
							"box-cliente_tipo_doc": {required: true},
							"box-cliente_nro_doc": {required: true},
							"box-cliente_reg_vencimiento": {dateISO: true},
							"box-cliente_email": {email: true}
						}
					});
			
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							updateFormCliente();
						};
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);					
								
				});		
				
			}
		});		
	}
	openBoxContacto = function (id) {
		$.colorbox({
			title:'Cliente/Contactos',
			href:'box-contacto.php',												
			width:'950px',
			height:'100%',
			onComplete: function() {	
			
				// -------------------- GENERAL ---------------------				
							
				// Initialize buttons
				$("#btnBox").button();		
				
				// Disable forms
				formDisable('frmBox','ui',true);					
				
				// Populate DIVs
				populateDiv_Cliente_Info(id);			
				populateDiv_Contacto(id);													
					
				// -------------------- FORM 1 ----------------------

				// Populate drop-downs, then initialize form
				$.when(
					populateListContacto_Tipo('box-contacto_tipo', 'box')
				).then(function(){							
							
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-contacto_tipo": {required: true},
							"box-contacto_domicilio": {required: true},
							"box-contacto_nro": {required: true},
							"box-contacto_localidad": {required: true},
							"box-contacto_cp": {required: true}
						}
					});																	
					
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							insertFormContacto(id);
						};
					});		
										
					// Enable form					
					formDisable('frmBox','ui',false);
					
				});
																						
			}
		});	
	}
	openBoxPolizas = function (id) {
		$.colorbox({
			title:'Cliente/Pólizas',
			href:'box-cliepoli.php',												
			width:'700px',
			height:'600px',
			onComplete: function() {	
			
				// -------------------- GENERAL ---------------------				
							
				// Initialize buttons
				$("#btnBox").button();		
				
				// Disable forms
				formDisable('frmBox','ui',true);					
				
				// Populate DIVs
				populateDiv_Cliente_Info(id);
				populateDiv_Polizas(id);													
					
				// -------------------- FORM 1 ----------------------

				// Populate drop-downs, then initialize form
				$.when(
					populateListSeguro('box-seguro_id', 'box')
				).then(function(){							
								
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-productor_seguro_codigo": {required: true}
						}
					});																	
					
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							insertFormProdSeg(id);
						};
					});		
										
					// Enable form					
					formDisable('frmBox','ui',false);
					
				});
																						
			}
		});	
		
	}
	openBoxAltaPoliza = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-poliza_alta.php?section=1',												
			width:'700px',
			height:'100%',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable forms
				formDisable('frmSelectClient','normal',true);				
				formDisable('frmBox','ui',true);
			
				// FORM INSERT POLIZA
				// Populate drop-downs, then initialize
				$.when(
					populateListSuc('box-sucursal_id', 'box'),
					populateListSeguro('box-seguro_id', 'box'),
					populateListTipoPoliza('box-tipo_poliza_id', 'box'),
					populateListPoliza_Vigencia('box-poliza_vigencia', 'box'),
					populateListPoliza_Cuotas('box-poliza_cuotas', 'box'),
					populateListPoliza_MP('box-poliza_medio_pago', 'box')
				).then(function(){					
					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					$("#box-tipo_poliza_id").change(function(){
						$('#box-subtipo_poliza_id').html(loading);
						populateListSubtipoPoliza($(this).val(), 'box-subtipo_poliza_id', 'box');
					});	
					$("#box-seguro_id").change(function(){
						$('#box-productor_seguro_id').html(loading);						
						populateListProductorSeguro_Productor($(this).val(), 'box-productor_seguro_id', 'box');
					});	
					$("#box-poliza_vigencia").change(function(){
						var months;
						switch ($(this).val()) {
							case 'Anual':
								months = 12;
								break;
							case 'Semestral':
								months = 6;							
								break;
							case 'Bimestral':
								months = 2;															
								break;							
						}
						if ($(this).val() !== '') {
							if ($('#box-poliza_validez_desde').val() !== '') {
								var parsedate = Date.parse($('#box-poliza_validez_desde').val());
								if (parsedate !== null) {
									$('#box-poliza_validez_hasta').val(parsedate.clearTime().add(months).months().toString("yyyy-MM-dd"));
								}
							}
						} else {
							$('#box-poliza_validez_hasta').val('');							
						}
					});
					$("#box-poliza_cuotas").change(function(){
						var cuotas = '';
						switch ($('#box-poliza_cuotas').val()) {
							case 'Total':
								cuotas = 1;
								break;							
							case 'Mensual':
								switch ($('#box-poliza_vigencia').val()) {
									case 'Anual':
										cuotas = 12;
										break;
									case 'Semestral':
										cuotas = 6;							
										break;
									case 'Bimestral':
										cuotas = 2;															
										break;							
								}
								break;
						}
						$('#box-poliza_cant_cuotas').val(cuotas);							
					});																
					// Set default values
					$('#box-poliza_validez_desde').val(Date.today().clearTime().toString("yyyy-MM-dd"));
					$('#box-poliza_medio_pago').val('Directo');				
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-tipo_poliza_id": {required: true},
							"box-sucursal_id": {required: true},
							"box-subtipo_poliza_id": {required: true},
							"box-seguro_id": {required: true},
							"box-productor_seguro_id": {required: true},
							"box-poliza_vigencia": {required: true},
							"box-poliza_validez_desde": {required: true, dateISO: true},
							"box-poliza_validez_hasta": {required: true, dateISO: true, enddate: "#box-poliza_validez_desde"},
							"box-poliza_cuotas": {required: true},
							"box-poliza_cant_cuotas": {required: true, digits: true, min:1, max:255},
							"box-poliza_fecha_solicitud": {dateISO: true},
							"box-poliza_fecha_emision": {dateISO: true},
							"box-poliza_fecha_recepcion": {dateISO: true},
							"box-poliza_fecha_entrega": {dateISO: true},
							"box-poliza_prima": {min:0, max: 99999999.99},
							"box-poliza_premio": {required: true, min:0, max: 99999999.99},
							"box-poliza_medio_pago": {required: true},
							"box-poliza_recargo": {min:0, max:100}
						},
						errorPlacement: function(error, element) {
							error.insertAfter(element.parent("p").children().last());
						}						
					});												
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?\n\nEsta acción no puede deshacerse.')) {												
								insertFormPoliza(id);
							}
						}
					});
				});
				
				// FORM SELECT CLIENT
				// Initialize special fields
				initAutocompleteCliente('box0-cliente_nombre', 'box');
				// Assign functions to buttons
				$("#BtnSearchCliente").click(function() {
					// If a field was completed
					if ($('#box0-cliente_nombre').val() != '' || $('#box0-cliente_nro_doc').val() != '') {
						populateDiv_Cliente_Results();
					} else {
						$('#divBoxClienteSearchResults').html('Debe ingresar información en al menos un campo.');
					}
				});
				// Submit on Enter
				$("#frmSelectClient :input[type=text]").each(function() {
					$(this).keypress(function(e) {
						if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
							$("#BtnSearchCliente").click();
						}
					});
				});													
				// Enable form					
				formDisable('frmSelectClient','normal',false);
				// Set focus on search
				$("#box0-cliente_nombre").focus();						
				
			}
		});		
	}
	openBoxModPoliza = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-poliza_mod.php?section=1',												
			width:'700px',
			height:'100%',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);

				// Populate form, then initialize
				$.when(populateFormBoxPoliza(id)).then(function(){
					
					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';	
					$("#box-seguro_id").change(function(){
						$('#box-productor_seguro_id').html(loading);						
						populateListProductorSeguro_Productor($(this).val(), 'box-productor_seguro_id', 'box');
					});		
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-productor_seguro_id": {required: true},
							"box-poliza_fecha_solicitud": {date: true},
							"box-poliza_fecha_emision": {date: true},
							"box-poliza_fecha_recepcion": {date: true},
							"box-poliza_fecha_entrega": {date: true},
							"box-poliza_prima": {min:0, max: 99999999.99},
							"box-poliza_medio_pago": {required: true},
							"box-poliza_recargo": {min:0, max:100}							
						},
						errorPlacement: function(error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});	
			
					// Button action	
					$("#btnBox").click(function() {
						$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
						if (validateForm.form()) {
							updateFormPoliza();
						}
						else {
							$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
						}
					});	
					
					// Enable form							
					formDisable('frmBox','ui',false);					
								
				});			
				
			}
		});		
	}
	openBoxPolizaRen = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-poliza_ren.php?section=1&ren=1',												
			width:'700px',
			height:'100%',
			onComplete: function() {
				
				// Initialize buttons
				$("#btnBox").button();
				
				// Disable form
				formDisable('frmBox','ui',true);
								
				// Populate form, then initialize
				$.when(populateFormBoxPolizaRen(id)).then(function(){
					
					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					$("#box-seguro_id").change(function(){
						$('#box-productor_seguro_id').html(loading);						
						populateListProductorSeguro_Productor($(this).val(), 'box-productor_seguro_id', 'box');
					});	
					$("#box-poliza_vigencia").change(function(){
						var months;
						switch ($(this).val()) {
							case 'Anual':
								months = 12;
								break;
							case 'Semestral':
								months = 6;							
								break;
							case 'Bimestral':
								months = 2;															
								break;							
						}
						if ($(this).val() !== '') {
							if ($('#box-poliza_validez_desde').val() !== '') {
								var parsedate = Date.parse($('#box-poliza_validez_desde').val());
								if (parsedate !== null) {
									$('#box-poliza_validez_hasta').val(parsedate.clearTime().add(months).months().toString("yyyy-MM-dd"));
								}
							}
						} else {
							$('#box-poliza_validez_hasta').val('');							
						}
					});
					$("#box-poliza_cuotas").change(function(){
						var cuotas = '';
						switch ($('#box-poliza_cuotas').val()) {
							case 'Total':
								cuotas = 1;
								break;							
							case 'Mensual':
								switch ($('#box-poliza_vigencia').val()) {
									case 'Anual':
										cuotas = 12;
										break;
									case 'Semestral':
										cuotas = 6;							
										break;
									case 'Bimestral':
										cuotas = 2;															
										break;							
								}
								break;
						}
						$('#box-poliza_cant_cuotas').val(cuotas);							
					});																
					// Set default values
					$('#box-poliza_validez_desde').val(Date.today().clearTime().toString("yyyy-MM-dd"));				
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-productor_seguro_id": {required: true},
							"box-poliza_vigencia": {required: true},
							"box-poliza_validez_desde": {required: true, dateISO: true},
							"box-poliza_validez_hasta": {required: true, dateISO: true, enddate: "#box-poliza_validez_desde"},
							"box-poliza_cuotas": {required: true},
							"box-poliza_cant_cuotas": {required: true, digits: true, min:1, max:255},
							"box-poliza_fecha_solicitud": {dateISO: true},
							"box-poliza_fecha_emision": {dateISO: true},
							"box-poliza_fecha_recepcion": {dateISO: true},
							"box-poliza_fecha_entrega": {dateISO: true},
							"box-poliza_prima": {min:0, max: 99999999.99},
							"box-poliza_premio": {required: true, min:0, max: 99999999.99},
							"box-poliza_medio_pago": {required: true},
							"box-poliza_recargo": {min:0, max:100}							
						},
						errorPlacement: function(error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});												
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?\n\nEsta acción no puede deshacerse.')) {												
								processFormPolizaRen(id);
							}
						}
					});				
					
					// Enable form							
					formDisable('frmBox','ui',false);										
								
				});
				
			}
		});		
	}		
	openBoxPolizaDet = function (id, fromcreate) {
		$.colorbox({
			title:'Registro',
			href:'box-polizadet.php?section=2&id='+id,												
			width:'700px',
			height:'100%',
			onComplete: function() {
				
				// Set button text
				if (fromcreate === true) {
					$("#btnBox").val('Siguiente');
				} else {
					$("#btnBox").val('Aceptar');
				}
			
				// Initialize buttons
				$("#btnBox").button();
				
				// Populate DIVs
				populateDiv_Poliza_Fotos(id);
				
				// AJAX file form
				$("#fileForm").ajaxForm({
					beforeSend: function() {
				    	$("#fotosEstado").html("Subiendo...").show();
					},
					complete: function(xhr) {
						if (xhr.responseText.indexOf('Error:')!=-1) {
							alert(xhr.responseText);
						}
						else {
							$("#fotosEstado").html("").hide();
						}
						populateDiv_Poliza_Fotos(id);
					}
				});
				
				// Disable form
				formDisable('frmBox','ui',true);
								
				// Populate form, then initialize
				$.when(populateFormBoxPolizaDet(id)).then(function(){				
				
					// Validate form
					var validateForm = $("#frmBox").validate();				
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {					
							processFormPolizaDet(id, fromcreate);
						}
					});	
					
					// Enable form					
					formDisable('frmBox','ui',false);				
				
				});
				
			}
		});		
	}
	openBoxPolizaCert = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-polizacert.php?section=3&id='+id,
			width:'700px',
			height:'300px',
			onComplete: function() {			
			
				// Initialize buttons
				$("#btnCCp").button();
				$("#btnCCd").button();
				$("#btnPE").button();
				$("#btnPEMC").button();
				
				// Button action	
				$("#btnCCp").click(function() {
					window.open('print-poliza.php?type=cc&id='+id+'&print');
				});
				$("#btnCCd").click(function() {
					window.open('print-poliza.php?type=cc&id='+id);
				});
				$("#btnPE").click(function() {
					window.open('print-poliza.php?type=pe&mc=0&id='+id);
				});
				$("#btnPEMC").click(function() {
					window.open('print-poliza.php?type=pe&mc=1&id='+id);
				});								
				
			}
		});		
	}		
	openBoxCuota = function (id) {
		$.colorbox({
			title:'Registro',
			href:'box-cuota.php',
			width:'900px',
			height:'100%',
			onComplete: function() {
							
				// Populate DIVs
				populateDiv_Poliza_Info(id);
				populateDiv_Cuotas(id);								
								
			}
		});		
	}	

});