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
	formatNumber = function(x) {
	    return x.toString().replace(/\./g, ",").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
	years = function(startYear, futureYears) {
		futureYears = futureYears || 0;
		var currentYear = new Date().getFullYear() + futureYears, years = [];
		startYear = startYear || 1980;

		while ( startYear <= currentYear ) {
			years.push(startYear++);
		} 

		return years;
	}
		
	<!-- Custom date validation -->
	$.validator.addMethod("dateAR", function(value, element) {
		return value=='' || value.match(/^\d\d\/\d\d\/\d\d(\d\d)?$/);
	},'Por favor ingresar una fecha en formato dd/mm/aa.');
	
	customValidations = function() {
		var objects = [
			{
				'name': 'tv_aud_vid_total', 
				'desc': 'Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto',
				'min': 100, 
				'max': 30000
			},
			{
				'name': 'obj_esp_prorrata_total', 
				'desc': 'Robo y/o Hurto de Objetos Específicos y/o Aparatos Electrodomésticos a Prorrata',
				'min': 100, 
				'max': 20000
			},
			{
				'name': 'equipos_computacion_total', 
				'desc': 'Todo Riesgo Equipos de Computación en Domicilio a Primer Riesgo Absoluto',
				'min': 1500, 
				'max': 10000
			},
			{
				'name': 'film_foto_total', 
				'desc': 'Robo de Filmadoras y/o Cam. Fotográficas a Prorrata',
				'min': 500, 
				'max': 5000
			},
		];
	
		for (var i = 0; i < objects.length;i++) {
			var obj = objects[i];
			var value = Number($('#'+obj.name).text());
			if (value != 0 && (value < obj.min || value > obj.max)) {
				alert('El valor total asegurado de '+obj.desc+' debe ser mayor a '+obj.min+' y menor a '+obj.max);
				return false;
			}
		}
		return true;
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
	initAutocompletePoliza = function (field, context) {
		$("#"+field).autocomplete({
			source: "get-json-poliza_numero.php",
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
		$("#"+form+" input[type='number']").attr("disabled", disabled);
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
	populateListUsuario_Sucursal = function(field, context, all){
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
					if (all) appendListItem(field, '', 'Todos');
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
	populateListTipoPoliza = function(field, context, include){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-tipopoliza.php?include="+include,
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
	populateListPoliza_Vigencia = function(field, context, tipo_poliza){
		var vigencia;
		var options = '<option value=\'\'>Todos</option>';
		switch (tipo_poliza) {
			case '3':
			case 'Personas':
				vigencia = ['Mensual','Bimestral','Trimestral','Cuatrimestral','Semestral','Anual','Otra'];
				break;
			case undefined:
				vigencia = [];
				break;
			default:
				vigencia = ['Bimestral','Semestral','Anual'];
				break;
		}
		for (var i = 0; i < vigencia.length; i++) {
			options += '<option value="' + vigencia[i] + '">' + vigencia[i] + '</option>';
		}
		$('#'+field).html(options);
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
	populateListProductorSeguro_Productor = function(seguro_id, sucursal_id, field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-productorseguro_productor.php?id="+seguro_id+'&id2='+sucursal_id,
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
	populateListAseguradoActividad = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-asegurado_actividad.php",
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
	populateListPlizaEstado = function(field, context){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-poliza_estado.php",
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
	populateListEndosoTipo = function(field, context) {
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-endoso_tipo.php",
			dataType: 'json',
			success: function (j) {
				if(j.error == 'expired'){
					sessionExpire(context);
				} else {				
					var options = ''; 
					$.each(j, function(key0, value0) { 
						options += '<optgroup label="'+key0+'">';
						$.each(value0, function(key, value) {
							options += '<option value="' + key + '">' + value + '</option>';
						})
						options += '</optgroup>';
					});		
					$('#'+field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Seleccionar');
					// Select first item
					selectFirstItem(field);	
					dfd.resolve();								
				}
			}
		});			
		return dfd.promise();	
	}
	populateListCoberturaTipo = function(field, context) {
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-cobertura_tipo.php",
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
					case 'radio':
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
						populateListProductorSeguro_Productor(j.seguro_id, j.sucursal_id, 'box-productor_seguro_id', 'box'),
						populateListPoliza_MP('box-poliza_medio_pago', 'box')
					).then(function(){	
						// Populate Form
						populateFormGeneric(j, "box");
						$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');						
						// Si la poliza no es Automotor, ocultar campo ajuste
						if (j.subtipo_poliza_nombre.toUpperCase() != 'AUTOMOTOR') {
							$('#box-poliza_ajuste').parent().hide();
						}
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
						populateListProductorSeguro_Productor(j.seguro_id, j.sucursal_id, 'box-productor_seguro_id', 'box'),
						populateListPoliza_Vigencia('box-poliza_vigencia', 'box'),
						populateListPoliza_Cuotas('box-poliza_cuotas', 'box'),						
						populateListPoliza_MP('box-poliza_medio_pago', 'box')
					).then(function(){	
						// Populate Form
						populateFormGeneric(j, "box");																
						// Si la poliza no es Automotor, ocultar campo ajuste
						if (j.subtipo_poliza_nombre.toUpperCase() != 'AUTOMOTOR') {
							$('#box-poliza_ajuste').parent().hide();
						}
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
					populatePolizaDet(j.subtipo_poliza, id);
					// Resolve
					dfd.resolve();														
				}
			}
		});	
		return dfd.promise();				
	}
	populateFormBoxEndoso = function(id) {
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_endoso.php?id="+id,
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
						populateListEndosoTipo('box-endoso_tipo_id', 'box')
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
	
	populatePolizaDet = function(subtipo_poliza, id) {
		switch (subtipo_poliza) {
			case 'automotor':
				// Populate DIVs
				populateDiv_Fotos('poliza', id);
				populateDiv_Fotos('automotor_micrograbado', id, 'Micrograbado');
				populateDiv_Fotos('automotor_gnc', id, 'GNC');
				
				// AJAX file form
				$("#fileForm").ajaxForm({
					beforeSend: function() {
				    	$("#fotosLoading").show();
					},
					uploadProgress: function(event, position, total, percentComplete) {
				       
					},
					complete: function(xhr) {
						if (xhr.responseText.indexOf('Error:')!=-1) {
							alert(xhr.responseText);
						}
						else {
							$("#fotosLoading").hide();
						}
						populateDiv_Fotos('poliza', id);
					}
				});
				break;
			case 'accidentes':
				// Agregar asegurado
				$(".box-date").datepicker({
						dateFormat: 'dd/mm/yy',
						changeYear: true,									
						yearRange: "-100:+0",
						changeMonth: true										
				});
				populateSectionAsegurado(id);
				populateSectionClausula(id);
				break;
			case 'combinado_familiar':
				$('fieldset.optional').each(function(i,e) {
					$(e).prop('disabled', !$(e).children().first().children().first().prop('checked'));
				});
				$('.toggle-fieldset').change(function() {
					$(this).parent().parent().prop('disabled', !$(this).prop('checked')).children('p').first().children().eq(1).focus();
				})
				populateSectionTvAudVid(id);
				populateSectionObjEspProrrata(id);
				populateSectionEquiposComputacion(id);
				populateSectionFilmFoto(id);
				
				$('#frmBox').submit(function() {
					var objects = [
						{'name': 'tv_aud_vid', 'min': 100, 'max': 30000},
						{'name': 'obj_esp_prorrata', 'min': 100, 'max': 20000},
						{'name': 'equipos_computacion', 'min': 1500, 'max': 10000},
						{'name': 'film_foto_total', 'min': 500, 'max': 5000},
					]
					
					$.each(objects, function(i,obj) {
						var value = Number($('#'+obj.name+'_total').text());
						if (value < obj.min || value > obj.max) {
							alert('aa');
							return false;
						}
						return true;
					})
					
				})
				
				$("#box-combinado_familiar_domicilio_calle").focus();	
				break;
		}
	}
	populateSectionAsegurado = function(id) {
		populateListAseguradoActividad('box-accidentes_asegurado_actividad');
		populateDiv_Asegurado(id);
		
		$("#box-accidentes_asegurado_suma_asegurada, #box-accidentes_asegurado_gastos_medicos").change(function() {
			var suma_asegurada = isNaN($("#box-accidentes_asegurado_suma_asegurada").val())?0:$("#box-accidentes_asegurado_suma_asegurada").val();
			if ($(this).prop('id')=='box-accidentes_asegurado_suma_asegurada' && $("#box-accidentes_asegurado_gastos_medicos").val() == '') {
				$("#box-accidentes_asegurado_gastos_medicos").val(Number(suma_asegurada) * 0.1);
			}
			var gastos_medicos = isNaN($("#box-accidentes_asegurado_gastos_medicos").val())?0:$("#box-accidentes_asegurado_gastos_medicos").val();
			$("#box-accidentes_asegurado_total").val(Number(suma_asegurada) + Number(gastos_medicos));
		});
		$("#box-accidentes_asegurado_beneficiario_cargar").change(function() {
			$("#box-accidentes_asegurado_beneficiario_nombre, #box-accidentes_asegurado_beneficiario_documento, #box-accidentes_asegurado_beneficiario_nacimiento").prop('disabled', !($(this).prop('checked')));
			$("#box-accidentes_asegurado_beneficiario_nombre").focus();
		});
		$("#btnBoxAsegurado, #btnBoxAseguradoReset").button();
		var validateForm = $("#frmBoxAsegurado").validate({
			rules: {							
				"box-accidentes_asegurado_nombre": {required: true},
				"box-accidentes_asegurado_documento": {required: true},
				"box-accidentes_asegurado_nacimiento": {required: true, dateAR: true},
				"box-accidentes_asegurado_actividad": {required: true},
				"box-accidentes_asegurado_suma_asegurada": {required: true, number: true},
				"box-accidentes_asegurado_gastos_medicos": {required: true, number: true},
				"box-accidentes_asegurado_beneficiario": {required: true},
				"box-accidentes_asegurado_beneficiario_nombre": {required: function() {return $("#box-accidentes_asegurado_beneficiario_cargar").prop('checked')}},
				"box-accidentes_asegurado_beneficiario_documento": {required: function() {return $("#box-accidentes_asegurado_beneficiario_cargar").prop('checked')}},
				"box-accidentes_asegurado_beneficiario_nacimiento": {required: function() {return $("#box-accidentes_asegurado_beneficiario_cargar").prop('checked')}, dateAR: true},
				
			}
		});		
		$("#btnBoxAsegurado").click(function() {
			if (validateForm.form()) {
				$('#frmBoxAsegurado .box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
				if ($("#box-action").val() == 'insert') {
					insertFormAsegurado(id);
				}
				else {
					updateFormAsegurado(id);
				}
			};
			$('#frmBoxAsegurado .box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
		});	
	}
	populateSectionClausula = function(id) {
		populateDiv_Clausula(id);
		
		$("#btnBoxClausula, #btnBoxClausulaReset").button();
		var validateForm = $("#frmBoxClausula").validate({
			rules: {							
				"box-accidentes_clausula_nombre": {required: true},
				"box-accidentes_clausula_cuit": {required: true},
				"box-accidentes_clausula_domicilio": {required: true}
			}
		});		
		$("#btnBoxClausula").click(function() {
			if (validateForm.form()) {
				if ($("#box-action").val() == 'insert') {
					insertFormClausula(id);
				}
				else {
					updateFormClausula(id);
				}
			};
		});	
	}
	populateSectionTvAudVid = function(id) {
		populateDiv_TvAudVid(id);

		calculateTvAudVidTotal();		
		$('#box-combinado_familiar_tv_aud_vid_add').button().click(function() {
			addTvAudVidItem();
		})
	}	
	populateSectionObjEspProrrata = function(id) {
		populateDiv_ObjEspProrrata(id);

		calculateObjEspProrrataTotal();		
		$('#box-combinado_familiar_obj_esp_prorrata_add').button().click(function() {
			addObjEspProrrataItem();
		})
	}
	populateSectionEquiposComputacion = function(id) {
		populateDiv_EquiposComputacion(id);

		calculateEquiposComputacionTotal();		
		$('#box-combinado_familiar_equipos_computacion_add').button().click(function() {
			addEquiposComputacionItem();
		})
	}
	populateSectionFilmFoto = function(id) {
		populateDiv_FilmFoto(id);

		calculateFilmFotoTotal();		
		$('#box-combinado_familiar_film_foto_add').button().click(function() {
			addFilmFotoItem();
		})
	}
	
	addTvAudVidItem = function(cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#tv_aud_vid p').each(function(i,e){
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var tv_aud_vid = '<p id="'+j+'"><input type="number" name="box-combinado_familiar_tv_aud_vid['+j+'][cantidad]" class="box-combinado_familiar_tv_aud_vid_cant" placeholder="Cant" style="width:40px" value="'+(cantidad?cantidad:'')+'" /> <input type="text" name="box-combinado_familiar_tv_aud_vid['+j+'][producto]" placeholder="Producto" value="'+(producto?producto:'')+'" /> <input type="text" name="box-combinado_familiar_tv_aud_vid['+j+'][marca]" placeholder="Marca" value="'+(marca?marca:'')+'" /> <input type="text" name="box-combinado_familiar_tv_aud_vid['+j+'][serial]" placeholder="Nro Serie" value="'+(serial?serial:'')+'" /> <input type="number" name="box-combinado_familiar_tv_aud_vid['+j+'][valor]" class="box-combinado_familiar_tv_aud_vid_valor" placeholder="Valor" style="width:80px" value="'+(valor?valor:'')+'" /> <input type="button" class="box-combinado_familiar_tv_aud_vid_remove" value="-" /></p>';
		$('#tv_aud_vid').append(tv_aud_vid);
		$('#tv_aud_vid p#'+j+' :nth-child(1)').focus();
		$('.box-combinado_familiar_tv_aud_vid_remove').button().click(function() {
			$(this).parent().remove();
			calculateTvAudVidTotal();
		})
		$('.box-combinado_familiar_tv_aud_vid_valor, .box-combinado_familiar_tv_aud_vid_cant').change(function() {calculateTvAudVidTotal()});
	}
	addObjEspProrrataItem = function(cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#obj_esp_prorrata p').each(function(i,e){
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var obj_esp_prorrata = '<p id="'+j+'"><input type="number" name="box-combinado_familiar_obj_esp_prorrata['+j+'][cantidad]" class="combinado_familiar_obj_esp_prorrata_cant" placeholder="Cant" style="width:40px" value="'+(cantidad?cantidad:'')+'" /> <input type="text" name="box-combinado_familiar_obj_esp_prorrata['+j+'][producto]" placeholder="Producto" value="'+(producto?producto:'')+'" /> <input type="text" name="box-combinado_familiar_obj_esp_prorrata['+j+'][marca]" placeholder="Marca" value="'+(marca?marca:'')+'" /> <input type="text" name="box-combinado_familiar_obj_esp_prorrata['+j+'][serial]" placeholder="Nro Serie" value="'+(serial?serial:'')+'" /> <input type="number" name="box-combinado_familiar_obj_esp_prorrata['+j+'][valor]" class="box-combinado_familiar_obj_esp_prorrata_valor" placeholder="Valor" style="width:80px" value="'+(valor?valor:'')+'" /> <input type="button" class="box-combinado_familiar_obj_esp_prorrata_remove" value="-" /></p>';
		$('#obj_esp_prorrata').append(obj_esp_prorrata);
		$('#obj_esp_prorrata p#'+j+' :nth-child(1)').focus();
		$('.box-combinado_familiar_obj_esp_prorrata_remove').button().click(function() {
			$(this).parent().remove();
			calculateObjEspProrrataTotal()
		})
		$('.box-combinado_familiar_obj_esp_prorrata_valor, .combinado_familiar_obj_esp_prorrata_cant').change(function() {calculateObjEspProrrataTotal()});
	}
	addEquiposComputacionItem = function(cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#equipos_computacion p').each(function(i,e){
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var equipos_computacion = '<p id="'+j+'"><input type="number" name="box-combinado_familiar_equipos_computacion['+j+'][cantidad]" class="box-combinado_familiar_equipos_computacion_cant" placeholder="Cant" style="width:40px" value="'+(cantidad?cantidad:'')+'" /> <input type="text" name="box-combinado_familiar_equipos_computacion['+j+'][producto]" placeholder="Producto" value="'+(producto?producto:'')+'" /> <input type="text" name="box-combinado_familiar_equipos_computacion['+j+'][marca]" placeholder="Marca" value="'+(marca?marca:'')+'" /> <input type="text" name="box-combinado_familiar_equipos_computacion['+j+'][serial]" placeholder="Nro Serie" value="'+(serial?serial:'')+'" /> <input type="number" name="box-combinado_familiar_equipos_computacion['+j+'][valor]" class="box-combinado_familiar_equipos_computacion_valor" placeholder="Valor" style="width:80px" value="'+(valor?valor:'')+'" /> <input type="button" class="box-combinado_familiar_equipos_computacion_remove" value="-" /></p>';
		$('#equipos_computacion').append(equipos_computacion);
		$('#equipos_computacion p#'+j+' :nth-child(1)').focus();
		$('.box-combinado_familiar_equipos_computacion_remove').button().click(function() {
			$(this).parent().remove();
			calculateEquiposComputacionTotal()
		})
		$('.box-combinado_familiar_equipos_computacion_valor, .box-combinado_familiar_equipos_computacion_cant').change(function() {calculateEquiposComputacionTotal()});
	}
	addFilmFotoItem = function(cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#film_foto p').each(function(i,e){
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var film_foto = '<p id="'+j+'"><input type="number" name="box-combinado_familiar_film_foto['+j+'][cantidad]" class="box-combinado_familiar_film_foto_cant" placeholder="Cant" style="width:40px" value="'+(cantidad?cantidad:'')+'" /> <input type="text" name="box-combinado_familiar_film_foto['+j+'][producto]" placeholder="Producto" value="'+(producto?producto:'')+'" /> <input type="text" name="box-combinado_familiar_film_foto['+j+'][marca]" placeholder="Marca" value="'+(marca?marca:'')+'" /> <input type="text" name="box-combinado_familiar_film_foto['+j+'][serial]" placeholder="Nro Serie" value="'+(serial?serial:'')+'" /> <input type="number" name="box-combinado_familiar_film_foto['+j+'][valor]" class="box-combinado_familiar_film_foto_valor" placeholder="Valor" style="width:80px" value="'+(valor?valor:'')+'" /> <input type="button" class="box-combinado_familiar_film_foto_remove" value="-" /></p>';
		$('#film_foto').append(film_foto);
		$('#film_foto p#'+j+' :nth-child(1)').focus();
		$('.box-combinado_familiar_film_foto_remove').button().click(function() {
			$(this).parent().remove();
			calculateFilmFotoTotal()
		})
		$('.box-combinado_familiar_film_foto_valor, .box-combinado_familiar_film_foto_cant').change(function() {calculateFilmFotoTotal()});
	}
	calculateTvAudVidTotal = function() {
		var total = 0;
		$('.box-combinado_familiar_tv_aud_vid_valor').each(function(i,e){
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#tv_aud_vid_total").html(total);
	}
	calculateObjEspProrrataTotal = function() {
		var total = 0;
		$('.box-combinado_familiar_obj_esp_prorrata_valor').each(function(i,e){
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#obj_esp_prorrata_total").html(total);
	}
	calculateEquiposComputacionTotal = function() {
		var total = 0;
		$('.box-combinado_familiar_equipos_computacion_valor').each(function(i,e){
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#equipos_computacion_total").html(total);
	}
	calculateFilmFotoTotal = function(id) {
		var total = 0;
		$('.box-combinado_familiar_film_foto_valor').each(function(i,e){
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#film_foto_total").html(total);
	}
	populateFormBoxAsegurado = function(id){
		var dfd = new $.Deferred();		
		$.ajax({
			url: "get-json-fich_accidentes_asegurado.php?id="+id,
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
	assignPolizaToEndoso = function(id, poliza_numero){
		$("#box-poliza_numero").val(poliza_numero);
		$("#box-poliza_id").val(id);
		
		// Clear search form
		$('#frmSelectPoliza').each(function(){
			this.reset();
		});						
		// Clear search results
		$('#divBoxPolizaSearchResults').html('');						
		// Enable main form
		formDisable('frmBox','ui',false);
		// Set focus
		$("#box-endoso_tipo").focus();		
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
					result += '<th width="30%">Seguro</th>';
					result += '<th width="30%">Sucursal</th>';
					result += '<th width="30%">Código</th>';
					result += '<th width="10%">Acciones</th>';					
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td>'+object.seguro_nombre+'</td>';
						result += '<td>'+object.sucursal_nombre+'</td>';
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
					result += '<th>Country</th>';
					result += '<th>Lote</th>';
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
						result += '<td>'+object.contacto_country+'</td>';
						result += '<td>'+object.contacto_lote+'</td>';
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
	populateDiv_Fotos = function(section, id, divsuffix){
		divsuffix = divsuffix || '';
		$.getJSON("get-json-"+section+"_fotos.php?id="+id, {}, function(j){
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
						
						
						result += '<td align="center" class="ui-state-default ui-corner-all" style="width:100px;height:115px;overflow: hidden;white-space: nowrap"><a href="'+object.foto_url+'" target="_blank"><img width="100" height="100" style="vertical-align:middle;" src="' + object.foto_thumb_url + '" /></a>';	
						result += '<br />';
						result += '<span style="float:right"><ul class="dtInlineIconList ui-widget ui-helper-clearfix"><li title="Abrir en nueva ventana" onclick="window.open(\''+object.foto_url+'\');"><span class="ui-icon ui-icon-newwin"></span></li><li title="Eliminar" onclick="deleteViaLink(\''+section+'_foto\', \''+object.foto_id+'\');$(\'#divShowFoto'+divsuffix+'\').hide();populateDiv_Fotos(\''+section+'\', '+id+', \''+divsuffix+'\');"><span class="ui-icon ui-icon-trash"></span></li></ul></span>';				
						result += '</td>';
					});
					// Close Table									
					result += '</tr></tbody></table>';
					// Populate DIV					
					$('#divBoxFotos'+divsuffix).html(result);
					if (j != '') {
						$('#divBoxFotos'+divsuffix).show();
					}
					else {
						$('#divBoxFotos'+divsuffix).hide();
					}
				}
			}
		});							
	}
	populateDiv_Asegurado = function(id) {
		$.getJSON("get-json-fich_accidentes_asegurados.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				var result = '';			
				// Check if empty
				if (j.length>0) {
					// Sort data
					j.sort(function (a, b) {
						return 1;
					});					
					var totalsuma = 0;
					var totalmed = 0;
					var count = 0;
					// Open Table
					result += '<table class="tblBox">';			
					// Table Head
					result += '<tr>';
					result += '<th>Nombre</th>';
					result += '<th>DNI</th>';
					result += '<th>Actividad</th>';
					result += '<th>Legal</th>';
					result += '<th>Asegurado</th>';
					result += '<th>Gastos Farm.</th>';
					result += '<th>Acción</th>';																				
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						totalsuma += Number(object.accidentes_asegurado_suma_asegurada);	
						totalmed += Number(object.accidentes_asegurado_gastos_medicos);	
						count++;	
						result += '<tr>';
						result += '<td>'+object.accidentes_asegurado_nombre+'</td>';
						result += '<td>'+object.accidentes_asegurado_documento+'</td>';
						result += '<td>'+object.asegurado_actividad_nombre.substr(0, 15)+(object.asegurado_actividad_nombre.length>15?'...':'')+'</td>';
						result += '<td>'+object.accidentes_asegurado_legal+'</td>';
						result += '<td>'+formatNumber(object.accidentes_asegurado_suma_asegurada)+'</td>';
						result += '<td>'+formatNumber(object.accidentes_asegurado_gastos_medicos)+'</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Editar Asegurado" onClick="javascript:editInBoxAsegurado('+object.accidentes_asegurado_id+');"><span class="ui-icon ui-icon-search"></span></li>';
						result += '<li title="Eliminar Asegurado" onClick="javascript:deleteAccidentesAsegurado('+object.accidentes_asegurado_id+', '+id+');"><span class="ui-icon ui-icon-trash"></span></li>';
						result += '</ul></td>';						
						result += '</tr>';		
					});
					result += '<tr><td><strong>Total ('+count+')</strong></td><td></td><td></td><td></td><td><strong>'+formatNumber(Number(totalsuma).toFixed(2))+'</strong></td><td><strong>'+formatNumber(Number(totalmed).toFixed(2))+'</strong></td><td></td></tr>';
					// Close Table
					result += '</table>';
				} else {
					result += 'La póliza no contiene datos de asegurados.';
				}
				// Populate DIV					
				$('#divBoxListAsegurado').html(result);								
			}
		});				
	}
	populateDiv_Clausula = function(id) {
		$.getJSON("get-json-fich_accidentes_clausulas.php?id="+id, {}, function(j){
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				var result = '';			
				// Check if empty
				if (j.length>0) {
					// Sort data
					j.sort(function (a, b) {
						return 1;
					});					
					var count = 0;
					// Open Table
					result += '<table class="tblBox">';			
					// Table Head
					result += '<tr>';
					result += '<th>Nombre</th>';
					result += '<th>CUIT</th>';
					result += '<th>Domicilio</th>';
					result += '<th>Acción</th>';																				
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td>'+object.accidentes_clausula_nombre+'</td>';
						result += '<td>'+object.accidentes_clausula_cuit+'</td>';
						result += '<td>'+object.accidentes_clausula_domicilio+'</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Eliminar Asegurado" onClick="javascript:deleteAccidentesClausula('+object.accidentes_clausula_id+', '+id+');"><span class="ui-icon ui-icon-trash"></span></li>';
						result += '</ul></td>';						
						result += '</tr>';
						count++;
					});
					result += '<tr><td><strong>Total: '+count+'</strong></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
					// Close Table
					result += '</table>';
				} else {
					result += 'La póliza no contiene cláusulas de no repetición.';
				}
				// Populate DIV					
				$('#divBoxListClausula').html(result);								
			}
		});				
	}
	populateDiv_TvAudVid = function(id) {
		$.getJSON("get-json-fich_combinado_familiar_tvaudvid.php?id=" + id, {}, function(j) {
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				$('#tv_aud_vid').empty();
				$.each(j, function(i, object) {
					addTvAudVidItem(object.combinado_familiar_tv_aud_vid_cantidad, object.combinado_familiar_tv_aud_vid_producto, object.combinado_familiar_tv_aud_vid_marca, object.combinado_familiar_tv_aud_vid_serial, object.combinado_familiar_tv_aud_vid_valor);
				});
				calculateTvAudVidTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_ObjEspProrrata = function(id) {
		$.getJSON("get-json-fich_combinado_familiar_objespprorrata.php?id=" + id, {}, function(j) {
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				$('#obj_esp_prorrata').empty();
				$.each(j, function(i, object) {
					addObjEspProrrataItem(object.combinado_familiar_obj_esp_prorrata_cantidad, object.combinado_familiar_obj_esp_prorrata_producto, object.combinado_familiar_obj_esp_prorrata_marca, object.combinado_familiar_obj_esp_prorrata_serial, object.combinado_familiar_obj_esp_prorrata_valor);
				});
				calculateObjEspProrrataTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_EquiposComputacion = function(id) {
		$.getJSON("get-json-fich_combinado_familiar_equiposcomputacion.php?id=" + id, {}, function(j) {
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				$('#equipos_computacion').empty();
				$.each(j, function(i, object) {
					addEquiposComputacionItem(object.combinado_familiar_equipos_computacion_cantidad, object.combinado_familiar_equipos_computacion_producto, object.combinado_familiar_equipos_computacion_marca, object.combinado_familiar_equipos_computacion_serial, object.combinado_familiar_equipos_computacion_valor);
				});
				calculateEquiposComputacionTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_FilmFoto = function(id) {
		$.getJSON("get-json-fich_combinado_familiar_filmfoto.php?id=" + id, {}, function(j) {
			if(j.error == 'expired'){
				sessionExpire('box');
			} else {		
				$('#film_foto').empty();
				$.each(j, function(i, object) {
					addFilmFotoItem(object.combinado_familiar_film_foto_cantidad, object.combinado_familiar_film_foto_producto, object.combinado_familiar_film_foto_marca, object.combinado_familiar_film_foto_serial, object.combinado_familiar_film_foto_valor);
				});
				calculateFilmFotoTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
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
	populateDiv_Poliza_Results = function() {
		$.getJSON("get-json-fich_poliza_search.php", $("#frmSelectPoliza").serialize(), function(j) {
			if(j.error == 'expired') {
				sessionExpire('box');
			} else {			
				if (j.empty == true) {
					$('#divBoxPolizaSearchResults').html('Póliza no encontrada. Intente nuevamente.');
				} else {
					var result = '';
					<!-- Open Table and Row -->
					result += '<table class="tblBox2">';
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td>' + object.poliza_numero + '</td>';
						result += '<td>' + object.validez + '</td>';
						result += '<td>' + object.seguro_nombre + '</td>';
						result += '<td style="text-align:right"><a href="javascript:assignPolizaToEndoso(' + object.poliza_id + ', \'' + object.poliza_numero + '\')">SELECCIONAR</a></td>';
						result += '</tr>';
					});
					result += '</table>';
					$('#divBoxPolizaSearchResults').html(result);
				}
			}			
		});		
	}
	populateDiv_Endosos = function(id) {
		$.getJSON("get-json-fich_poliendosos.php?id="+id, {}, function(j){
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
					result += '<th width="10%">Pedido</th>';
					result += '<th width="10%">Aceptado</th>';
					result += '<th width="60%">Motivo</th>';	
					result += '<th width="10%">Completo</th>';	
					result += '<th width="10%">Acciones</th>';								
					result += '</tr>';					
					// Data
					$.each(j, function(i, object) {
						result += '<tr>';
						result += '<td>'+object.endoso_fecha_pedido+'</td>';
						result += '<td>'+object.endoso_fecha_compania+'</td>';	
						result += '<td><span title="'+object.endoso_tipo+'">'+object.endoso_tipo+'</span></td>';	
						result += '<td>'+object.endoso_completo+'</td>';
						result += '<td><span onClick="openBoxModEndoso('+object.endoso_id+')" style="cursor: pointer;" class="ui-icon ui-icon-extlink" title="Ir al endoso"></span></td>';
						result += '</tr>';									
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'La póliza no posee endosos.';
				}
				// Populate DIV					
				$('#divBoxList').html(result);					
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
	insertFormAsegurado = function(id){	
		// Disable button
		$('#btnBoxAsegurado').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBoxAsegurado").serializeArray();	
		param.push({ name: "box-poliza_id", value: id });		
		// Post				
		$.post("insert-accidentes_asegurado.php", param, function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));						
				} else {
					// Clear form
					$('#frmBoxAsegurado').each(function(){
						this.reset();
					});					
					$("#box-accidentes_asegurado_beneficiario").prop('checked', false);
					// Refresh DIVs
					populateDiv_Asegurado(id);					
				}
				// Enable button
				$('#btnBoxAsegurado').button("option", "disabled", false);				
			}
		});
	}
	insertFormClausula = function(id){	
		// Disable button
		$('#btnBoxClausula').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBoxClausula").serializeArray();	
		param.push({ name: "box-poliza_id", value: id });		
		// Post				
		$.post("insert-accidentes_clausula.php", param, function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));						
				} else {
					// Clear form
					$('#frmBoxClausula').each(function(){
						this.reset();
					});					
					// Refresh DIVs
					populateDiv_Clausula(id);					
				}
				// Enable button
				$('#btnBoxClausula').button("option", "disabled", false);				
			}
		});
	}
	insertFormEndoso = function(id){
		// Disable button
		$('#btnBox').button("option", "disabled", true);			
		// Post				
		$.post("insert-endoso.php", $("#frmBox").serializeArray(), function(data){		
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
					openBoxModEndoso(id);
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
	updateFormAsegurado = function(id){	
		// Disable button
		$('#btnBoxAsegurado').button("option", "disabled", true);
		// Post				
		$.post("update-accidentes_asegurado.php", $("#frmBoxAsegurado").serialize(), function(data){		
			if (data=='Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));						
				} else {
					// Clear form
					$('#frmBoxAsegurado').each(function(){
						this.reset();
					});					
					$("#box-accidentes_asegurado_id").remove();
					$("#box-action").val('insert');
					$("#btnBoxAseguradoReset").button('option', 'label', 'Borrar');
					$("#btnBoxAsegurado").button('option', 'label', 'Agregar');
					$("#box-accidentes_asegurado_beneficiario_tomador").prop('checked', false);
					$("#box-accidentes_asegurado_beneficiario").prop('checked', false);
					$("#box-accidentes_asegurado_beneficiario_nombre, #box-accidentes_asegurado_beneficiario_documento, #box-accidentes_asegurado_beneficiario_nacimiento, #box-accidentes_asegurado_beneficiario_tomador").prop('disabled', true);
					// Refresh DIVs
					populateDiv_Asegurado(id);					
				}
				// Enable button
				$('#btnBoxAsegurado').button("option", "disabled", false);				
			}
		});
	}
	updateFormClausula = function(id){	
		
	}
	updateFormEndoso = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);		
		// Post				
		$.post("update-endoso.php", $("#frmBox").serialize(), function(data){		
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
					populateFormBoxEndoso($('#box-endoso_id').val());					
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
		
		// AJAXize form
		$("#frmBox").ajaxSubmit({
			url: 'process-polizadet.php',
			type: 'POST',
			data: { 'box-poliza_id': id },
			beforeSend: function() {
		    	
			},
			uploadProgress: function(event, position, total, percentComplete) {
		       
			},
			success: function(responseText, statusText, xhr) {
				data = responseText;
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
	deleteAccidentesAsegurado = function (id, poliza_id) {		
		$.when(
			deleteViaLink('accidentes_asegurado', id)
		).then(function(){
			populateDiv_Asegurado(poliza_id);	
		})
	}			
	deleteAccidentesClausula = function (id, poliza_id) {		
		$.when(
			deleteViaLink('accidentes_clausula', id)
		).then(function(){
			populateDiv_Clausula(poliza_id);	
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
					populateListSeguro('box-seguro_id', 'box'),
					populateListUsuario_Sucursal('box-sucursal_id', 'box', true	)
				).then(function(){							
								
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-sucursal_id": {required: true},
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
						dateFormat: 'dd/mm/yy',
						changeYear: true,									
						yearRange: "-100:+0",
						changeMonth: true										
					});
					$("#box-cliente_reg_vencimiento").datepicker({
						dateFormat: 'dd/mm/yy',
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
							"box-cliente_nacimiento": {required: true, dateAR: true},
							"box-cliente_sexo": {required: true},
							"box-cliente_nacionalidad": {required: true},
							"box-cliente_cf": {required: true},
							"box-cliente_tipo_doc": {required: true},
							"box-cliente_nro_doc": {required: true},
							"box-cliente_reg_vencimiento": {dateAR: true},
							"box-cliente_email": {email: true}
						}
					});		
							
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
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
				
				// Disable form
				formDisable('frmBox','ui',true);				

				// Populate form, then initialize
				$.when(populateFormBoxCliente(id)).then(function(){
					
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-cliente_nombre": {required: true},
							"box-cliente_nacimiento": {required: true, dateAR: true},
							"box-cliente_sexo": {required: true},
							"box-cliente_nacionalidad": {required: true},
							"box-cliente_cf": {required: true},
							"box-cliente_tipo_doc": {required: true},
							"box-cliente_nro_doc": {required: true},
							"box-cliente_reg_vencimiento": {dateAR: true},
							"box-cliente_email": {email: true}
						}
					});
			
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
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
				populateDiv_Fotos('cliente', id);
				
				// Append hidden input to AJAX file form
				$('<input>').prop({
				    type: 'hidden',
				    id: 'cliente_id',
				    name: 'cliente_id'
				}).val(id).appendTo($('#fileForm'));
				
				// AJAX file form
				$("#fileForm").ajaxForm({
					beforeSend: function() {
				    	$("#fotosLoading").show();
					},
					uploadProgress: function(event, position, total, percentComplete) {
					       
					},
					complete: function(xhr) {
						if (xhr.responseText.indexOf('Error:')!=-1) {
							alert(xhr.responseText);
						}
						else {
							$("#fotosLoading").hide();
						}
						populateDiv_Fotos('cliente', id);
					}
				});
					
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
	openBoxAltaPoliza = function (tipo) {
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
					populateListTipoPoliza('box-tipo_poliza_id', 'box', tipo),
					populateListPoliza_Vigencia('box-poliza_vigencia', 'box'),
					populateListPoliza_Cuotas('box-poliza_cuotas', 'box'),
					populateListPoliza_MP('box-poliza_medio_pago', 'box')
				).then(function(){					
					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					var empty = '<option value="">Todos</option>';
					$("#box-tipo_poliza_id").change(function(){
						$('#box-subtipo_poliza_id').html(loading);
						populateListSubtipoPoliza($(this).val(), 'box-subtipo_poliza_id', 'box');
						// Si el tipo de póliza es PERSONAS, deshabilitar campo AJUSTE y ampliar rango de selección de vigencia
						switch ($(this).val()) {
							case '3':
								$('#box-poliza_ajuste').val('').parent().hide();
								break;
							default:
								$('#box-poliza_ajuste').parent().show();
								break;
						}
						populateListPoliza_Vigencia('box-poliza_vigencia', 'box', $(this).val());
					});	
					$("#box-subtipo_poliza_id").change(function(){
						// Si el subtipo de poliza es Automotor habilitar campo AJUSTE
						switch($(this).val()) {
							case '6':
								$('#box-poliza_ajuste').parent().show();
								break;
							default:
								$('#box-poliza_ajuste').val('').parent().hide();
								break;
						}

					})
					$('#box-sucursal_id').change(function(){
						$('#box-productor_seguro_id').html(loading);						
						populateListProductorSeguro_Productor($("#box-seguro_id").val(), $(this).val(), 'box-productor_seguro_id', 'box');
					})
					$("#box-seguro_id").change(function(){
						$('#box-productor_seguro_id').html(loading);						
						populateListProductorSeguro_Productor($(this).val(), $('#box-sucursal_id').val(), 'box-productor_seguro_id', 'box');
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
							case 'Cuatrimestral':
								months = 4;
								break;
							case 'Trimestral':
								months = 3;
								break;
							case 'Bimestral':
								months = 2;															
								break;
							case 'Mensual':
								months = 1;
								break;					
						}
						if ($(this).val() !== '') {
							if ($(this).val() == 'Otra') {
								$("#box-poliza_vigencia_dias").attr('readonly', false);
								$("#box-poliza_vigencia_dias").focus();
							}
							else {
								$("#box-poliza_vigencia_dias").attr('readonly', true);
								$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'yy-mm-dd');
								$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'yy-mm-dd');
								if ($('#box-poliza_validez_desde').val() !== '') {
									var parsedate = Date.parse($('#box-poliza_validez_desde').val());
									if (parsedate !== null) {
										var newdate = parsedate.addMonths(months).toString("yyyy-MM-dd");
										$('#box-poliza_validez_hasta').val(newdate);
									}
								}
								$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'dd/mm/y');
								$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'dd/mm/y');
							}
						} else {
							$('#box-poliza_validez_hasta').val('');							
						}
					});
					$("#box-poliza_vigencia_dias").change(function() {
						var days = $(this).val();
						$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'yy-mm-dd');
						$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'yy-mm-dd');
						if ($(this).val() !== '') {
							if ($('#box-poliza_validez_desde').val() !== '') {
								var parsedate = Date.parse($('#box-poliza_validez_desde').val());
								if (parsedate !== null) {
									var newdate = parsedate.addDays(days).toString("yyyy-MM-dd");
									$('#box-poliza_validez_hasta').val(newdate);
								}
							}
						} else {
							$('#box-poliza_validez_hasta').val('');							
						}
						$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'dd/mm/y');
						$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'dd/mm/y');
					})
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
									case 'Cuatrimestral':
										cuotas = 4;
										break;
									case 'Trimestral':
										cuotas = 3;
										break;
									case 'Bimestral':
										cuotas = 2;															
										break;				
									case 'Mensual':
										cuotas = 1;
										break;			
								}
								break;
						}
						$('#box-poliza_cant_cuotas').val(cuotas);							
					});																
					// Set default values
					$('#box-poliza_validez_desde').val(Date.today().clearTime().toString("dd/MM/yy"));
					$('#box-poliza_fecha_solicitud').val(Date.today().clearTime().toString("dd/MM/yy"));
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
							"box-poliza_validez_desde": {required: true, dateAR: true},
							"box-poliza_validez_hasta": {required: true, dateAR: true, enddate: "#box-poliza_validez_desde"},
							"box-poliza_cuotas": {required: true},
							"box-poliza_cant_cuotas": {required: true, digits: true, min:1, max:255},
							"box-poliza_fecha_solicitud": {dateAR: true},
							"box-poliza_fecha_emision": {dateAR: true},
							"box-poliza_fecha_recepcion": {dateAR: true},
							"box-poliza_fecha_entrega": {dateAR: true},
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
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								insertFormPoliza();
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
						populateListProductorSeguro_Productor($(this).val(), $('#box-sucursal_id').val(), 'box-productor_seguro_id', 'box');
					});		
					
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-productor_seguro_id": {required: true},
							"box-poliza_fecha_solicitud": {dateAR: true},
							"box-poliza_fecha_emision": {dateAR: true},
							"box-poliza_fecha_recepcion": {dateAR: true},
							"box-poliza_fecha_entrega": {dateAR: true},
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
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormPoliza();
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
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
					// Si el tipo de póliza es PERSONAS, deshabilitar campo AJUSTE y ampliar rango de selección de vigencia
					switch ($("#box-tipo_poliza_nombre").val()) {
						case 'Personas':
							$('#box-poliza_ajuste').prop('disabled', true);
							break;
						default:
							$('#box-poliza_ajuste').prop('disabled', false);
							break;
					}
					populateListPoliza_Vigencia('box-poliza_vigencia', 'box', $("#box-tipo_poliza_nombre").val());
					
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					$("#box-seguro_id").change(function(){
						$('#box-productor_seguro_id').html(loading);						
						populateListProductorSeguro_Productor($(this).val(), $('#box-sucursal_id').val(), 'box-productor_seguro_id', 'box');
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
							case 'Cuatrimestral':
								months = 4;
								break;
							case 'Trimestral':
								months = 3;
								break;
							case 'Bimestral':
								months = 2;															
								break;
							case 'Mensual':
								months = 1;
								break;							
						}
						
						if ($(this).val() !== '') {
							if ($(this).val() == 'Otra') {
								$("#box-poliza_vigencia_dias").attr('readonly', false);
								$("#box-poliza_vigencia_dias").focus();
							}
							else {
								$("#box-poliza_vigencia_dias").attr('readonly', true);
								$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'yy-mm-dd');
								$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'yy-mm-dd');
								if ($('#box-poliza_validez_desde').val() !== '') {
									var parsedate = Date.parse($('#box-poliza_validez_desde').val());
									if (parsedate !== null) {
										var newdate = parsedate.addMonths(months).toString("yyyy-MM-dd");
										$('#box-poliza_validez_hasta').val(newdate);
									}
								}
								$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'dd/mm/y');
								$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'dd/mm/y');
							}
						} else {
							$('#box-poliza_validez_hasta').val('');							
						}
					});
					$("#box-poliza_vigencia_dias").change(function() {
						var days = $(this).val();
						$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'yy-mm-dd');
						$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'yy-mm-dd');
						if ($(this).val() !== '') {
							if ($('#box-poliza_validez_desde').val() !== '') {
								var parsedate = Date.parse($('#box-poliza_validez_desde').val());
								if (parsedate !== null) {
									var newdate = parsedate.addDays(days).toString("yyyy-MM-dd");
									$('#box-poliza_validez_hasta').val(newdate);
								}
							}
						} else {
							$('#box-poliza_validez_hasta').val('');							
						}
						$('#box-poliza_validez_desde').datepicker('option', 'dateFormat', 'dd/mm/y');
						$('#box-poliza_validez_hasta').datepicker('option', 'dateFormat', 'dd/mm/y');
					})
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
									case 'Cuatrimestral':
										cuotas = 4;
										break;
									case 'Trimestral':
										cuotas = 3;
										break;
									case 'Bimestral':
										cuotas = 2;															
										break;				
									case 'Mensual':
										cuotas = 1;
										break;			
								}
								break;
						}
						$('#box-poliza_cant_cuotas').val(cuotas);							
					});																
					// Set default values
					$('#box-poliza_fecha_solicitud').val(Date.today().clearTime().toString("dd/MM/yy"));				
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {							
							"box-seguro_id": {required: true},
							"box-productor_seguro_id": {required: true},
							"box-poliza_vigencia": {required: true},
							"box-poliza_validez_desde": {required: true, dateAR: true},
							"box-poliza_validez_hasta": {required: true, dateAR: true, enddate: "#box-poliza_validez_desde"},
							"box-poliza_cuotas": {required: true},
							"box-poliza_cant_cuotas": {required: true, digits: true, min:1, max:255},
							"box-poliza_fecha_solicitud": {dateAR: true},
							"box-poliza_fecha_emision": {dateAR: true},
							"box-poliza_fecha_recepcion": {dateAR: true},
							"box-poliza_fecha_entrega": {dateAR: true},
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
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
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
			width:'750px',
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
				
				// Disable form
				formDisable('frmBox','ui',true);
								
				// Populate form, then initialize
				$.when(populateFormBoxPolizaDet(id)).then(function(){				
					
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
				
					$('#box-suma_asegurada').val($('#box-valor_total').val());
					
					// Validate form
					var validateForm = $("#frmBox").validate();				
							
					// Button action	
					$("#btnBox").click(function() {
						// if (customValidations()) {
							if (validateForm.form() && customValidations()) {					
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								processFormPolizaDet(id, fromcreate);
							}
						// }
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
				$("#btnPR").button();
				
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
				$("#btnPR").click(function() {
					window.open('print-poliza.php?type=pe&re=1&id='+id);
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
	editInBoxAsegurado = function(id) {
		// Disable form
		formDisable('frmBoxAsegurado','ui',true);
		$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');	
		$.when(populateFormBoxAsegurado(id)).then(function() {
			$("#box-accidentes_asegurado_beneficiario_nombre, #box-accidentes_asegurado_beneficiario_documento, #box-accidentes_asegurado_beneficiario_nacimiento, #box-accidentes_asegurado_beneficiario_tomador").prop('disabled', !($("#box-accidentes_asegurado_beneficiario").prop('checked')));
			var suma_asegurada = isNaN($("#box-accidentes_asegurado_suma_asegurada").val())?0:$("#box-accidentes_asegurado_suma_asegurada").val();
			var gastos_medicos = isNaN($("#box-accidentes_asegurado_gastos_medicos").val())?0:$("#box-accidentes_asegurado_gastos_medicos").val();
			$("#box-accidentes_asegurado_total").val(Number(suma_asegurada) + Number(gastos_medicos));
			
			// Append hidden input to form
			$('<input>').prop({
			    type: 'hidden',
			    id: 'box-accidentes_asegurado_id',
			    name: 'box-accidentes_asegurado_id'
			}).val(id).appendTo($('#frmBoxAsegurado'));
			$("#box-action").val('edit');
			$("#btnBoxAseguradoReset").button('option', 'label', 'Cancelar').click(function() {
				// Clear form
				$('#frmBoxAsegurado').each(function(){
					this.reset();
				});					
				$("#box-accidentes_asegurado_id").remove();
				$("#box-action").val('insert');
				$("#btnBoxAseguradoReset").button('option', 'label', 'Borrar');
				$("#btnBoxAsegurado").button('option', 'label', 'Agregar');
				$("#box-accidentes_asegurado_beneficiario").prop('checked', false);
			});
			$("#btnBoxAsegurado").button('option', 'label', 'Guardar');
			$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
			formDisable('frmBoxAsegurado','ui',false);
			$("#box-accidentes_asegurado_nombre").focus();
		});
	}	
	openBoxAltaEndoso = function() {
		$.colorbox({
			title:'Endoso',
			href:'box-endoso_alta.php',												
			width:'700px',
			height:'100%',
			onComplete: function() {			
			
				$("#btnBox").button();
			
			
				formDisable('frmSelectPoliza','normal',false);
				formDisable('frmBox','ui',true);
				
				$.when(
					populateListEndosoTipo('box-endoso_tipo_id', 'box')
				).then(function() {
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					$('#box-endoso_fecha_pedido').val(Date.today().clearTime().toString("dd/MM/yy"));
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-endoso_fecha_pedido": {required: true, dateAR: true},
							"box-endoso_tipo": {required: true},
							"box-endoso_fecha_compania": {dateAR: true},
						},
						errorPlacement: function(error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?\n\nEsta acción no puede deshacerse.')) {												
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								insertFormEndoso();
							}
						}
					});
				});
				
				// FORM SELECT POLIZA
				// Initialize special fields
				initAutocompletePoliza('box0-poliza_numero', 'box');
				// Assign functions to buttons
				$("#BtnSearchPoliza").click(function() {
					// If a field was completed
					if ($('#box0-poliza_numero').val() != '') {
						populateDiv_Poliza_Results();
					} else {
						$('#divBoxPolizaSearchResults').html('Debe ingresar información en al menos un campo.');
					}
				});
				// Submit on Enter
				$("#frmSelectPoliza :input[type=text]").each(function() {
					$(this).keypress(function(e) {
						if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
							$("#BtnSearchPoliza").click();
						}
					});
				});													
				// Enable form					
				formDisable('frmSelectPoliza','normal',false);
				// Set focus on search
				$("#box0-poliza_numero").focus();		
			
			}
			
		});
	}
	openBoxModEndoso = function(id) {
		$.colorbox({
			title:'Endoso',
			href:'box-endoso_mod.php',												
			width:'700px',
			height:'100%',
			onComplete: function() {			
			
				$("#btnBox").button();
				$("#btnBoxExport").button();
			
				formDisable('frmBox','ui',true);
				
				populateDiv_Fotos('endoso', id);
				$("#endoso_id").val(id);
				// AJAX file form
				$("#fileForm").ajaxForm({
					beforeSend: function() {
				    	$("#fotosLoading").show();
					},
					uploadProgress: function(event, position, total, percentComplete) {
					       
					},
					complete: function(xhr) {
						if (xhr.responseText.indexOf('Error:')!=-1) {
							alert(xhr.responseText);
						}
						else {
							$("#fotosLoading").hide();
						}
						populateDiv_Fotos('endoso', id);
					}
				});
				
				$.when(
					populateFormBoxEndoso(id)
				).then(function() {
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-endoso_fecha_pedido": {required: true, dateAR: true},
							"box-endoso_tipo": {required: true},
							"box-endoso_fecha_compania": {dateAR: true},
						},
						errorPlacement: function(error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action	
					$("#btnBox").click(function() {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormEndoso();
						}
					});
					$("#btnBoxExport").click(function() {
						var poliza_id = $('#box-poliza_id').val();
						window.open('print-poliza.php?type=pe&en=1&id='+poliza_id+'&endoso_id='+id);
					})
					// Enable form							
					formDisable('frmBox','ui',false);
				});
			}
			
		});
	}
	openBoxEndosos = function(id) {
		$.colorbox({
			title:'Póliza/Endosos',
			href:'box-poliendosos.php',												
			width:'700px',
			height:'600px',
			onComplete: function() {	
			
				// -------------------- GENERAL ---------------------
				
				// Populate DIVs
				populateDiv_Poliza_Info(id);
				populateDiv_Endosos(id);													
			}
		});	
	}
});