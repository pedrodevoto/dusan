$(document).ready(function () {

	/* ---------------------------- FILTER AND REUSABLE FUNCTIONS ---------------------------- */

	/* Session functions */
	sessionExpire = function (type) {
		switch (type) {
		case 'main':
			document.location.href = 'index.php';
			break;
		case 'box':
			$.colorbox.close();
			document.location.href = 'index.php';
			break;
		}
	}

	/* Formatting functions */
	nullToSpace = function (value) {
		if (value == null) {
			return '&nbsp;';
		} else {
			return value;
		}
	}
	formatNumber = function (x) {
		return x.toString().replace(/\./g, ",").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
	getYears = function (startYear, futureYears) {
		futureYears = futureYears || 0;
		var currentYear = new Date().getFullYear() + futureYears,
			years = [];
		startYear = startYear || 1980;

		while (startYear <= currentYear) {
			years.push(startYear++);
		}

		return years;
	}
	formToJson = function (form) {
		var arr = $(form).serializeArray();
		var ret = {};
		$.each(arr, function() {	
			var key = this.name;
			var val = this.value || '';
			if (key.match(/\[\]$/)) {
				if (val!='') {
					if (ret[key]) ret[key].push(val);
					else ret[key] = [val];
				}
			}
			else {
				ret[key] = val;
			}
		});
		return ret;
	}
	
	$(document).tooltip();
	
	/* Custom validations */
	$.validator.addMethod("dateAR", function (value, element) {
		return value == '' || value.match(/^\d\d\/\d\d\/\d\d(\d\d)?$/);
	}, 'Por favor ingresar una fecha en formato dd/mm/aa.');
	$.validator.addMethod("datetime", function (value, element) {
		return value == '' || value.match(/^\d\d\/\d\d\/\d\d(\d\d)? \d\d:\d\d$/);
	}, 'Por favor ingrese una fecha en formato dd/mm/aa hh:mm');
	$.validator.addMethod("csemails", function (value, element) {
		return value == '' || value.match(/^(([\w\+\-\.]+)@([\w\-]+\.[\w\-\.]+)(\s*,\s*)?)*$/);
	}, 'Por favor ingrese direcciones de mail separadas por coma (\',\')');

	customValidations = function () {
		var objects = [{
			'name': 'tv_aud_vid_total',
			'desc': 'Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto',
			'min': 100,
			'max': 30000
		}, {
			'name': 'obj_esp_prorrata_total',
			'desc': 'Robo y/o Hurto de Objetos Específicos y/o Aparatos Electrodomésticos a Prorrata',
			'min': 100,
			'max': 20000
		}, {
			'name': 'equipos_computacion_total',
			'desc': 'Todo Riesgo Equipos de Computación en Domicilio a Primer Riesgo Absoluto',
			'min': 1500,
			'max': 10000
		}, {
			'name': 'film_foto_total',
			'desc': 'Robo de Filmadoras y/o Cam. Fotográficas a Prorrata',
			'min': 500,
			'max': 5000
		}, ];

		for (var i = 0; i < objects.length; i++) {
			var obj = objects[i];
			var value = Number($('#' + obj.name).text());
			if (value != 0 && (value < obj.min || value > obj.max)) {
				alert('El valor total asegurado de ' + obj.desc + ' debe ser mayor a ' + obj.min + ' y menor a ' + obj.max);
				return false;
			}
		}
		return true;
	}

	$.validator.defaults.focusInvalid = false;
	$.validator.defaults.invalidHandler = function(form, validator) {
		if (!validator.numberOfInvalids()) {
			return;
		}
		$(validator.errorList[0].element).focus();
	}
	$.colorbox.settings.overlayClose = false;
	$.colorbox.settings.fixed = true;

	$.fn.hasAnyClass = function() {
	    var classes = arguments[0].split(" ");
	    for (var i = 0; i < classes.length; i++) {
	        if (this.hasClass(classes[i])) {
	            return true;
	        }
	    }
	    return false;
	}
	String.prototype.capitalize = function() {
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}

	/* List functions */
	sortListAlpha = function (field) {
		$("select#" + field).html($("select#" + field + " option").sort(function (a, b) {
			return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
		}));
	}
	sortListValue = function (field) {
		$("#" + field).html($("#" + field + " option").sort(function (a, b) {
			var aValue = parseInt(a.value);
			var bValue = parseInt(b.value);
			return aValue == bValue ? 0 : aValue < bValue ? -1 : 1;
		}));
	}
	appendListItem = function (field, optionvalue, optiontext) {
		$("select#" + field).prepend($('<option />').attr('value', optionvalue).text(optiontext));
	}
	selectFirstItem = function (field) {
		$("select#" + field).val($("select#" + field + " option:first").val());
	}
	sortListNum = function (field) {
		$("select#" + field).html($("select#" + field + " option").sort(function (a, b) {
			return parseFloat(a.text.replace(/[^0-9\.]/g, '')) > parseFloat(b.text.replace(/[^0-9\.]/g, ''));
		}));
	}
	
	/* Initialize Special Field functions */
	initDatePickersDaily = function (clase, clear, maxdate) {
		$("." + clase).each(function () {
			var date = $(this).datepicker({
				dateFormat: 'yy-mm-dd'
			});
			if (clear == true) {
				date.click(function () {
					$(this).val("");
				});
			}
			if (maxdate != null) {
				date.datepicker("option", "maxDate", maxdate);
			}
		});
	}
	initDatePickersWeekly = function (clase, clear, weekday) {
		$("." + clase).each(function () {
			var date = $(this).datepicker({
				dateFormat: 'yy-mm-dd',
				beforeShowDay: function (date) {
					return [date.getDay() == weekday, ""]
				}
			});
			if (clear == true) {
				date.click(function () {
					$(this).val("");
				});
			}
		});
	}
	initAutocompleteCliente = function (field, context) {
		$("#" + field).autocomplete({
			source: "get-json-cliente_nombre.php",
			minLength: 2,
			select: function (event, ui) {
				if (ui.item.value == 'Session expired') {
					sessionExpire(context);
				}
			}
		});
	}
	initAutocompletePoliza = function (field, context) {
		$("#" + field).autocomplete({
			source: "get-json-poliza_numero.php",
			minLength: 2,
			select: function (event, ui) {
				if (ui.item.value == 'Session expired') {
					sessionExpire(context);
				}
			}
		});
	}
	initDateTimePicker = function (clase) {
		$("." + clase).each(function () {
			var date = $(this).datetimepicker({
				currentText: 'Ahora',
				closeText: 'Listo',
				timeText: 'Horario',
				hourText: 'Hora',
				minuteText: 'Minuto',
			})
		})
	}
	initCalendar = function(defaultView, contentHeight) {
	    $( "#eventdialog, #neweventdialog" ).dialog({
	      autoOpen: false,
	    });
		$('#btnEvent').button().click(function(event) {
			$.post('process-evento.php', $('#frmEvent').serializeArray(), function (data) {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				}
				else {
					if ($('#box-evento_id').val()) {
						// update
						$('#neweventdialog').dialog('close');
						// TODO fix event update
						var event = $('#calendar').fullCalendar('clientEvents', $('#box-evento_id').val());
						event.title = $('#box-evento_titulo').val().toUpperCase();
						event.description = $('#box-evento_descripcion').val().toUpperCase();
						$('#calendar').fullCalendar('updateEvent', event);
					}
					else if(data==parseInt(data)) {
						// add
						var event = {
							id: parseInt(data),
							start: $('#box-evento_fecha').val(),
							title: $('#box-evento_titulo').val().toUpperCase(),
							description: $('#box-evento_descripcion').val().toUpperCase(),
							className: 'evento'
						};
						$('#calendar').fullCalendar('addEventSource', [event]);
					}
					$('#frmEvent').each(function () {
						this.reset();
					});
					$('#neweventdialog').dialog('close');
				}
			});
			event.preventDefault();
		});
		$('#calendar').fullCalendar({
			header: {
					left: 'prev,next today',
					center: 'title',
					right: ''
			},
			defaultView: defaultView,
			contentHeight: contentHeight,
			editable: false,
			eventLimit: true,
			eventSources: [
				{
					url: 'get-json-vencimientos.php',
					className: 'vencimiento',
					color: '#cd0a0a',
				},
				{
					url: 'get-json-renovaciones.php',
					className: 'renovacion',
				},
				{
					url: 'get-json-eventos.php',
					className: 'evento',
				},
				{
					url: 'get-json-cumpleanos.php',
					className: 'cumpleano',
					color: '#3c763d',
				},
				{
					url: 'https://www.google.com/calendar/feeds/info%40dusanasegurador.com.ar/public/basic',
					className: 'gcal-event'
				}
			],
			eventClick: function(event, jsEvent, view) {
				date = event.start;
				if ($(this).hasAnyClass('vencimiento renovacion cumpleano')) {
					$('#eventdialog').html('Cargando...');
					type = event.id;
					var prefix = event.titlePrefix;
					populateDialog_Calendar(type, date.format("YYYY-MM-DD"));
					$("#eventdialog").dialog({
						position: { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
						title: prefix+' el '+date.format('DD/MM/YY'),
						width: 500
					}).dialog("open");
				}
				if ($(this).hasClass('evento')) {
					$('#frmEvent').each(function () {
						this.reset();
					});
					$('#box-evento_id').val(event.id);
					$('#box-evento_fecha').val(date.format('YYYY-MM-DD'));
					$('#box-evento_titulo').val(event.title);
					$('#box-evento_descripcion').val(event.description);
					$("#neweventdialog").dialog({
						"position": { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
						title: "Evento el "+date.format('DD/MM/YY')
					}).dialog("open");
				}
			},
			dayClick: function(date, jsEvent, view) {
				$('#frmEvent').each(function () {
					this.reset();
				});
				$('#box-evento_fecha').val(date.format('YYYY-MM-DD'));
				$("#neweventdialog").dialog({
					"position": { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
					title: "Evento el "+date.format('DD/MM/YY')
				}).dialog("open");
			},
			eventRender: function(event, element, view) {
				if (event.description) {
					element.prop('title', event.description);
				}
			}
		})
	}

	/* Filter functions */
	disableFilters = function (disabled) {
		$(".tobedisabled").each(function () {
			if (disabled == true) {
				$(this).attr("disabled", "disabled");
			} else {
				$(this).removeAttr('disabled');
			}
		});
	}
	checkIfDateFieldIsEmpty = function () {
		var validate = true;
		$(".datedisabler").each(function () {
			if ($(this).val() != "") {
				validate = false;
			}
		});
		return validate;
	}
	checkIfTextFieldIsEmpty = function () {
		var validate = true;
		$(".txtdisabler").each(function () {
			if ($(this).val() != "") {
				validate = false;
			}
		});
		return validate;
	}
	checkFilters = function () {
		if (checkIfDateFieldIsEmpty() && checkIfTextFieldIsEmpty()) {
			disableFilters(false);
		} else {
			disableFilters(true);
		}
	}
	listenToTxtForDisable = function () {
		$(".txtdisabler").each(function () {
			$(this).keyup(function () {
				checkFilters();
			});
		});
	}
	listenToDateForDisable = function () {
		$(".datedisabler").each(function () {
			$(this).click(function () {
				checkFilters();
			});
			$(this).change(function () {
				checkFilters();
			});
		});
	}
	listenToTxtForSubmit = function () {
		$("form#frmFiltro :input[type=text]").each(function () {
			$(this).keypress(function (e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					$('#btnFiltro').click();
					return false;
				}
			});
		});
	}

	/* Form Disable/Enable/Clear functions */
	formDisable = function (form, type, disabled) {
		// Enable-disable general inputs
		$("#" + form + " textarea").attr("disabled", disabled);
		$("#" + form + " select").attr("disabled", disabled);
		$("#" + form + " input[type='text']").attr("disabled", disabled);
		$("#" + form + " input[type='password']").attr("disabled", disabled);
		$("#" + form + " input[type='radio']").attr("disabled", disabled);
		$("#" + form + " input[type='checkbox']").attr("disabled", disabled);
		$("#" + form + " input[type='number']").attr("disabled", disabled);
		// Enable-disable buttons
		if (type == 'ui') {
			$("#" + form + " input[type='button']").button().button("option", "disabled", disabled);
			$("#" + form + " input[type='submit']").button().button("option", "disabled", disabled);
		} else {
			$("#" + form + " input[type='button']").attr("disabled", disabled);
			$("#" + form + " input[type='submit']").attr("disabled", disabled);
		}
	}

	/* Populate List functions */
	populateListUsuario_Acceso = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-usr_acceso.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options by index value
					sortListValue(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListUsuario_Sucursal = function (field, context, all) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-suc.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options by index value
					sortListValue(field);
					// Append option: "all"
					if (all) appendListItem(field, '', 'Elegir');
					// Select first item
					// selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListProductor_IVA = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-pro_iva.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}

	populateListSuc = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-suc.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}

	populateListSeguro = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-seguro.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListCliente_Sexo = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-clie_sexo.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListCliente_Nac = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-clie_nac.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListCliente_TipoDoc = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-clie_tipodoc.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListCliente_RegTipo = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-clie_regtipo.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListCliente_CF = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-clie_cf.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListCliente_TipoSociedad = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-clie_tipo_sociedad.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListContacto_Tipo = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-contacto_tipo.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListTipoPoliza = function (field, context, include) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-tipopoliza.php?include=" + include,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListSubtipoPoliza = function (parent_id, field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-subtipopoliza.php?id=" + parent_id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListPoliza_Vigencia = function (field, context, tipo_poliza) {
		var vigencia;
		var options = '<option value=\'\'>Todos</option>';
		switch (tipo_poliza) {
		case '3':
		case 3:
		case 'Personas':
			vigencia = ['Mensual', 'Bimestral', 'Trimestral', 'Cuatrimestral', 'Semestral', 'Anual', 'Otra'];
			break;
		case undefined:
			vigencia = [];
			break;
		default:
			vigencia = ['Mensual', 'Bimestral', 'Trimestral', 'Cuatrimestral', 'Semestral', 'Anual', 'Otra'];
			break;
		}
		for (var i = 0; i < vigencia.length; i++) {
			options += '<option value="' + vigencia[i] + '">' + vigencia[i] + '</option>';
		}
		$('#' + field).html(options);
	}
	populateListPoliza_Cuotas = function (field, context, seguro_id) {
		var cuotas;
		switch (seguro_id) {
		case "4":
			cuotas = ['Mensual', 'Semestral'];
			break;
		default:
			cuotas = ['Mensual', 'Total'];
			break;
		}
		var options = '';
		for (var i = 0; i < cuotas.length; i++) {
			options += '<option value="' + cuotas[i] + '">' + cuotas[i] + '</option>';
		}
		$('#' + field).html(options);
		
		return true;
		
		// var dfd = new $.Deferred();
		// $.ajax({
		// 	url: "get-json-poliza_cuotas.php",
		// 	dataType: 'json',
		// 	success: function (j) {
		// 		if (j.error == 'expired') {
		// 			sessionExpire(context);
		// 		} else if (j.empty == true) {
		// 			// Record not found
		// 			$.colorbox.close();
		// 		} else {
		// 			var options = '';
		// 			$.each(j, function (key, value) {
		// 				options += '<option value="' + key + '">' + value + '</option>';
		// 			});
		// 			$('#' + field).html(options);
		// 			// Sort options alphabetically
		// 			sortListAlpha(field);
		// 			// Select first item
		// 			selectFirstItem(field);
		// 			dfd.resolve();
		// 		}
		// 	}
		// });
		// return dfd.promise();
	}
	populateListPoliza_MP = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-poliza_mp.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Select first item
					$('#' + field).val('Directo').change();
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListProductorSeguro_Productor = function (seguro_id, sucursal_id, field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-productorseguro_productor.php?id=" + seguro_id + '&id2=' + sucursal_id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListAseguradoActividad = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-asegurado_actividad.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListPlizaEstado = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-poliza_estado.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListEndosoTipo = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-endoso_tipo.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key0, value0) {
						options += '<optgroup label="' + key0 + '">';
						$.each(value0, function (key, value) {
							options += '<option value="' + key + '">' + value + '</option>';
						})
						options += '</optgroup>';
					});
					$('#' + field).html(options);
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
	populateListCoberturaTipo = function (field, context, x, id) {
		var dfd = new $.Deferred();
		var url = "get-json-cobertura_tipo"+(x?"_id":"")+".php" + (id>0?'?id='+id:'');
		$.ajax({
			url: url,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					if (x) {
						
					}
					else {
						appendListItem(field, '', 'Elegir');
					}
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListProductor = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-productor.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
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
	populateListLimiteRC = function (field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-limite_rc.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					sortListNum(field);
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
	
	populateListZonaRiesgo = function(field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-zona_riesgo.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
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
	
	populateListPoliza_Plan = function(field, context, subtipo_poliza_id, seguro_id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-poliza_plan.php?subtipo_poliza_id=" + subtipo_poliza_id + "&seguro_id=" + seguro_id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListPoliza_Pack = function(field, context, id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-poliza_pack.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListClientes = function(field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-cliente.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + value[0] + '">' + value[1] + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	
	populateListTelefonoCompania = function(field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-contacto_telefono_compania.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + value[0] + '">' + value[1] + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Elegir');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}

	populateListOrg = function(field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-org.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					// Sort options alphabetically
					sortListAlpha(field);
					// Append option: "all"
					appendListItem(field, '', 'Sin organizador');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListLocalidades = function(field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-localidad.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + value[0] + '">' + value[1] + '</option>';
					});
					$('#' + field).html(options);
					// Append option: "all"
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListAutoMarca = function(field, context) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-auto_marca.php",
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListAutoModelo = function(field, context, marca_id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-auto_modelo.php?marca_id="+marca_id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateListAutoVersion = function(field, context, modelo_id, ano) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-auto_version.php?modelo_id="+modelo_id+"&ano="+ano,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire(context);
				} else {
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
					});
					$('#' + field).html(options);
					appendListItem(field, '', 'Seleccione');
					// Select first item
					selectFirstItem(field);
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	
	/* Delete via Link functions */
	deleteViaLink = function (section, id, table) {
		table = table || (typeof oTable != 'undefined'?oTable:undefined);
		var dfd = new $.Deferred();
		if (confirm('Está seguro que desea eliminar el registro?\n\nEsta acción no puede deshacerse.')) {
			$.post('delete-' + section + '.php', {
				id: id
			}, function (data) {
				// Table standing redraw
				if (typeof table != 'undefined') {
					table.fnStandingRedraw();
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
	// Globo flotante de stats
	$( "#stats" ).dialog({
		position: { my: "left top", at: "right top+150", of: $('#divContainer') },
		width: 140,
		dialogClass: 'fixedpos'
	});
	$.ajax({
		url: "get-json-stats.php",
		dataType: 'json',
		success: function (j) {
			var output = '';
			$.each(j, function (key, object) {
				if (key=='total') {
					output += '<strong>TOTAL VIGENTES: '+object+'</strong>'
				}
				else {
					output += '<strong>Vigentes '+object.seguro_nombre+': '+object.vigentes+'</strong>';
					output += '<br />';
					output += 'Directo: '+object.directo;
					output += '<br />';
					output += 'TC + DC: '+object.tc;
					output += '<br />';
					output += 'Cup: '+object.cup;
					output += '<br /><br />';
				}
			});
			$('#stats').html(output);
		}
	});

	/* --------------------------------- BOX FUNCTIONS --------------------------------- */

	/* General functions */
	showBoxConf = function (data, autoscroll, hide, delay, callback, suffix) {
		suffix = suffix || '';
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
		$("#divBoxMessage").show("fast", function () {
			// If autoscroll was set, scroll to bottom
			if (autoscroll == true) {
				$("#cboxLoadedContent").scrollTop($("#cboxLoadedContent")[0].scrollHeight);
			}
		});
		// Determine hide method
		switch (hide) {
		case 'always':
			// Delay and hide
			$("#divBoxMessage").delay(delay).hide("fast", function () {
				// If no error ocurred, execute callback function
				if (ok) {
					callback(ok);
				}
				// Enable button
				$('#btnBox').button("option", "disabled", false);
			});
			break;
		case 'onerror':
			// If an error occurred
			if (!ok) {
				// Delay and hide
				$("#divBoxMessage").delay(delay).hide("fast", function () {
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
			if (ok) {
				callback(ok);
			}
			// Enable button
			$('#btnBox').button("option", "disabled", false);
			break;
		}
	}

	/* Populate form functions */
	populateFormGeneric = function (j, target) {
		$.each(j, function (key, value) {
			var element = '#' + target + '-' + key;
			if ($(element).length > 0) {
				switch ($(element).attr('type')) {
				case 'checkbox':
				case 'radio':
					if (value == 1 || value.toLowerCase() == 'on') {
						$(element).attr('checked', true);
					} else if (value == 0) {
						$(element).attr('checked', false);
					}
					break;
				case 'span':
					$(element).text(value);
					break;
				default:
					$(element).val($(element).prop("multiple") ? value.split(',') : value);
					break;
				}
			}
		});
	}
	populateFormBoxUsuario = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_usr.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListUsuario_Acceso('box-usuario_acceso', 'box'),
						populateListUsuario_Sucursal('box-usuario_sucursal', 'box')
					).then(function () {
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
	populateFormBoxSeguro = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_seguro.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormBoxProd = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_prod.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListProductor_IVA('box-productor_iva', 'box')
					).then(function () {
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
	populateFormBoxOrg = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_org.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						
					).then(function () {
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
	
	populateFormBoxSuc = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_suc.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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

	populateFormBoxCob = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_segcob.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListLimiteRC('box-seguro_cobertura_tipo_limite_rc_id', 'box')
					).then(function () {
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
	populateFormBoxCod = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_prodseg.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListProductor("box-productor_id", "box"),
						populateListSeguro("box-seguro_id", "box"),
						populateListSuc("box-sucursal_id", "box"),
						populateListOrg("box-organizador_id", "box")
					).then(function () {
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
	populateFormBoxRie = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_rie.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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

	populateFormBoxCliente = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_cliente.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListCliente_Sexo('box-cliente_sexo', 'box'),
						populateListCliente_Nac('box-cliente_nacionalidad_id', 'box'),
						populateListCliente_CF('box-cliente_cf_id', 'box'),
						populateListCliente_TipoDoc('box-cliente_tipo_doc', 'box'),
						populateListCliente_RegTipo('box-cliente_reg_tipo_id', 'box'),
						populateListCliente_TipoSociedad('box-cliente_tipo_sociedad_id', 'box'),
						populateListSuc("box-sucursal_id", "box")
					).then(function () {
						// Populate Form
						populateFormGeneric(j, "box");
						populateDiv_Fotos('cliente', id);
						// Resolve
						dfd.resolve();
					});
				}
			}
		});
		return dfd.promise();
	}
	populateFormBoxPoliza = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_poliza.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListSuc('box-sucursal_id', 'box'),
						populateListSeguro('box-seguro_id', 'box'),
						populateListProductorSeguro_Productor(j.seguro_id, j.sucursal_id, 'box-productor_seguro_id', 'box'),
						populateListPoliza_MP('box-poliza_medio_pago', 'box')
					).then(function () {
						// Medio pago (allianz)
						if (j.seguro_id==4) {
							var mp;
							switch (j.poliza_cuotas) {
							case 'Mensual':
								mp = ['Tarjeta de Credito / CBU - 1 Cuota'];
								var options = '';
								for (var i = 0; i < mp.length; i++) {
									options += '<option value="' + mp[i] + '">' + mp[i] + '</option>';
								}
								$('#box-poliza_medio_pago').html(options);
								$('#box-poliza_medio_pago').val('Tarjeta de Credito / CBU - 1 Cuota').change();
								break;
							case 'Semestral':
								mp = ['1 Pago Cupon Contado', '1 Pago Tarjeta de Credito', '6 Cuotas Pago Cupones', '6 Cuotas Pago Tarj/CBU'];
								var options = '';
								for (var i = 0; i < mp.length; i++) {
									options += '<option value="' + mp[i] + '">' + mp[i] + '</option>';
								}
								$('#box-poliza_medio_pago').html(options);
								$('#box-poliza_medio_pago').val('6 Cuotas Pago Cupones').change();
								break;
							}
						}
						// Populate Form
						populateFormGeneric(j, "box");
						$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
						// Resolve
						dfd.resolve(j.master);
					});
				}
			}
		});
		return dfd.promise();
	}
	populateFormBoxPolizaRen = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_polizaren.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
						populateListPoliza_Vigencia('box-poliza_vigencia', 'box', j.tipo_poliza_id),
						populateListPoliza_Cuotas('box-poliza_cuotas', 'box'),
						populateListPoliza_MP('box-poliza_medio_pago', 'box')
					).then(function () {
						// Populate Form
						populateFormGeneric(j, "box");
						if (j.sucursal_pfc==1) {
							$("#pfc").show();
							$("#box-sucursal_pfc").prop("checked", j.sucursal_pfc_default==1);
						}
						else {
							$("#pfc").hide();
						}
						// Resolve
						dfd.resolve();
					});
				}
			}
		});
		return dfd.promise();
	}
	populateFormBoxPolizaDet = function (id, flota) {
		flota = flota || 0;
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_polizadet.php?id=" + id + '&flota=' + flota,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else {
					// Populate Form
					populateFormGeneric(j, "box");
					// Call post function
					if (typeof (polDetInit) == "function") {
						polDetInit();
					}
					populatePolizaDet(j, id);
					// Resolve
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateFormBoxEndoso = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_endoso.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateListEndosoTipo('box-endoso_tipo_id', 'box')
					).then(function () {
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
	populateFormBoxPolizaObservaciones = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_poliza_observaciones.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormPolizaPackPremio = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich-poliza_pack_premio.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormPFC = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich-sucursal_pfc.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					// $.colorbox.close();
				} else {
					if (j.sucursal_pfc==1) {
						$('#pfc').children().eq(0).attr('disabled', false).prop('checked', (j.sucursal_pfc_default==1?true:false));
					}
					else {
						$('#pfc').children().eq(0).attr('disabled', true).prop('checked', false);
					}
					// Resolve
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	populateFormFlota = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-seguro_flota.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					//Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					// $.colorbox.close();
				} else {
					$('#box-poliza_flota').attr('disabled', !j.seguro_flota==1);
					dfd.resolve();
				}
			}
		})
	}
	populateFormBoxSiniestro = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_siniestro.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						populateDiv_SiniestroDatosTerceros(id)
					).then(function () {
						// Populate croquis
						$('.dragged').remove();
						$.each(j, function(k,v) {
							if (k.substring(0, 7)=='croquis') {
								var i = parseInt(k.substring(12));
								var type = k.substring(8, 12);
								croquisAddItem(type, i, v);
							}
						});
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
	populateFormBoxSiniestroDatosTercero = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_siniestro_datos_tercero.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						
					).then(function () {
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
	populateFormBoxSiniestroLesionesTercero = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_siniestro_lesiones_tercero.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						
					).then(function () {
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
	populateFormCajaDiaria = function(sucursal_id, fecha) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_caja_diaria.php?sucursal_id=" + sucursal_id+"&fecha="+fecha,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// Populate drop-downs, then form
					$.when(
						
					).then(function () {
						// Populate Form
						populateFormGeneric(j, "box");
						// Resolve
					});
				}
				calculateCajaDiaria();
				dfd.resolve();
			}
		});
		return dfd.promise();
	}
	populateFormBoxAutoMarca = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_auto_marca.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormBoxAutoModelo = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_auto_modelo.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormBoxAutoVersion = function(id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_auto_version.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					// Session expired
					sessionExpire('box');
				} else if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					var years = getYears(1950, 1).reverse();
					var options = '';
					$.each(years, function(i,e) {
						options += '<option value="' + e + '">' + e + '</option>';
					});
					$('#box-automotor_anos').html(options);
					// Populate Form
					populateFormGeneric(j, "box");
					// Resolve
					dfd.resolve();
				}
			}
		});
		return dfd.promise();
	}
	
	populatePolizaDet = function (j, id) {
		var subtipo_poliza = j.subtipo_poliza;
		switch (subtipo_poliza) {
		case 'automotor':

			populateSectionAutomotorAccesorios(id);
			$.when(
				populateListAutoModelo('box-automotor_modelo_id', 'box', j.automotor_marca_id),
				populateListAutoVersion('box-automotor_version_id', 'box', j.automotor_modelo_id, j.ano)
			).then(function() {
				$('#box-automotor_modelo_id').val(j.automotor_modelo_id);
				$('#box-automotor_version_id').val(j.automotor_version_id);
			});

			// AJAX file form
			$("#fileForm").ajaxForm({
				beforeSend: function () {
					$("#fotosLoading").show();
				},
				uploadProgress: function (event, position, total, percentComplete) {

				},
				complete: function (xhr) {
					if (xhr.responseText.indexOf('Error:') != -1) {
						alert(xhr.responseText);
					} else {
						$("#fotosLoading").hide();
					}
					populateDiv_Fotos('poliza', id);
				}
			});
			$("#box-patente_0, #box-patente_1").change(function() {
				$.ajax({
					url: 'get-json-patente.php?patente_0='+$("#box-patente_0").val()+'&patente_1='+$("#box-patente_1").val(),
					dataType: 'json',
					success: function(j) {
						if (j != false) {
							$('#box-nro_motor').val(j.nro_motor);
							$('#box-nro_chasis').val(j.nro_chasis);
						}
						$('#msg_patente').text(j?'Patente existente':'');

					}
				});
			});
			$('#box-combustible, #box-pedido_instalacion, #box-cert_rodamiento').change();
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
			$('fieldset.optional').each(function (i, e) {
				$(e).prop('disabled', !$(e).children().first().children().first().prop('checked'));
			});
			$('.toggle-fieldset').change(function () {
				$(this).parent().parent().prop('disabled', !$(this).prop('checked')).children('p').first().children().eq(1).focus();
			})
			populateSectionTvAudVid(id);
			populateSectionObjEspProrrata(id);
			populateSectionEquiposComputacion(id);
			populateSectionFilmFoto(id);

			$('#frmBox').submit(function () {
				var objects = [{
					'name': 'tv_aud_vid',
					'min': 100,
					'max': 30000
				}, {
					'name': 'obj_esp_prorrata',
					'min': 100,
					'max': 20000
				}, {
					'name': 'equipos_computacion',
					'min': 1500,
					'max': 10000
				}, {
					'name': 'film_foto_total',
					'min': 500,
					'max': 5000
				}, ]

				$.each(objects, function (i, obj) {
					var value = Number($('#' + obj.name + '_total').text());
					if (value < obj.min || value > obj.max) {
						alert('aa');
						return false;
					}
					return true;
				})

			})

			$("#box-combinado_familiar_domicilio_calle").focus();
			break;
		case 'integral_comercio':
			$('fieldset.optional').each(function (i, e) {
				$(e).prop('disabled', !$(e).children().first().children().first().prop('checked'));
			});
			$('.toggle-fieldset').change(function () {
				$(this).parent().parent().prop('disabled', !$(this).prop('checked')).children('p').first().children().eq(1).focus();
			})
			populateSectionBienesDeUso(id);
			
		}
	}
	populateSectionAsegurado = function (id) {
		populateListAseguradoActividad('box-accidentes_asegurado_actividad');
		populateDiv_Asegurado(id);

		$("#box-accidentes_asegurado_suma_asegurada, #box-accidentes_asegurado_gastos_medicos").change(function () {
			var suma_asegurada = isNaN($("#box-accidentes_asegurado_suma_asegurada").val()) ? 0 : $("#box-accidentes_asegurado_suma_asegurada").val();
			if ($(this).prop('id') == 'box-accidentes_asegurado_suma_asegurada' && $("#box-accidentes_asegurado_gastos_medicos").val() == '') {
				$("#box-accidentes_asegurado_gastos_medicos").val(Number(suma_asegurada) * 0.1);
			}
			var gastos_medicos = isNaN($("#box-accidentes_asegurado_gastos_medicos").val()) ? 0 : $("#box-accidentes_asegurado_gastos_medicos").val();
			$("#box-accidentes_asegurado_total").val(Number(suma_asegurada) + Number(gastos_medicos));
		});
		$("#box-accidentes_asegurado_beneficiario_cargar").change(function () {
			$("#box-accidentes_asegurado_beneficiario_nombre, #box-accidentes_asegurado_beneficiario_documento, #box-accidentes_asegurado_beneficiario_nacimiento").prop('disabled', !($(this).prop('checked')));
			$("#box-accidentes_asegurado_beneficiario_nombre").focus();
		});
		$("#btnBoxAsegurado, #btnBoxAseguradoReset").button();
		var validateForm = $("#frmBoxAsegurado").validate({
			rules: {
				"box-accidentes_asegurado_nombre": {
					required: true
				},
				"box-accidentes_asegurado_documento": {
					required: true
				},
				"box-accidentes_asegurado_nacimiento": {
					required: true,
					dateAR: true
				},
				"box-accidentes_asegurado_actividad": {
					required: true
				},
				"box-accidentes_asegurado_suma_asegurada": {
					required: true,
					number: true
				},
				"box-accidentes_asegurado_gastos_medicos": {
					required: true,
					number: true
				},
				"box-accidentes_asegurado_beneficiario": {
					required: true
				},
				"box-accidentes_asegurado_beneficiario_nombre": {
					required: function () {
						return $("#box-accidentes_asegurado_beneficiario_cargar").prop('checked')
					}
				},
				"box-accidentes_asegurado_beneficiario_documento": {
					required: function () {
						return $("#box-accidentes_asegurado_beneficiario_cargar").prop('checked')
					}
				},
				"box-accidentes_asegurado_beneficiario_nacimiento": {
					required: function () {
						return $("#box-accidentes_asegurado_beneficiario_cargar").prop('checked')
					},
					dateAR: true
				},

			}
		});
		$("#btnBoxAsegurado").click(function () {
			if (validateForm.form()) {
				$('#frmBoxAsegurado .box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
				if ($("#box-action").val() == 'insert') {
					insertFormAsegurado(id);
				} else {
					updateFormAsegurado(id);
				}
			};
			$('#frmBoxAsegurado .box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
		});
	}
	populateSectionClausula = function (id) {
		populateDiv_Clausula(id);

		$("#btnBoxClausula, #btnBoxClausulaReset").button();
		var validateForm = $("#frmBoxClausula").validate({
			rules: {
				"box-accidentes_clausula_nombre": {
					required: true
				},
				"box-accidentes_clausula_cuit": {
					required: true
				},
				"box-accidentes_clausula_domicilio": {
					required: true
				}
			}
		});
		$("#btnBoxClausula").click(function () {
			if (validateForm.form()) {
				if ($("#box-action").val() == 'insert') {
					insertFormClausula(id);
				} else {
					updateFormClausula(id);
				}
			};
		});
	}
	populateSectionTvAudVid = function (id) {
		populateDiv_TvAudVid(id);

		calculateTvAudVidTotal();
		$('#box-combinado_familiar_tv_aud_vid_add').button().click(function () {
			addTvAudVidItem();
		})
	}
	populateSectionObjEspProrrata = function (id) {
		populateDiv_ObjEspProrrata(id);

		calculateObjEspProrrataTotal();
		$('#box-combinado_familiar_obj_esp_prorrata_add').button().click(function () {
			addObjEspProrrataItem();
		})
	}
	populateSectionEquiposComputacion = function (id) {
		populateDiv_EquiposComputacion(id);

		calculateEquiposComputacionTotal();
		$('#box-combinado_familiar_equipos_computacion_add').button().click(function () {
			addEquiposComputacionItem();
		})
	}
	populateSectionFilmFoto = function (id) {
		populateDiv_FilmFoto(id);

		calculateFilmFotoTotal();
		$('#box-combinado_familiar_film_foto_add').button().click(function () {
			addFilmFotoItem();
		})
	}
	populateSectionAutomotorAccesorios = function (id) {
		populateDiv_AutomotorAccesorios(id);

		calculateAutomotorAccesoriosTotal();
		$('#box-automotor_accesorios_add').button().click(function () {
			addAutomotorAccesorios();
		})
	}
	populateSectionBienesDeUso = function (id) {
		populateDiv_BienesDeUso(id);

		calculateBienesDeUsoTotal();
		$('#box-integral_comercio_bienes_de_uso_add').button().click(function () {
			addBienesDeUsoItem();
		})
	}
	
	addTvAudVidItem = function (cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#tv_aud_vid p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var tv_aud_vid = '<p id="' + j + '"><input type="number" name="box-combinado_familiar_tv_aud_vid[' + j + '][cantidad]" class="box-combinado_familiar_tv_aud_vid_cant" placeholder="Cant" style="width:40px" value="' + (cantidad ? cantidad : '') + '" /> <input type="text" name="box-combinado_familiar_tv_aud_vid[' + j + '][producto]" placeholder="Producto" value="' + (producto ? producto : '') + '" /> <input type="text" name="box-combinado_familiar_tv_aud_vid[' + j + '][marca]" placeholder="Marca" value="' + (marca ? marca : '') + '" /> <input type="text" name="box-combinado_familiar_tv_aud_vid[' + j + '][serial]" placeholder="Nro Serie" value="' + (serial ? serial : '') + '" /> <input type="number" name="box-combinado_familiar_tv_aud_vid[' + j + '][valor]" class="box-combinado_familiar_tv_aud_vid_valor" placeholder="Valor" style="width:80px" value="' + (valor ? valor : '') + '" /> <input type="button" class="box-combinado_familiar_tv_aud_vid_remove" value="-" /></p>';
		$('#tv_aud_vid').append(tv_aud_vid);
		$('#tv_aud_vid p#' + j + ' :nth-child(1)').focus();
		$('.box-combinado_familiar_tv_aud_vid_remove').button().click(function () {
			$(this).parent().remove();
			calculateTvAudVidTotal();
		})
		$('.box-combinado_familiar_tv_aud_vid_valor, .box-combinado_familiar_tv_aud_vid_cant').change(function () {
			calculateTvAudVidTotal()
		});
	}
	addObjEspProrrataItem = function (cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#obj_esp_prorrata p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var obj_esp_prorrata = '<p id="' + j + '"><input type="number" name="box-combinado_familiar_obj_esp_prorrata[' + j + '][cantidad]" class="combinado_familiar_obj_esp_prorrata_cant" placeholder="Cant" style="width:40px" value="' + (cantidad ? cantidad : '') + '" /> <input type="text" name="box-combinado_familiar_obj_esp_prorrata[' + j + '][producto]" placeholder="Producto" value="' + (producto ? producto : '') + '" /> <input type="text" name="box-combinado_familiar_obj_esp_prorrata[' + j + '][marca]" placeholder="Marca" value="' + (marca ? marca : '') + '" /> <input type="text" name="box-combinado_familiar_obj_esp_prorrata[' + j + '][serial]" placeholder="Nro Serie" value="' + (serial ? serial : '') + '" /> <input type="number" name="box-combinado_familiar_obj_esp_prorrata[' + j + '][valor]" class="box-combinado_familiar_obj_esp_prorrata_valor" placeholder="Valor" style="width:80px" value="' + (valor ? valor : '') + '" /> <input type="button" class="box-combinado_familiar_obj_esp_prorrata_remove" value="-" /></p>';
		$('#obj_esp_prorrata').append(obj_esp_prorrata);
		$('#obj_esp_prorrata p#' + j + ' :nth-child(1)').focus();
		$('.box-combinado_familiar_obj_esp_prorrata_remove').button().click(function () {
			$(this).parent().remove();
			calculateObjEspProrrataTotal()
		})
		$('.box-combinado_familiar_obj_esp_prorrata_valor, .combinado_familiar_obj_esp_prorrata_cant').change(function () {
			calculateObjEspProrrataTotal()
		});
	}
	addEquiposComputacionItem = function (cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#equipos_computacion p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var equipos_computacion = '<p id="' + j + '"><input type="number" name="box-combinado_familiar_equipos_computacion[' + j + '][cantidad]" class="box-combinado_familiar_equipos_computacion_cant" placeholder="Cant" style="width:40px" value="' + (cantidad ? cantidad : '') + '" /> <input type="text" name="box-combinado_familiar_equipos_computacion[' + j + '][producto]" placeholder="Producto" value="' + (producto ? producto : '') + '" /> <input type="text" name="box-combinado_familiar_equipos_computacion[' + j + '][marca]" placeholder="Marca" value="' + (marca ? marca : '') + '" /> <input type="text" name="box-combinado_familiar_equipos_computacion[' + j + '][serial]" placeholder="Nro Serie" value="' + (serial ? serial : '') + '" /> <input type="number" name="box-combinado_familiar_equipos_computacion[' + j + '][valor]" class="box-combinado_familiar_equipos_computacion_valor" placeholder="Valor" style="width:80px" value="' + (valor ? valor : '') + '" /> <input type="button" class="box-combinado_familiar_equipos_computacion_remove" value="-" /></p>';
		$('#equipos_computacion').append(equipos_computacion);
		$('#equipos_computacion p#' + j + ' :nth-child(1)').focus();
		$('.box-combinado_familiar_equipos_computacion_remove').button().click(function () {
			$(this).parent().remove();
			calculateEquiposComputacionTotal()
		})
		$('.box-combinado_familiar_equipos_computacion_valor, .box-combinado_familiar_equipos_computacion_cant').change(function () {
			calculateEquiposComputacionTotal()
		});
	}
	addFilmFotoItem = function (cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#film_foto p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var film_foto = '<p id="' + j + '"><input type="number" name="box-combinado_familiar_film_foto[' + j + '][cantidad]" class="box-combinado_familiar_film_foto_cant" placeholder="Cant" style="width:40px" value="' + (cantidad ? cantidad : '') + '" /> <input type="text" name="box-combinado_familiar_film_foto[' + j + '][producto]" placeholder="Producto" value="' + (producto ? producto : '') + '" /> <input type="text" name="box-combinado_familiar_film_foto[' + j + '][marca]" placeholder="Marca" value="' + (marca ? marca : '') + '" /> <input type="text" name="box-combinado_familiar_film_foto[' + j + '][serial]" placeholder="Nro Serie" value="' + (serial ? serial : '') + '" /> <input type="number" name="box-combinado_familiar_film_foto[' + j + '][valor]" class="box-combinado_familiar_film_foto_valor" placeholder="Valor" style="width:80px" value="' + (valor ? valor : '') + '" /> <input type="button" class="box-combinado_familiar_film_foto_remove" value="-" /></p>';
		$('#film_foto').append(film_foto);
		$('#film_foto p#' + j + ' :nth-child(1)').focus();
		$('.box-combinado_familiar_film_foto_remove').button().click(function () {
			$(this).parent().remove();
			calculateFilmFotoTotal()
		})
		$('.box-combinado_familiar_film_foto_valor, .box-combinado_familiar_film_foto_cant').change(function () {
			calculateFilmFotoTotal()
		});
	}
	addAutomotorAccesorios = function (cantidad, detalle, valor, focus) {
		if (focus == undefined) {
			focus = true;
		}
		var j = 0;
		$('#automotor_accesorios p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var automotor_accesorio = '<p id="' + j + '"><input type="number" name="box-automotor_accesorio[' + j + '][cantidad]" class="box-automotor_accesorio_cant" placeholder="Cant" style="width:40px" value="' + (cantidad ? cantidad : '') + '" /> <input type="text" name="box-automotor_accesorio[' + j + '][detalle]" placeholder="Detalle" value="' + (detalle ? detalle : '') + '" style="width:230px" /> <input type="number" name="box-automotor_accesorio[' + j + '][valor]" class="box-automotor_accesorio_valor" placeholder="Valor" style="width:80px" value="' + (valor ? valor : '') + '" step="any" /> <input type="button" class="box-automotor_accesorio_remove" value="-" /></p>';
		$('#automotor_accesorios').append(automotor_accesorio);
		if (focus == true) {
			$('#automotor_accesorios p#' + j + ' :nth-child(1)').focus();
		}
		$('.box-automotor_accesorio_remove').button().click(function () {
			$(this).parent().remove();
			calculateAutomotorAccesoriosTotal()
		})
		$('.box-automotor_accesorio_valor, .box-automotor_accesorio_cant').change(function () {
			calculateAutomotorAccesoriosTotal()
		});
	}
	calculateTvAudVidTotal = function () {
		var total = 0;
		$('.box-combinado_familiar_tv_aud_vid_valor').each(function (i, e) {
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#tv_aud_vid_total").html(total);
	}
	calculateObjEspProrrataTotal = function () {
		var total = 0;
		$('.box-combinado_familiar_obj_esp_prorrata_valor').each(function (i, e) {
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#obj_esp_prorrata_total").html(total);
	}
	calculateEquiposComputacionTotal = function () {
		var total = 0;
		$('.box-combinado_familiar_equipos_computacion_valor').each(function (i, e) {
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#equipos_computacion_total").html(total);
	}
	calculateFilmFotoTotal = function (id) {
		var total = 0;
		$('.box-combinado_familiar_film_foto_valor').each(function (i, e) {
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#film_foto_total").html(total);
	}
	calculateAutomotorAccesoriosTotal = function () {
		var total = 0;
		$('.box-automotor_accesorio_valor').each(function (i, e) {
			total += (Number($(e).val()) * Number($(e).prev().prev().val()));
		});
		$("#automotor_accesorios_total").html(total);
		$("#box-valor_accesorios").val(total);
		calculateTotal();
	}
	populateFormBoxAsegurado = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_accidentes_asegurado.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormBoxPayCuota = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_pay_cuota.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	setCuotaToPrint = function(recibo, cuota) {
		$('#recibo-id').text(recibo);
		$('#cuota-id').val(cuota);
		$('#email').focus();
		$('#btnBox1, #btnVerPDF').button('option', 'disabled', false);
	}
	populateFormBoxSegCob = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_segcob.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	populateFormBoxContacto = function (id) {
		var dfd = new $.Deferred();
		$.ajax({
			url: "get-json-fich_contacto1.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
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
	addBienesDeUsoItem = function (cantidad, producto, marca, serial, valor) {
		var j = 0;
		$('#bienes_de_uso p').each(function (i, e) {
			j = Math.max(Number($(e).attr('id')), j) + 1;
		});
		var bienes_de_uso = '<p id="' + j + '"><input type="number" name="box-integral_comercio_bienes_de_uso[' + j + '][cantidad]" class="box-integral_comercio_bienes_de_uso_cant" placeholder="Cant" style="width:40px" value="' + (cantidad ? cantidad : '') + '" /> <input type="text" name="box-integral_comercio_bienes_de_uso[' + j + '][producto]" placeholder="Producto" value="' + (producto ? producto : '') + '" /> <input type="text" name="box-integral_comercio_bienes_de_uso[' + j + '][marca]" placeholder="Marca" value="' + (marca ? marca : '') + '" /> <input type="text" name="box-integral_comercio_bienes_de_uso[' + j + '][serial]" placeholder="Nro Serie" value="' + (serial ? serial : '') + '" /> <input type="number" name="box-integral_comercio_bienes_de_uso[' + j + '][valor]" class="box-integral_comercio_bienes_de_uso_valor" placeholder="Valor" style="width:80px" value="' + (valor ? valor : '') + '" /> <input type="button" class="box-integral_comercio_bienes_de_uso_remove" value="-" /></p>';
		$('#bienes_de_uso').append(bienes_de_uso);
		$('#bienes_de_uso p#' + j + ' :nth-child(1)').focus();
		$('.box-integral_comercio_bienes_de_uso_remove').button().click(function () {
			$(this).parent().remove();
			calculateBienesDeUsoTotal();
		})
		$('.box-integral_comercio_bienes_de_uso_valor, .box-integral_comercio_bienes_de_uso_cant').change(function () {
			calculateBienesDeUsoTotal()
		});
	}
	calculateBienesDeUsoTotal = function () {
		var total = 0;
		$('.box-integral_comercio_bienes_de_uso_valor').each(function (i, e) {
			total += (Number($(e).val()) * Number($(e).prev().prev().prev().prev().val()));
		});
		$("#bienes_de_uso_total").html(total);
	}
	croquisAddItem = function(type, i, data) {
		data = data || false;
		switch (type) {
		case 'auto':
			if (data) {
				var pos = data.split('X');
				$('#auto'+i).appendTo($('#droppable')).css({'position': 'absolute', 'top': pos[0]+'px', 'left': pos[1]+'px'});
			}
			else {
				var auto = '<div id="auto'+i+'" class="draggable-autos croquis-auto" style="width:25px;height:13px;border:solid 1px black;background-color:white;text-align:center;cursor:move">' + (i+1) + '</div>';
				auto = '<div style="float:left;width:25px;padding:4px">'+auto+'</div>';
				$('#croquis-autos').append(auto);
			}
			$('.draggable-autos').draggable();
			break;
		case 'moto':
			if (data) {
				var pos = data.split('X');
				var moto = '<div class="croquis-moto dragged" style="width:20px;height:9px;background: center no-repeat url(\'siniestros/croquis/moto.png\');background-size:20px 9px"></div>';
				$(moto).appendTo($('#droppable')).css({'position': 'absolute', 'top': pos[0]+'px', 'left': pos[1]+'px'}).draggable();
			}
			break;
		case 'peat':
			if (data) {
				var pos = data.split('X');
				var peaton = '<div class="croquis-peaton dragged" style="width:9px;height:9px;background: center no-repeat url(\'siniestros/croquis/peaton.png\');background-size:9px 9px"></div>';
				$(peaton).appendTo($('#droppable')).css({'position': 'absolute', 'top': pos[0]+'px', 'left': pos[1]+'px'}).draggable();
			}
			break;
		case 'dire':
			if (data) {
				data = data.split(',');
				var dir = data[0].toLowerCase();
				var pos = data[1].split('X');
				var direccion = '<div class="croquis-direccion dragged" style="width:16px;height:15px;background: center no-repeat url(\'siniestros/croquis/'+dir+'.png\');background-size:16px 15px" direction="'+dir+'"></div>';
				$(direccion).appendTo($('#droppable')).css({'position': 'absolute', 'top': pos[0]+'px', 'left': pos[1]+'px'}).draggable();
			}
			break;
		}
	}
	calculateCajaDiaria = function() {
		var totalingresos = parseFloat($('#totalIngresosSistema').text()) + parseFloat($('#totalIngresos').text());
		var totalegresos = parseFloat($('#totalEgresos').text());
		var saldo = totalingresos - totalegresos;
		var cierre = parseFloat($('#box-caja_diaria_cierre').val()) || parseFloat(0);
		var sobre = saldo - cierre;
		var arrastreanterior = parseFloat($('#box-caja_arrastre_anterior').val()) || parseFloat(0);
		var arrastre = arrastreanterior + saldo;
		
		$('#box-caja_ingresos').val(parseFloat(totalingresos).toFixed(2));
		$('#box-caja_egresos').val(parseFloat(totalegresos).toFixed(2));
		$('#box-caja_saldo').val(parseFloat(saldo).toFixed(2));
		$('#box-caja_sobre').val(parseFloat(sobre).toFixed(2));
		$('#box-caja_arrastre_total').val(parseFloat(arrastre).toFixed(2));
	}
	
	/* Other form functions */
	assignClientToPoliza = function (id) {
		$.ajax({
			url: "get-json-fich_poliza-cliente_nombre.php?id=" + id,
			dataType: 'json',
			success: function (j) {
				if (j.error == 'expired') {
					sessionExpire('box');
				} else {
					if (j.empty != true) {
						// Populate main form
						$('#box-cliente_nombre').val(j.cliente_nombre);
						$('#box-cliente_id').val(j.cliente_id);
						$("#box-sucursal_id").val(j.sucursal_id);
						// Clear search form
						$('#frmSelectClient').each(function () {
							this.reset();
						});
						// Clear search results
						$('#divBoxClienteSearchResults').html('');
						// Enable main form
						formDisable('frmBox', 'ui', false);
						$('#box-sucursal_pfc, #box-poliza_flota').attr('disabled', true);
						// Set focus
						$("#box-sucursal_id").focus();
					}
				}
			}
		});
	}
	assignPolizaToEndoso = function (id, poliza_numero) {
		$("#box-poliza_numero").val(poliza_numero);
		$("#box-poliza_id").val(id);

		// Clear search form
		$('#frmSelectPoliza').each(function () {
			this.reset();
		});
		// Clear search results
		$('#divBoxPolizaSearchResults').html('');
		// Enable main form
		formDisable('frmBox', 'ui', false);
		// Set focus
		$("#box-endoso_tipo_id").focus();
	}
	assignPolizaToSiniestro = function (id, productor_seguro_codigo) {
		$("#box-automotor_id").val(id);
		$("#box-productor_seguro_codigo").val(productor_seguro_codigo);
		var d = new Date();
		var date = ('0' + d.getDate()).slice(-2) + '/'
				+ ('0' + (d.getMonth()+1)).slice(-2) + '/'
				+ d.getFullYear();
		$('#box-fecha_denuncia').val(date);
		$('#box-hora_denuncia').val(('0' + d.getHours()).slice(-2)+':'+('0' + d.getMinutes()).slice(-2));
		
		// Clear search form
		$('#frmSelectPoliza').each(function () {
			this.reset();
		});
		// Clear search results
		$('#divBoxPolizaSearchResults').html('');
		// Enable main form
		formDisable('frmBox', 'ui', false);
		// Set focus
		$("#box-lugar_denuncia").focus();
	}

	/* Populate DIV functions */
	populateDiv_Prod_Info = function (id) {
		$.getJSON("get-json-prod_info.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
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
	populateDiv_ProdSeg = function (id) {
		$.getJSON("get-json-fich_prodseg.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {
					// Sort data
					j.sort(function (a, b) {
						return a.seguro_nombre == b.seguro_nombre ? 0 : a.seguro_nombre < b.seguro_nombre ? -1 : 1;
					});
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
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.seguro_nombre + '</td>';
						result += '<td>' + object.sucursal_nombre + '</td>';
						result += '<td><span class="jeditrow1" id="prodseg_' + object.productor_seguro_id + '">' + object.productor_seguro_codigo + '</span></td>';
						result += '<td><span onClick="javascript:deleteProdSeg(' + object.productor_seguro_id + ', ' + id + ')" style="cursor: pointer;" class="ui-icon ui-icon-trash" title="Eliminar"></span></td>';
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
	populateDiv_Cliente_Info = function (id) {
		$.getJSON("get-json-cliente_info.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
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
					result += '<td><strong>Nombre:</strong> <a title="Ir a Cliente" href="#" onclick="openBoxModCliente(\'' + j.cliente_id + '\')">' + j.cliente_nombre + '</a></td>';
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
	populateDiv_Seguro_Info = function (id) {
		$.getJSON("get-json-seg_info.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
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
					result += '<td><strong>Nombre:</strong> ' + j.seguro_nombre + '</td>';
					result += '<td><strong>CUIT:</strong> ' + j.seguro_cuit + '</td>';
					// Close Row and Table
					result += '</tr>';
					result += '</table>';
					// Populate DIV
					$('#divBoxInfo').html(result);
				}
			}
		});
	}
	

	populateDiv_Contacto = function (id) {
		$.getJSON("get-json-fich_contacto.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {
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
					$.each(j, function (i, object) {
						result += '<tr>';
						if (object.contacto_default == 1) {
							result += '<td><strong>X</strong></td>';
						} else {
							result += '<td>&nbsp;</td>';
						}
						result += '<td>' + object.contacto_tipo + '</td>';
						result += '<td>' + object.contacto_domicilio + '</td>';
						result += '<td>' + object.contacto_nro + '</td>';
						result += '<td>' + object.contacto_piso + '</td>';
						result += '<td>' + object.contacto_dpto + '</td>';
						result += '<td>' + object.localidad_nombre + '</td>';
						result += '<td>' + object.contacto_country + '</td>';
						result += '<td>' + object.contacto_lote + '</td>';
						result += '<td>' + object.localidad_cp + '</td>';
						result += '<td>' + object.contacto_telefono1 + '</td>';
						result += '<td>' + object.contacto_telefono2 + '</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Editar" onClick="javascript:editInBoxContacto(' + object.contacto_id + ');"><span class="ui-icon ui-icon-search"></span></li>';
						result += '<li title="Eliminar" onClick="javascript:deleteContacto(' + object.contacto_id + ', ' + id + ');"><span class="ui-icon ui-icon-trash"></span></li>';
						if (object.contacto_default == 0) {
							result += '<li title="Establecer por defecto" onClick="javascript:updateLinkContacto_Default(' + object.contacto_id + ', ' + id + ');"><span class="ui-icon ui-icon-star"></span></li>';
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
	populateDiv_Polizas = function (id) {
		$.getJSON("get-json-fich_cliepoli.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {

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
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.poliza_numero + '</td>';
						result += '<td>' + object.subtipo_poliza_nombre + '</td>';
						result += '<td><span title="' + object.poliza_al_dia_detalle + '">' + object.poliza_al_dia + '</span></td>';
						result += '<td><span onClick="openBoxPolizaDet(' + object.poliza_id + ')" style="cursor: pointer;" class="ui-icon ui-icon-extlink" title="Ir a Póliza"></span></td>';
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
	populateDiv_Poliza_Info = function (id, tipo) {
		tipo = tipo || 'poliza';
		$.getJSON("get-json-poliza_info.php?id=" + id + "&tipo=" + tipo, {}, function (j) {
			if (j.error == 'expired') {
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
					result += '<tr><td><strong>Teléfonos:</strong> ' + j.cliente_telefonos + '</td></tr>';
					result += '<tr><td><strong>Domicilio:</strong> ' + j.cliente_domicilio + '</td></tr>';
					result += '<tr><td><strong>Compañía:</strong> ' + j.seguro_nombre + '</td></tr>';
					result += '<tr><td><strong>Productor:</strong> ' + j.productor_nombre + '</td></tr>';
					result += '<tr><td><strong>Poliza Nº:</strong> ' + (j.poliza_numero == '' ? '-' : j.poliza_numero) + '</td></tr>';
					result += '<tr><td><strong>Detalle de póliza: </strong> ' + j.detalle_poliza + '</td></tr>';
					// Close Row and Table
					result += '</tr>';
					result += '</table>';
					// Populate DIV
					$('#divBoxInfo').html(result);
				}
			}
		});
	}
	populateDiv_Fotos = function (section, id, divsuffix) {
		divsuffix = divsuffix || '';
		$.getJSON("get-json-" + section + "_fotos.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
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
					$.each(j, function (i, object) {


						result += '<td align="center" class="ui-state-default ui-corner-all" style="width:100px;height:115px;overflow: hidden;white-space: nowrap"><a href="' + object.foto_url + '" target="_blank"><img width="100" height="100" style="vertical-align:middle;" src="' + object.foto_thumb_url + '" /></a>';
						result += '<br />';
						result += '<span style="float:right"><ul class="dtInlineIconList ui-widget ui-helper-clearfix"><li title="Abrir en nueva ventana" onclick="window.open(\'' + object.foto_url + '\');"><span class="ui-icon ui-icon-newwin"></span></li><li title="Eliminar" onclick="deleteViaLink(\'' + section + '_foto\', \'' + object.foto_id + '\');$(\'#divShowFoto' + divsuffix + '\').hide();populateDiv_Fotos(\'' + section + '\', ' + id + ', \'' + divsuffix + '\');"><span class="ui-icon ui-icon-trash"></span></li></ul></span>';
						result += '</td>';
					});
					// Close Table
					result += '</tr></tbody></table>';
					// Populate DIV
					$('#divBoxFotos' + divsuffix).html(result);
					if (j != '') {
						$('#divBoxFotos' + divsuffix).show();
					} else {
						$('#divBoxFotos' + divsuffix).hide();
					}
				}
			}
		});
	}
	populateDiv_Asegurado = function (id) {
		$.getJSON("get-json-fich_accidentes_asegurados.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {
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
					$.each(j, function (i, object) {
						totalsuma += Number(object.accidentes_asegurado_suma_asegurada);
						totalmed += Number(object.accidentes_asegurado_gastos_medicos);
						count++;
						result += '<tr>';
						result += '<td>' + object.accidentes_asegurado_nombre + '</td>';
						result += '<td>' + object.accidentes_asegurado_documento + '</td>';
						result += '<td>' + object.asegurado_actividad_nombre.substr(0, 15) + (object.asegurado_actividad_nombre.length > 15 ? '...' : '') + '</td>';
						result += '<td>' + object.accidentes_asegurado_legal + '</td>';
						result += '<td>' + formatNumber(object.accidentes_asegurado_suma_asegurada) + '</td>';
						result += '<td>' + formatNumber(object.accidentes_asegurado_gastos_medicos) + '</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Editar Asegurado" onClick="javascript:editInBoxAsegurado(' + object.accidentes_asegurado_id + ');"><span class="ui-icon ui-icon-search"></span></li>';
						result += '<li title="Eliminar Asegurado" onClick="javascript:deleteAccidentesAsegurado(' + object.accidentes_asegurado_id + ', ' + id + ');"><span class="ui-icon ui-icon-trash"></span></li>';
						result += '</ul></td>';
						result += '</tr>';
					});
					result += '<tr><td><strong>Total (' + count + ')</strong></td><td></td><td></td><td></td><td><strong>' + formatNumber(Number(totalsuma).toFixed(2)) + '</strong></td><td><strong>' + formatNumber(Number(totalmed).toFixed(2)) + '</strong></td><td></td></tr>';
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
	populateDiv_Clausula = function (id) {
		$.getJSON("get-json-fich_accidentes_clausulas.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {
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
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.accidentes_clausula_nombre + '</td>';
						result += '<td>' + object.accidentes_clausula_cuit + '</td>';
						result += '<td>' + object.accidentes_clausula_domicilio + '</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Eliminar Asegurado" onClick="javascript:deleteAccidentesClausula(' + object.accidentes_clausula_id + ', ' + id + ');"><span class="ui-icon ui-icon-trash"></span></li>';
						result += '</ul></td>';
						result += '</tr>';
						count++;
					});
					result += '<tr><td><strong>Total: ' + count + '</strong></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
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
	populateDiv_TvAudVid = function (id) {
		$.getJSON("get-json-fich_combinado_familiar_tvaudvid.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				$('#tv_aud_vid').empty();
				$.each(j, function (i, object) {
					addTvAudVidItem(object.combinado_familiar_tv_aud_vid_cantidad, object.combinado_familiar_tv_aud_vid_producto, object.combinado_familiar_tv_aud_vid_marca, object.combinado_familiar_tv_aud_vid_serial, object.combinado_familiar_tv_aud_vid_valor);
				});
				calculateTvAudVidTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_ObjEspProrrata = function (id) {
		$.getJSON("get-json-fich_combinado_familiar_objespprorrata.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				$('#obj_esp_prorrata').empty();
				$.each(j, function (i, object) {
					addObjEspProrrataItem(object.combinado_familiar_obj_esp_prorrata_cantidad, object.combinado_familiar_obj_esp_prorrata_producto, object.combinado_familiar_obj_esp_prorrata_marca, object.combinado_familiar_obj_esp_prorrata_serial, object.combinado_familiar_obj_esp_prorrata_valor);
				});
				calculateObjEspProrrataTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_EquiposComputacion = function (id) {
		$.getJSON("get-json-fich_combinado_familiar_equiposcomputacion.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				$('#equipos_computacion').empty();
				$.each(j, function (i, object) {
					addEquiposComputacionItem(object.combinado_familiar_equipos_computacion_cantidad, object.combinado_familiar_equipos_computacion_producto, object.combinado_familiar_equipos_computacion_marca, object.combinado_familiar_equipos_computacion_serial, object.combinado_familiar_equipos_computacion_valor);
				});
				calculateEquiposComputacionTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_FilmFoto = function (id) {
		$.getJSON("get-json-fich_combinado_familiar_filmfoto.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				$('#film_foto').empty();
				$.each(j, function (i, object) {
					addFilmFotoItem(object.combinado_familiar_film_foto_cantidad, object.combinado_familiar_film_foto_producto, object.combinado_familiar_film_foto_marca, object.combinado_familiar_film_foto_serial, object.combinado_familiar_film_foto_valor);
				});
				calculateFilmFotoTotal();
				$("#box-combinado_familiar_domicilio_calle").focus();
			}
		})
	}
	populateDiv_Cliente_Results = function () {
		$.getJSON("get-json-fich_poliza-cliente_search.php", $("#frmSelectClient").serialize(), function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					$('#divBoxClienteSearchResults').html('Cliente no encontrado. Intente nuevamente.');
				} else {
					var result = '';
					/* Open Table and Row */
					result += '<table class="tblBox2">';
					$.each(j, function (i, object) {
						result += '<tr>';
						/* Table Data */
						result += '<td>' + object.cliente_nombre + '</td>';
						result += '<td><strong>Documento:</strong> ' + object.cliente_tipo_doc + ' ' + object.cliente_nro_doc + '</td>';
						result += '<td><a href="javascript:assignClientToPoliza(' + object.cliente_id + ')">SELECCIONAR</a></td>';
						/* Close Row and Table */
						result += '</tr>';
					});
					result += '</table>';
					$('#divBoxClienteSearchResults').html(result);
				}
			}
		});
	}
	populateDiv_Cuotas = function (id) {
		$.getJSON("get-json-fich_cuota.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// General variables
					var total = 0;
					var pagado = 0;
					var a_pagar = 0;
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
					result += '<th>Pagado</th>';
					result += '<th>Recibo</th>';
					result += '<th>PFC</th>';
					result += '<th>Acc.</th>';
					result += '</tr>';
					// Table Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td height="21">' + object.cuota_nro + '</td>';
						result += '<td>' + object.cuota_periodo + '</td>';
						result += '<td>' + object.cuota_monto + '</td>';
						result += '<td><span class="jeditrow2" id="vencimiento_' + object.cuota_id + '">' + object.cuota_vencimiento + '</span></td>';
						result += '<td>' + object.cuota_estado_nombre + '</td>';
						result += '<td>' + object.cuota_fe_pago + '</td>';
						result += '<td>' + (object.cuota_estado_nombre=='Pagado'?object.cuota_monto:'') + '</td>';
						result += '<td>' + object.cuota_recibo + '</td>';
						result += '<td>' + (object.cuota_pfc==1?'Sí':'&nbsp;') + '</td>';
						result += '<td>';
						switch (object.cuota_estado_nombre) {
						case 'Pagado':
							result += '<span onClick="javascript:window.open(\'print-cuota.php?id=' + object.cuota_id + '&print\');" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-print" title="Imprimir"></span>';
							result += '<span onClick="setCuotaToPrint(' + object.cuota_recibo + ', ' + object.cuota_id + ');" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-mail-closed" title="Enviar por email"></span>';
							if (object.master) {
								result += '<sapn onclick="updateCuotaAnular(' + object.cuota_id + ', ' + id + ')" style="cursor:pointer;display:inline-block" class="ui-icon ui-icon-close" title="Anular"></span>';
							}
							pagado += parseFloat(object.cuota_monto);
							break;
						case 'No Pagado':
						case 'Anulado':
							result += '<a href="#" onclick="openBoxPayCuota(' + id + ', ' + object.cuota_id + ')">PAGAR</a>';
							a_pagar += parseFloat(object.cuota_monto);
							break;
						}
						result += '</td>';
						result += '</tr>';
						total += object.cuota_pfc==1?0:parseFloat(object.cuota_monto);
					});
					result += '<tr>';
					result += '<td colspan="3" style="text-align:center	"><b>Premio total: '+parseFloat(total).toFixed(2)+'</b></td>';
					result += '<td colspan="4" style="text-align:right"><b>Total pagado: '+parseFloat(pagado).toFixed(2)+'</b></td>';
					result += '<td colspan="3" style="text-align:right"><b>Total a pagar: '+parseFloat(a_pagar).toFixed(2)+'</b></td>';
					result += '</tr>'
					// Close Table
					result += '</table>';
					// Populate DIV
					$('#divBoxList').html(result);
				}
			}
		});
	}
	populateDiv_Poliza_Results = function (tipo) {
		$.getJSON("get-json-fich_poliza_search.php", $("#frmSelectPoliza").serialize(), function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					$('#divBoxPolizaSearchResults').html('Póliza no encontrada. Intente nuevamente.');
				} else {
					var result = '';
					/* Open Table and Row */
					result += '<table class="tblBox" style="border-spacing:0">';
					$.each(j, function (i, object) {
						switch (object.highlight) {
						case '0':
							result += '<tr>';
							break;
						case '1':
							result += '<tr style="background-color:#b0dfaa">';
							break;
						case '2':
							result += '<tr style="background-color:red">';
							break;
						}
						result += '<td>' + object.cliente_nombre + '</td>';
						result += '<td>' + object.patente + '</td>';
						result += '<td>' + object.poliza_numero + '</td>';
						result += '<td>' + object.validez + '</td>';
						result += '<td>' + object.seguro_nombre + '</td>';
						var f = '';
						switch (tipo) {
						case 'endoso':
							f = 'assignPolizaToEndoso('+object.poliza_id+', \'' + object.poliza_numero + '\')';
							break;
						case 'recibo':
							f = '$.colorbox.settings.onClosed=function(){openBoxEmitirRecibo('+object.cliente_id+')};openBoxCuota('+object.poliza_id+')';
							break;
						case 'siniestro':
							result += '<td>' + object.patente + '</td>';
							f = 'assignPolizaToSiniestro('+object.automotor_id+', '+object.productor_seguro_codigo+')';
							break;
						}
						result += '<td style="text-align:right"><a href="javascript:'+f+'">SELECCIONAR</a></td>';
						result += '</tr>';
					});
					result += '</table>';
					$('#divBoxPolizaSearchResults').html(result);
				}
			}
		});
	}
	populateDiv_Endosos = function (id, poliza_numero) {
		$.getJSON("get-json-fich_poliendosos.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {

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
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.endoso_fecha_pedido + '</td>';
						result += '<td>' + object.endoso_fecha_compania + '</td>';
						result += '<td><span title="' + object.endoso_tipo + '">' + object.endoso_tipo + '</span></td>';
						result += '<td>' + object.endoso_completo + '</td>';
						result += '<td><span onClick="openBoxModEndoso(' + object.endoso_id + ')" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-extlink" title="Ir al endoso"></span>';
						result += '<span onclick="$.when(deleteViaLink(\'endoso\','+object.endoso_id+')).then(function(){populateDiv_Endosos('+id+', '+poliza_numero+');});" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-trash" title="Eliminar"></span></td>';
						result += '</tr>';
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'La póliza no posee endosos.';
				}
				// Populate DIV
				$('#divBoxList').html(result);
				$('#btnNuevoEndoso').click(function() {
					if (poliza_numero) {
						$.when(openBoxAltaEndoso()).then(function() {
							assignPolizaToEndoso(id, poliza_numero);
							
						});
					}
				})
			}
		});
	}
	populateDiv_Envios = function(type, id, suffix) {
		suffix = suffix || '';
		$.getJSON("get-json-fich_envios.php?type="+encodeURIComponent(type)+"&id=" + id, {}, function (j) {
			if (j.error == 'expired') {
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
					result += '<th>Fecha</th>';
					result += '<th>Usuario</th>';
					result += '<th>Destino emails</th>';
					result += '<th>';
					switch (type) {
					case '6':
						result += 'Recibo';
						break;
					}
					result += '</th>';
					result += '<th>Tipo</th>';
					result += '</tr>';
					// Table Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.email_log_timestamp + '</td>';
						result += '<td>' + object.usuario_usuario + '</td>';
						result += '<td><span title="'+object.email_log_to+'">' + object.email_log_to.slice(0, 50) + (object.email_log_to.length>50?'...':'') + '</span></td>';
						result += '<td>' + object.email_log_desc + '</td>';
						result += '<td>' + object.email_type_name + '</td>';
						result += '<td>';
						if (object.cuota_nro == 1) {
							if (object.cuota_pfc == 1) {
								result += '<span onClick="javascript:updateLinkCuota_PFC(' + object.cuota_id + ', ' + id + ');" style="cursor: pointer;" class="ui-icon ui-icon-circle-check" title="Cambiar"></span>';
							} else {
								result += '<span onClick="javascript:updateLinkCuota_PFC(' + object.cuota_id + ', ' + id + ');" style="cursor: pointer;" class="ui-icon ui-icon-circle-close" title="Cambiar"></span>';
							}
						} else {
							result += '&nbsp;';
						}
						result += '</td>';
						result += '<td>';

						result += '</td>';
						result += '</tr>';
					});
					// Close Table
					result += '</table>';
					// Populate DIV
					$('#divBoxList'+suffix).html(result);
				}
			}
		});
		
	}
	populateDiv_AutomotorAccesorios = function (id) {
		$.getJSON("get-json-automotor_accesorios.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				$('#automotor_accesorios').empty();
				$.each(j, function (i, object) {
					addAutomotorAccesorios(object.cantidad, object.detalle, object.valor, false);
				});
				calculateAutomotorAccesoriosTotal();
			}
		})
	}
	populateDiv_BienesDeUso = function (id) {
		$.getJSON("get-json-fich_integral_comercio_bienes_de_uso.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				$('#bienes_de_uso').empty();
				$.each(j, function (i, object) {
					addBienesDeUsoItem(object.integral_comercio_bienes_de_uso_cantidad, object.integral_comercio_bienes_de_uso_producto, object.integral_comercio_bienes_de_uso_marca, object.integral_comercio_bienes_de_uso_serial, object.integral_comercio_bienes_de_uso_valor);
				});
				calculateBienesDeUsoTotal();
				$("#box-integral_comercio_domicilio_calle").focus();
			}
		})
	}
	populateDiv_Archivos = function (section, id, divsuffix) {
		divsuffix = divsuffix || '';
		$.getJSON("get-json-" + section + "_archivos.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// General variables
					var result = '';
					$.each(j, function (i, object) {
						result += '<a href="'+object.archivo_url+'" target="_blank">';
						result += object.archivo_nombre;
						result += '</a>';
						result += ' <a href="#" onclick="deleteViaLink(\'' + section + '_archivo\', \'' + object.archivo_id + '\');$(\'#divBoxArchivos' + divsuffix + '\').hide();populateDiv_Archivos(\'' + section + '\', ' + id + ', \'' + divsuffix + '\');"><span class="ui-icon ui-icon-trash" style="display:inline-block;margin-top:2px"></span></span>';
						result += '<br />';
					});
					
					// Populate DIV
					$('#divBoxArchivos' + divsuffix).html(result);
					if (j != '') {
						$('#divBoxArchivos' + divsuffix).show();
					} else {
						$('#divBoxArchivos' + divsuffix).hide();
					}
				}
			}
		});
	}
	editCuotaObservacion = function (id) {
		$.getJSON("get-json-cuota_observacion.php?id=" + id, {}, function (j) {
			var comment = prompt('Ingrese las observaciones', j);
			if (comment) {
				var data = {
					'id': id,
					'comment': comment
				};
				$.post('update-cuota_observacion.php', data);
			}
		});
	}
	populateDiv_CuotasOperaciones = function (id, suffix) {
		$.getJSON("get-json-fich_cuota_log.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// General variables
					var result = '';
					$.each(j, function (i, object) {
						result += '<p>Se '+(object.cuota_log_tipo==1?'emite':'anula')+' recibo '+object.cuota_recibo+' (cuota número '+object.cuota_nro+') por el usuario '+object.usuario_nombre+' el día '+object.dia+' a las '+object.hora+'</p>';
					});
					// Populate DIV
					$('#divBoxList'+suffix).html(result);
				}
			}
		});
	}
	populateDiv_SeguroZonasRiesgo = function (id) {
		$.getJSON("get-json-fich_segurozonasriesgo.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
					$.colorbox.close();
				} else {
					// General variables
					var result = '';
					result += '<table>';
					// Table Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td style="min-width:100px">'+object.seguro_zona_riesgo_nombre+'</td>';
						result += '<td style="min-width:80px"><input class="seguro_zona_riesgo_default_btn" type="radio" name="box-seguro_zona_riesgo_default" value="'+object.seguro_zona_riesgo_id+'"'+(object.seguro_zona_riesgo_default==1?' checked':'')+'> <span>'+(object.seguro_zona_riesgo_default==1?'DEFAULT':'')+'</span></td>';
						
						result += '<td><span onClick="openBoxModZonaRiesgo(' + object.seguro_zona_riesgo_id + ', '+id+')" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-extlink" title="Editar zona de riesgo"></span>';
						result += '<span onclick="$.when(deleteViaLink(\'segurozonariesgo\','+object.seguro_zona_riesgo_id+')).then(function(){populateDiv_SeguroZonasRiesgo('+id+');});" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-trash" title="Eliminar"></span></td>';
						
						result += '</tr>';
					});
					// Populate DIV
					$('#divSeguroZonasRiesgo').html(result);
					$('.seguro_zona_riesgo_default_btn').change(function() {
						$(this).next().text('DEFAULT');
						$(this).parent().parent().siblings().each(function(i,e) {
							$(e).children().eq(1).children().eq(1).text('');
						});
					});
				}
			}
		});
	}
	populateDiv_Siniestros = function(id) {
		$.getJSON("get-json-fich_polisiniestros.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {

					// Open Table
					result += '<table class="tblBox">';
					// Table Head
					result += '<tr>';
					result += '<th width="15%">Fecha</th>';
					result += '<th width="55%">Lugar</th>';
					result += '<th width="20%">Número de siniestro</th>';
					result += '<th width="10%">Acciones</th>';
					result += '</tr>';
					// Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.fecha + '</td>';
						result += '<td>' + object.lugar + '</td>';
						result += '<td>' + object.siniestro_numero + '</td>';
						result += '<td><span onClick="openBoxModSiniestro(' + object.id + ', false, '+id+')" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-extlink" title="Ir al siniestro"></span>';
						result += '<span onclick="$.when(deleteViaLink(\'siniestro\','+object.id+')).then(function(){populateDiv_Siniestros('+id+');});" style="cursor: pointer;display:inline-block" class="ui-icon ui-icon-trash" title="Eliminar"></span></td>';
						result += '</tr>';
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'La póliza no posee siniestros.';
				}
				// Populate DIV
				$('#divBoxList').html(result);
			}
		});
	}
	populateDiv_SiniestroDatosTerceros = function(id) {
		var dfd = new $.Deferred();
		$.getJSON("get-json-fich_siniestros_datos_terceros.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				var count = 0;
				// Check if empty
				if (j.length > 0) {
					// Open Table
					result += '<table class="tblBox">';
					// Table Head
					result += '<tr>';
					result += '<th>N° vehículo</th>';
					result += '<th>Nombre y apellido</th>';
					result += '<th>Patente</th>';
					result += '<th>Acción</th>';
					result += '</tr>';
					// Data
					$.each(j, function (i, object) {
						count++;
						result += '<tr>';
						result += '<td>' + (count+1) + '</td>';
						result += '<td>' + object.nombre + '</td>';
						result += '<td>' + object.patente + '</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Editar datos de tercero" onClick="openBoxModSiniestroDatosTercero(' + object.id + ', '+id+');"><span class="ui-icon ui-icon-search"></span></li>';
						result += '<li title="Eliminar datos de tercero" onClick="$.when(deleteViaLink(\'siniestro_datos_tercero\','+object.id+')).then(function(){populateDiv_SiniestroDatosTerceros('+id+');});"><span class="ui-icon ui-icon-trash"></span></li>';
						result += '</ul></td>';
						result += '</tr>';
					});

					// Close Table
					result += '</table>';
				} else {
					result += 'El siniestro no posee datos de terceros.';
				}
				// Populate DIV
				$('#divBoxListDatosTerceros').html(result);
				$('#croquis-autos').children().remove();
				$('.croquis-auto').remove();
				croquisAddItem('auto', 0);
				for (var i = 1; i <= count; i++) {
					croquisAddItem('auto', i);
				}
			}
			dfd.resolve();
		});
		return dfd.promise();
	}
	populateDiv_SiniestroLesionesTerceros = function(id) {
		$.getJSON("get-json-fich_siniestros_lesiones_terceros.php?id=" + id, {}, function (j) {
			if (j.error == 'expired') {
				sessionExpire('box');
			} else {
				var result = '';
				// Check if empty
				if (j.length > 0) {
					// Open Table
					result += '<table class="tblBox">';
					// Table Head
					result += '<tr>';
					result += '<th>Nombre y apellido</th>';
					result += '<th>DNI</th>';
					result += '<th>Teléfono</th>';
					result += '<th>Acción</th>';
					result += '</tr>';
					// Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td>' + object.nombre + '</td>';
						result += '<td>' + object.nro_doc + '</td>';
						result += '<td>' + object.tel + '</td>';
						result += '<td><ul class="listInlineIcons">';
						result += '<li title="Editar lesiones a tercero" onClick="openBoxModSiniestroLesionesTercero(' + object.id + ', '+id+');"><span class="ui-icon ui-icon-search"></span></li>';
						result += '<li title="Eliminar lesiones a tercero" onClick="$.when(deleteViaLink(\'siniestro_lesiones_tercero\','+object.id+')).then(function(){populateDiv_SiniestroLesionesTerceros('+id+');});"><span class="ui-icon ui-icon-trash"></span></li>';
						result += '</ul></td>';
						result += '</tr>';
					});
					// Close Table
					result += '</table>';
				} else {
					result += 'El siniestro no posee lesiones a terceros.';
				}
				// Populate DIV
				$('#divBoxListLesionesTerceros').html(result);
			}
		});
	}
	populateDiv_CajaIngresosSistema = function(sucursal_id, fecha) {
		var dfd = new $.Deferred();
		$.getJSON("get-json-fich_caja_ingresos_sistema.php?sucursal_id="+sucursal_id+"&date=" + fecha, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
				} else {
					// General variables
					var total = 0;
					var result = '';
					// Open Table and Headers
					result += '<table class="tblBox">';
					result += '<tr>';
					result += '<th height="21">Hora</th>';
					result += '<th>Usuario</th>';
					result += '<th>Recibo</th>';
					result += '<th>Cliente</th>';
					result += '<th>Cuota</th>';
					result += '<th>Valor</th>';
					result += '<th>No Efc</th>';
					result += '<th>No Entra</th>';
					result += '</tr>';
					// Table Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td height="21">' + object.hora + '</td>';
						result += '<td>' + object.usuario_usuario + '</td>';
						result += '<td>' + object.cuota_recibo + '</td>';
						result += '<td>' + object.nombre + '</td>';
						result += '<td>' + object.cuota_nro + '/' + object.cuota_nros + '</td>';
						result += '<td>' + object.cuota_monto + '</td>';
						result += '<td><input name="box-cuota_no_efc[]" value="'+object.id+'" type="checkbox" '+(object.cuota_no_efc==1?'checked':'')+'/></td>';
						result += '<td><input name="box-cuota_no_entra[]" value="'+object.id+'" type="checkbox" '+(object.cuota_no_entra==1?'checked':'')+'/></td>';
						result += '</tr>';
						total += (object.cuota_no_efc==1?0:parseFloat(object.cuota_monto));
					});
					// Close Table
					result += '</table>';
					// Populate DIV
					$('#divIngresosSistema').html(result);
					$('#totalIngresosSistema').text(parseFloat(total).toFixed(2));
				}
				calculateCajaDiaria();
				dfd.resolve();
			}
		});
		return dfd.promise();
	}
	populateDiv_CajaEgresos = function(sucursal_id, fecha) {
		var dfd = new $.Deferred();
		$.getJSON("get-json-fich_caja_egresos.php?sucursal_id="+sucursal_id+"&date=" + fecha, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
				} else {
					// General variables
					var total = 0;
					var result = '';
					// Open Table and Headers
					result += '<table class="tblBox">';
					result += '<tr>';
					result += '<th height="21">Hora</th>';
					result += '<th>Usuario</th>';
					result += '<th>Detalle</th>';
					result += '<th>Valor</th>';
					result += '<th>Acciones</th>';
					result += '</tr>';
					// Table Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td height="21">' + object.hora + '</td>';
						result += '<td>' + object.usuario_usuario + '</td>';
						result += '<td>' + object.caja_egreso_detalle + '</td>';
						result += '<td>' + object.caja_egreso_valor + '</td>';
						result += '<td><ul class="listInlineIcons"><li title="Eliminar egreso" onClick="$.when(deleteViaLink(\'caja_egresos\','+object.id+')).then(function(){populateDiv_CajaEgresos('+sucursal_id+', \''+fecha+'\');});"><span class="ui-icon ui-icon-trash"></span></li></ul></td>';
						result += '</tr>';
						total += parseFloat(object.caja_egreso_valor);
					});
					// Close Table
					result += '</table>';
					// Populate DIV
					$('#divEgresos').html(result);
					$('#totalEgresos').text(parseFloat(total).toFixed(2));
				}
				calculateCajaDiaria();
				dfd.resolve();
			}
		});
		return dfd.promise();
	}
	populateDiv_CajaIngresos = function(sucursal_id, fecha) {
		var dfd = new $.Deferred();
		$.getJSON("get-json-fich_caja_ingresos.php?sucursal_id="+sucursal_id+"&date=" + fecha, {}, function (j) {
			if (j.error == 'expired') {
				// Session expired
				sessionExpire('box');
			} else {
				if (j.empty == true) {
					// Record not found
				} else {
					// General variables
					var total = 0;
					var result = '';
					// Open Table and Headers
					result += '<table class="tblBox">';
					result += '<tr>';
					result += '<th height="21">Hora</th>';
					result += '<th>Usuario</th>';
					result += '<th>Recibo</th>';
					result += '<th>Cliente</th>';
					result += '<th>Valor</th>';
					result += '<th>Acciones</th>';
					result += '</tr>';
					// Table Data
					$.each(j, function (i, object) {
						result += '<tr>';
						result += '<td height="21">' + object.hora + '</td>';
						result += '<td>' + object.usuario_usuario + '</td>';
						result += '<td>' + object.caja_ingreso_recibo + '</td>';
						result += '<td>' + object.caja_ingreso_cliente + '</td>';
						result += '<td>' + object.caja_ingreso_valor + '</td>';
						result += '<td><ul class="listInlineIcons"><li title="Eliminar ingreso" onClick="$.when(deleteViaLink(\'caja_ingresos\','+object.id+')).then(function(){populateDiv_CajaIngresos('+sucursal_id+', \''+fecha+'\');});"><span class="ui-icon ui-icon-trash"></span></li></ul></td>';
						result += '</tr>';
						total += parseFloat(object.caja_ingreso_valor);
					});
					// Close Table
					result += '</table>';
					// Populate DIV
					$('#divIngresos').html(result);
					$('#totalIngresos').text(parseFloat(total).toFixed(2));
				}
				calculateCajaDiaria();
				dfd.resolve();
			}
		});
		return dfd.promise();
	}

	populateDialog_Calendar = function(type, date) {
		$.ajax({
			url: "get-json-"+type+".php?date="+date,
			dataType: 'json',
			success: function (j) {
				var output = '';
				output += '<table class="tblBox">';
				$.each(j, function (key, object) {
					output += '<tr>';
					output += '<td>'+object.cliente_nombre+'</td>';
					switch(type) {
					case 'vencimientos':
					case 'renovaciones':
						output += '<td>PZA '+object.poliza_numero+'</td>';
						output += '<td>'+object.patente+'</td>';
						if (type=='vencimientos') {
							output += '<td>'+object.cuota_nro+'/'+object.poliza_cant_cuotas+'</td>';
							output += '<td>$'+object.cuota_monto+'</td>';
						}
						break;
					case 'cumpleanos':
						output += '<td>'+object.cliente_nacimiento+'</td>';
						output += '<td>'+object.telefonos+'</td>';
						break;
					}
						
					output += '</tr>';
				});
				output += '</table>';
				$('#eventdialog').html(output);
			}
		});
	}
	
	/* Insert via form functions */
	insertFormUsuario = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-usuario.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormSeguro = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-seguro.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormProd = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-prod.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'onerror', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormProdSeg = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-prodseg.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					openBoxModSeguro(id);
				});
			}
		});
	}
	insertFormSuc = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-suc.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormCliente = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// AJAXize form
		$("#frmBox").ajaxSubmit({
			url: 'insert-cliente.php',
			type: 'POST',
			beforeSend: function () {

			},
			uploadProgress: function (event, position, total, percentComplete) {

			},
			success: function (responseText, statusText, xhr) {
				data = responseText;
				if (data == 'Session expired') {
					sessionExpire('box');
				} else {
					// Table standing redraw
					if (typeof oTable != 'undefined') {
						oTable.fnStandingRedraw();
					}
					if (!isNaN(data)) {
						openBoxModCliente(data);
					}
					else {
						// Show message
						showBoxConf(data, false, 'always', 3000, function () {});
					}
				}
			}
		});
	}
	insertFormContacto = function (id) {
		// Disable button
		$('#btnBoxContacto').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBoxContacto").serializeArray();
		param.push({
			name: "box-cliente_id",
			value: id
		});
		// Post
		$.post("insert-contacto.php", param, function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Clear form
					$('#frmBoxContacto').each(function () {
						this.reset();
					});
					$('#box-localidad_id').trigger('chosen:updated');
					$('box-contacto_tipo').val('Particular');
					// Refresh DIVs
					populateDiv_Contacto(id);
				}
				// Enable button
				$('#btnBoxContacto').button("option", "disabled", false);
			}
		});
	}
	insertFormPoliza = function (flota) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-poliza.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					var id = parseInt(data);
					if (flota) openBoxPolizaFlota('detalle', id, true);
					else openBoxPolizaDet(id, true);
				} else {
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	insertFormAsegurado = function (id) {
		// Disable button
		$('#btnBoxAsegurado').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBoxAsegurado").serializeArray();
		param.push({
			name: "box-poliza_id",
			value: id
		});
		// Post
		$.post("insert-accidentes_asegurado.php", param, function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Clear form
					$('#frmBoxAsegurado').each(function () {
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
	insertFormClausula = function (id) {
		// Disable button
		$('#btnBoxClausula').button("option", "disabled", true);
		// Set form parameters
		var param = $("#frmBoxClausula").serializeArray();
		param.push({
			name: "box-poliza_id",
			value: id
		});
		// Post
		$.post("insert-accidentes_clausula.php", param, function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Clear form
					$('#frmBoxClausula').each(function () {
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
	insertFormEndoso = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-endoso.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
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
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	insertFormSegCob = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-segcob.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					openBoxModSeguro(id);
				});
			}
		});
	}
	insertFormRie = function(id, seguro_id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-rie.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					openBoxModSeguro(id);
				});
			}
		});
	}
	insertFormOrg = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-org.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'onerror', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormSiniestro = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-siniestro.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					var id = parseInt(data);
					openBoxModSiniestro(id, true);
				} else {
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	insertFormSiniestroDatosTercero = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-siniestro_datos_tercero.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					openBoxModSiniestro(id, 'btnNuevoDatosTercero');
				} else {
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	insertFormSiniestroLesionesTercero = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-siniestro_lesiones_tercero.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					openBoxModSiniestro(id, 'btnNuevoLesionesTercero');
				} else {
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	insertFormAutoMarca = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-auto_marca.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormAutoModelo = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-auto_modelo.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}
	insertFormAutoVersion = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("insert-auto_version.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear form
					$('#frmBox').each(function () {
						this.reset();
					});
				});
			}
		});
	}

	/* Update via form functions */
	updateFormUsuario = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-usuario.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Clear password fields
					$("#box-usuario_clave").val('');
					$("#box-usuario_clave2").val('');
					// Repopulate form
					populateFormBoxUsuario($('#box-usuario_id').val());
				});
			}
		});
	}
	updateFormSeguro = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-seguro.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxSeguro($('#box-seguro_id').val());
				});
			}
		});
	}
	updateFormProd = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-prod.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxProd($('#box-productor_id').val());
				});
			}
		});
	}
	updateFormSuc = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-suc.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxSuc($('#box-sucursal_id').val());
				});
			}
		});
	}
	updateFormCliente = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-cliente.php", $("#frmBox").serialize()+'&'+$("#frmBox1").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxCliente($('#box-cliente_id').val());
				});
			}
		});
	}
	updateFormPoliza = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-poliza.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, true, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxPoliza($('#box-poliza_id').val());
				});
			}
		});
	}
	updateFormAsegurado = function (id) {
		// Disable button
		$('#btnBoxAsegurado').button("option", "disabled", true);
		// Post
		$.post("update-accidentes_asegurado.php", $("#frmBoxAsegurado").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Clear form
					$('#frmBoxAsegurado').each(function () {
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
	updateFormClausula = function (id) {

	}
	updateFormEndoso = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-endoso.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, true, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxEndoso($('#box-endoso_id').val());
				});
			}
		});
	}
	updateFormSegCob = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-segcob.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Table standing redraw
					if (typeof oTable != 'undefined') {
						oTable.fnStandingRedraw();
					}
					// Show message
					showBoxConf(data, false, 'always', 3000, function () {
						// Repopulate form
						openBoxModSeguro(id);
					});
				}
				// Enable button
				$('#btnBox').button("option", "disabled", false);
			}
		});
	}
	updateFormContacto = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-contacto.php", $("#frmBoxContacto").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Clear form
					$('#frmBoxContacto').each(function () {
						this.reset();
					});
					$('#box-localidad_id').trigger('chosen:updated');
					$("#box-contacto_id").remove();
					$("#box-action").val('insert');
					$("#btnBoxResetContacto").button('option', 'label', 'Borrar');
					$("#btnBoxContacto").button('option', 'label', 'Agregar');
					$("#box-contacto_tipo").val('Particular');
					$("#box-contacto_domicilio").focus();
					// Refresh DIVs
					populateDiv_Contacto(id);
				}
				// Enable button
				$('#btnBox').button("option", "disabled", false);
			}
		});
	}
	updateFormSegCod = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-prodseg.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				} else {
					// Table standing redraw
					if (typeof oTable != 'undefined') {
						oTable.fnStandingRedraw();
					}
					// Show message
					showBoxConf(data, false, 'always', 3000, function () {
						// Repopulate form
						openBoxModSeguro(id);
					});
				}
				// Enable button
				$('#btnBox').button("option", "disabled", false);
			}
		});
	}
	updateFormRie = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-rie.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					openBoxModSeguro(id);
				});
			}
		});
	}
	updateFormOrg = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-org.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxOrg($('#box-organizador_id').val());
				});
			}
		});
	}
	updateFormSiniestro = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		var croquis = '';
		var t = $('#droppable').offset().top;
		var b = t + $('#droppable').height();
		var l = $('#droppable').offset().left;
		var r = l + $('#droppable').width();
		$('.croquis-auto').each(function(i,e) {
			var top = $(e).offset().top;
			var left = $(e).offset().left;
			if (top >= t && top <= b && left >= l && left <= r) {
				croquis += '&box-croquis-'+$(e).prop('id')+'='+(parseInt(top)-parseInt(t))+'x'+(parseInt(left)-parseInt(l));
			}
		});
		$('.croquis-moto').each(function(i,e) {
			var top = $(e).offset().top;
			var left = $(e).offset().left;
			if (top >= t && top <= b && left >= l && left <= r) {
				croquis += '&box-croquis-moto'+i+'='+(parseInt(top)-parseInt(t))+'x'+(parseInt(left)-parseInt(l));
			}
		});
		$('.croquis-peaton').each(function(i,e) {
			var top = $(e).offset().top;
			var left = $(e).offset().left;
			if (top >= t && top <= b && left >= l && left <= r) {
				croquis += '&box-croquis-peat'+i+'='+(parseInt(top)-parseInt(t))+'x'+(parseInt(left)-parseInt(l));
			}
		});
		$('.croquis-direccion').each(function(i,e) {
			var top = $(e).offset().top;
			var left = $(e).offset().left;
			if (top >= t && top <= b && left >= l && left <= r) {
				croquis += '&box-croquis-dire'+i+'='+$(e).attr('direction')+','+(parseInt(top)-parseInt(t))+'x'+(parseInt(left)-parseInt(l));
			}
		});
		// Post
		$.post("update-siniestro.php", $("#frmBox").serialize() + croquis, function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, true, 'always', 3000, function () {
					// Repopulate form
					openBoxSiniestroCert(id);
				});
			}
		});
	}
	updateFormSiniestroDatosTercero = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-siniestro_datos_tercero.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					openBoxModSiniestro(id, 'btnNuevoDatosTercero');
				} else {
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	updateFormSiniestroLesionesTercero = function(id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-siniestro_lesiones_tercero.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// If no error ocurred
				if ((data.toLowerCase().indexOf("error") === -1)) {
					openBoxModSiniestro(id, 'btnNuevoLesionesTercero');
				} else {
					showBoxConf(data, true, 'always', 3000, function () {});
				}
			}
		});
	}
	updateFormAutoMarca = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-auto_marca.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxAutoMarca($('#box-automotor_marca_id').val());
				});
			}
		});
	}
	updateFormAutoModelo = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-auto_modelo.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxAutoModelo($('#box-automotor_modelo_id').val());
				});
			}
		});
	}
	updateFormAutoVersion = function() {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-auto_version.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxAutoVersion($('#box-automotor_version_id').val());
				});
			}
		});
	}
	
	/* Update via Link functions */
	updateLinkContacto_Default = function (id, cliente_id) {
		if (confirm('Está seguro que desea establecer este contacto como primario?')) {
			$.post("update-contacto_default.php", {
				id: id
			}, function (data) {
				if (data == 'Session expired') {
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
	updateLinkCuota_PFC = function (id, poliza_id) {
		$.post("update-cuota_pfc.php", {
			id: id
		}, function (data) {
			if (data == 'Session expired') {
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
	updateCuotaAnular = function (cuota_id, poliza_id) {
		if (confirm('Seguro desea anular la cuota?')) {
			$.post('update-cuota_anular.php', {
				'id': cuota_id,
			}, function (data) {
				openBoxCuota(poliza_id);
			});
		}
	}
	updateFormPolizaObservaciones = function (id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("update-poliza_observaciones.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 3000, function () {
					// Repopulate form
					populateFormBoxPolizaObservaciones(id);
				});
			}
		});
	}
	updatePolizaArchivar = function(id, flag) {
		flag = flag || 0;
		if (confirm('Seguro desea archivar la póliza?')) {
			$.post('update-poliza_archivar.php', {
				'id': id,
				'flag': flag,
			}, function (data) {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
			});
		}
	}

	/* Process via form functions */
	processFormPolizaDet = function (id, fromcreate, flota) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);

		// AJAXize form
		$("#frmBox").ajaxSubmit({
			url: 'process-polizadet.php',
			type: 'POST',
			data: {
				'box-poliza_id': id,
				'flota': flota,
			},
			beforeSend: function () {

			},
			uploadProgress: function (event, position, total, percentComplete) {

			},
			success: function (responseText, statusText, xhr) {
				data = responseText;
				if (data == 'Session expired') {
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
							if (flota!=0) 
								openBoxPolizaFlota('detalle', id, true);
							else {
								// Open next box
								openBoxPolizaCert(id);
							}
						} else {
							// Show message
							showBoxConf(data, true, 'always', 3000, function () {
								// Populate form
								populateFormBoxPolizaDet(id);
								// Enable button
								$('#btnBox').button("option", "disabled", false);
							});
						}
					} else {
						// Show message
						showBoxConf(data, true, 'always', 3000, function () {
							// Enable button
							$('#btnBox').button("option", "disabled", false);
						});
					}
				}
			}
		});
	}
	processFormPolizaRen = function () {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("process-polizaren.php", $("#frmBox").serializeArray(), function (data) {
			if (data == 'Session expired') {
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
					openBoxPolizaDet(newid, 'ren');
				} else {
					// Show message
					showBoxConf(data, true, 'always', 3000, function () {
						// Enable button
						$('#btnBox').button("option", "disabled", false);
					});
				}
			}
		});
	}
	processFormPayCuota = function (poliza_id, cuota_id) {
		// Disable button
		$('#btnBox').button("option", "disabled", true);
		// Post
		$.post("process-paycuota.php", $("#frmBox").serialize(), function (data) {
			if (data == 'Session expired') {
				sessionExpire('box');
			} else {
				// Table standing redraw
				if (typeof oTable != 'undefined') {
					oTable.fnStandingRedraw();
				}
				// Show message
				showBoxConf(data, false, 'always', 10, function () {
					openBoxCuota(poliza_id);
					var recibo = window.open('print-cuota.php?print&id=' + cuota_id);
				});
			}
		});
	}
	processLibrosRubricados = function() {
		$.ajax({
			url: 'process-libros_rubricados.php',
			success: function(data) {
				showBoxConf(data, false, 'always', 3000, function () {});
			},
			error: function() {
				showBoxConf('Error. Intente nuevamente.', false, 'always', 3000, function () {});
			}
		});
	}
	
	renderCroquis = function() {
		var dfd = new $.Deferred();
		$('#droppable')[0].scrollIntoView();
		html2canvas($('#droppable'), {
		  onrendered: function(canvas) {
			$('#box-croquis_img-noupper').val(canvas.toDataURL());
			dfd.resolve();
		  }
		});
		return dfd.promise();
	}
	
	/* Delete via Link functions */
	deleteProdSeg = function (id, productor_id) {
		$.when(
			deleteViaLink('prodseg', id)
		).then(function () {
			populateDiv_ProdSeg(productor_id);
		})
	}
	deleteContacto = function (id, cliente_id) {
		$.when(
			deleteViaLink('contacto', id)
		).then(function () {
			populateDiv_Contacto(cliente_id);
		})
	}
	deleteAccidentesAsegurado = function (id, poliza_id) {
		$.when(
			deleteViaLink('accidentes_asegurado', id)
		).then(function () {
			populateDiv_Asegurado(poliza_id);
		})
	}
	deleteAccidentesClausula = function (id, poliza_id) {
		$.when(
			deleteViaLink('accidentes_clausula', id)
		).then(function () {
			populateDiv_Clausula(poliza_id);
		})
	}

	/* Box functions */
	openBoxAltaUsuario = function () {
		$.colorbox({
			title: 'Registro',
			href: 'box-usuario_alta.php',
			width: '700px',
			height: '450px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate selects, then initialize
				$.when(
					populateListUsuario_Acceso('box-usuario_acceso', 'box'),
					populateListUsuario_Sucursal('box-usuario_sucursal', 'box')
				).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-usuario_nombre": {
								required: true
							},
							"box-usuario_email": {
								required: true,
								email: true
							},
							"box-usuario_usuario": {
								required: true,
								minlength: 4
							},
							"box-usuario_clave": {
								required: true,
								minlength: 8
							},
							"box-usuario_clave2": {
								required: true,
								equalTo: "#box-usuario_clave"
							},
							"box-usuario_acceso": {
								required: true
							},
							"box-usuario_sucursal[]": {
								required: function () {
									return $("#box-usuario_acceso").val() == "administrativo";
								}
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								insertFormUsuario();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					$("#box-usuario_sucursal").prop("disabled", true);
					$("#box-usuario_acceso").change(function () {
						$("#box-usuario_sucursal").prop("disabled", !($("#box-usuario_acceso").val() == "administrativo"));
					});
				});

			}
		});
	}
	openBoxModUsuario = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-usuario_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxUsuario(id)).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-usuario_nombre": {
								required: true
							},
							"box-usuario_email": {
								required: true,
								email: true
							},
							"box-usuario_usuario": {
								required: true,
								minlength: 4
							},
							"box-usuario_clave": {
								minlength: 8
							},
							"box-usuario_clave2": {
								equalTo: "#box-usuario_clave"
							},
							"box-usuario_acceso": {
								required: true
							},
							"box-usuario_sucursal[]": {
								required: function () {
									return $("#box-usuario_acceso").val() == "administrativo";
								}
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormUsuario();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					$("#box-usuario_sucursal").prop("disabled", !($("#box-usuario_acceso").val() == "administrativo"));
					$("#box-usuario_acceso").change(function () {
						$("#box-usuario_sucursal").prop("disabled", !($("#box-usuario_acceso").val() == "administrativo"));
					});
				});

			}
		});
	}
	openBoxAltaSeguro = function () {
		$.colorbox({
			title: 'Registro',
			href: 'box-seguro_alta.php',
			width: '700px',
			height: '550px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-seguro_nombre": {
							required: true
						},
						"box-seguro_email_siniestro": {
							csemails: true
						},
						"box-seguro_email_emision": {
							csemails: true
						},
						"box-seguro_email_endosos": {
							csemails: true
						},
						"box-seguro_email_rastreador": {
							csemails: true
						},
						"box-seguro_email_fotos": {
							csemails: true
						},
						"box-seguro_email_inspeccion": {
							csemails: true
						}
					}
				});

				// Button action
				$("#btnBox").click(function () {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormSeguro();
						}
					};
				});

				// Enable form
				formDisable('frmBox', 'ui', false);

			}
		});
	}
	openBoxModSeguro = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-seguro_mod.php',
			width: '900px',
			height: '100%',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(
					populateFormBoxSeguro(id)
				).then(function () {
					seguroCoberturasTable = $('#seguroCoberturas').dataTable({
						"oLanguage": {
							"sUrl": "jquery-plugins/dataTables/media/language/es_AR.txt"						
						},
						"sPaginationType": "full_numbers",
						"processing": true,
						"bServerSide": true,
						"sAjaxSource": "datatables-segurocoberturas.php"+'?action=view&seguro='+id,
						"aoColumns": [
							{"bSearchable": false, "bVisible": false},
							null,
							{"fnRender": function(oObj) {
								return oObj.aData[2]+(oObj.aData[2]!=''&&oObj.aData[3]!=''?' a ':'')+oObj.aData[3];
							}},
							{"bSearchable": false, "bVisible": false},
							null,
							null,
							null,
							{"sWidth": "8%", "bSearchable": false, "fnRender": function (oObj) {
								var returnval = '';
								returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
								returnval += '<li title="Editar" onclick="openBoxModCob('+oObj.aData[0]+', '+id+');"><span class="ui-icon ui-icon-pencil"></span></li>';							
								returnval += '<li title="Eliminar" onclick="deleteViaLink(\'segcob\','+oObj.aData[0]+', seguroCoberturasTable);"><span class="ui-icon ui-icon-trash"></span></li>';
								returnval += '</ul>';
								return returnval;
							}}
						]
					});
					$('#btnSeguroCoberturas').button().click(function(event) {
						event.preventDefault();
						openBoxAltaCob(id);
					});
					
					seguroCodigosTable = $('#seguroCodigos').dataTable({
						"oLanguage": {
							"sUrl": "jquery-plugins/dataTables/media/language/es_AR.txt"						
						},
						"sPaginationType": "full_numbers",
						"processing": true,
						"bServerSide": true,
						"sAjaxSource": "datatables-segurocodigos.php"+'?action=view&seguro='+id,
						"aoColumns": [
							{"bSearchable": false, "bVisible": false},
							null,
							null,
							null,
							null,
							{"sWidth": "8%", "bSearchable": false, "fnRender": function (oObj) {
								var returnval = '';
								returnval += '<ul class="dtInlineIconList ui-widget ui-helper-clearfix">';
								returnval += '<li title="Editar" onclick="openBoxModCod('+oObj.aData[0]+', '+id+');"><span class="ui-icon ui-icon-pencil"></span></li>';							
								returnval += '<li title="Eliminar" onclick="deleteViaLink(\'prodseg\','+oObj.aData[0]+', seguroCodigosTable);"><span class="ui-icon ui-icon-trash"></span></li>';
								returnval += '</ul>';
								return returnval;
							}}
						]
					});
					$('#btnSeguroCodigos').button().click(function(event) {
						event.preventDefault();
						openBoxAltaCod(id);
					});
					
					populateDiv_SeguroZonasRiesgo(id);
					$('#btnSeguroZonasRiesgo').button().click(function(event) {
						event.preventDefault();
						openBoxAltaZonaRiesgo(id);
					});
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_nombre": {
								required: true
							},
							"box-seguro_email_siniestro": {
								csemails: true
							},
							"box-seguro_email_emision": {
								csemails: true
							},
							"box-seguro_email_endosos": {
								csemails: true
							},
							"box-seguro_email_rastreador": {
								csemails: true
							},
							"box-seguro_email_fotos": {
								csemails: true
							},
							"box-seguro_email_inspeccion": {
								csemails: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormSeguro();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxAltaProd = function () {
		$.colorbox({
			title: 'Registro',
			href: 'box-prod_alta.php',
			width: '700px',
			height: '520px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate drop-downs, then initialize form
				$.when(
					populateListProductor_IVA('box-productor_iva', 'box')
				).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-productor_nombre": {
								required: true
							},
							"box-productor_iva": {
								required: true
							},
							"box-productor_cuit": {
								required: true
							},
							"box-productor_matricula": {
								required: true
							},
							"box-productor_email": {
								email: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								insertFormProd();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxModProd = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-prod_mod.php',
			width: '700px',
			height: '520px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxProd(id)).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-productor_nombre": {
								required: true
							},
							"box-productor_iva": {
								required: true
							},
							"box-productor_cuit": {
								required: true
							},
							"box-productor_matricula": {
								required: true
							},
							"box-productor_email": {
								email: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormProd();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxAltaSuc = function () {
		$.colorbox({
			title: 'Registro',
			href: 'box-suc_alta.php',
			width: '700px',
			height: '450px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				$('#box-sucursal_pfc').change(function() {
					$('#box-sucursal_pfc_default').attr('disabled', !$(this).prop('checked'));
				})
				
				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-sucursal_nombre": {
							required: true
						},
						"box-sucursal_email": {
							email: true
						}
					}
				});

				// Button action
				$("#btnBox").click(function () {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormSuc();
						}
					};
				});

				// Enable form
				formDisable('frmBox', 'ui', false);
				$('#box-sucursal_pfc').change();
			}
		});
	}

	openBoxModSuc = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-suc_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxSuc(id)).then(function () {
					$('#box-sucursal_pfc').change(function() {
						$('#box-sucursal_pfc_default').attr('disabled', !$(this).prop('checked'));
					})
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-sucursal_nombre": {
								required: true
							},
							"box-sucursal_email": {
								email: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormSuc();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					$('#box-sucursal_pfc').change();
				});

			}
		});
	}


	openBoxAltaCliente = function () {
		$.colorbox({
			title: 'Registro',
			href: 'box-cliente_alta.php',
			width: '950px',
			height: '100%',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox, #btnContact").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate drop-downs, then initialize form
				$.when(
					populateListCliente_Sexo('box-cliente_sexo', 'box'),
					populateListCliente_Nac('box-cliente_nacionalidad_id', 'box'),
					populateListCliente_CF('box-cliente_cf_id', 'box'),
					populateListCliente_TipoDoc('box-cliente_tipo_doc', 'box'),
					populateListCliente_RegTipo('box-cliente_reg_tipo_id', 'box'),
					populateListCliente_TipoSociedad('box-cliente_tipo_sociedad_id', 'box'),
					populateListSuc("box-sucursal_id", "box")
				).then(function () {

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
					$("#box-cliente_nacionalidad").val(1);
					$("#box-cliente_cf_id").val(1);
					$("#box-cliente_tipo_doc").val('DNI');
					$("#box-cliente_reg_tipo_id").val(5);

					// On Change: Input text
					$("#box-cliente_nro_doc").keyup(function () {
						$('#box-cliente_registro').val($(this).val());
					});
					$("#box-cliente_cuit_1").keyup(function(event) {
						if (event.keyCode!=9) {
							$("#box-cliente_nro_doc").val($(this).val()).keyup();
						}
					});
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-cliente_apellido": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_nombre": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_razon_social": {
								required: function() {return $("#box-cliente_tipo_persona").val()==2}
							},
							"box-cliente_nacimiento": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_sexo": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_nacionalidad_id": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_tipo_doc": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_nro_doc": {
								required: true
							},
							"box-cliente_cf_id": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_cuit_0": {
								required:  function() {return $("#box-cliente_tipo_persona").val()==2}
							},
							"box-cliente_cuit_1": {
								required:  function() {return $("#box-cliente_tipo_persona").val()==2}
							},
							"box-cliente_cuit_2": {
								required:  function() {return $("#box-cliente_tipo_persona").val()==2}
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								insertFormCliente();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					$('#box-cliente_tipo_persona').change();
					$('#btnContact').hide();
				});

			}
		});
	}
	openBoxModCliente = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-cliente_mod.php',
			width: '970px',
			height: '100%',
			onComplete: function () {
				populateDiv_Fotos('cliente', id, 'Registro');
				populateDiv_Contacto(id);

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
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(
					populateFormBoxCliente(id),
					populateListContacto_Tipo('box-contacto_tipo', 'box'),
					populateListTelefonoCompania('box-contacto_telefono2_compania', 'box')
				).then(function () {

					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-cliente_apellido": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_nombre": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_razon_social": {
								required: function() {return $("#box-cliente_tipo_persona").val()==2}
							},
							"box-cliente_nacimiento": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_sexo": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_nacionalidad_id": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_tipo_doc": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_nro_doc": {
								required: function() {return $("#box-cliente_tipo_persona").val()==1}
							},
							"box-cliente_cuit_0": {
								required:  function() {return $("#box-cliente_tipo_persona").val()==2}
							},
							"box-cliente_cuit_1": {
								required:  function() {return $("#box-cliente_tipo_persona").val()==2}
							},
							"box-cliente_cuit_2": {
								required:  function() {return $("#box-cliente_tipo_persona").val()==2}
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormCliente();
						};
					});
					$('#btnAcciones').button().click(function() {
						openBoxClieAcciones(id);
					});
					
					
					$('#cliente_foto').ajaxForm({
						data: { cliente_id: id },
						beforeSend: function () {
							$("#fotosLoadingcliente_foto").show();
						},
						uploadProgress: function (event, position, total, percentComplete) {
						
						},
						complete: function (xhr) {
							if (xhr.responseText.indexOf('Error:') != -1) {
								alert(xhr.responseText);
							} else {
								$("#fotosLoadingcliente_foto").show().hide();
							}
							populateDiv_Fotos('cliente', id, 'Registro');
						}
					});
					
					// Contactos
					$.when(populateListLocalidades('box-localidad_id', 'box')).then(function() {
						$('#box-localidad_id').chosen();
					});
					
					$('#box-contacto_tipo').val('Particular');

					// Validate form
					var validateFormContacto = $("#frmBoxContacto").validate({
						rules: {
							"box-contacto_tipo": {
								required: true
							},
							"box-contacto_domicilio": {
								required: true
							},
							"box-contacto_nro": {
								required: true
							}
						}
					});
					// Button action
					$("#btnBoxContacto").button().click(function () {
						if (validateFormContacto.form()) {
							if ($("#box-action").val() == 'insert') {
								insertFormContacto(id);
							} else {
								updateFormContacto(id);
							}
						};
					});
					
					$("#btnBoxResetContacto").button().click(function () {
						// Clear form
						$('#frmBoxContacto').each(function () {
							this.reset();
						});
						$('#box-localidad_id').trigger('chosen:updated');
						$("#box-contacto_id").remove();
						$("#box-action").val('insert');
						$("#btnBoxResetContacto").button('option', 'label', 'Borrar');
						$("#btnBoxContacto").button('option', 'label', 'Agregar');
						$("#box-contacto_domicilio").focus();
					});
					
					// Enable form
					formDisable('frmBox', 'ui', false);
					$('#box-cliente_tipo_persona').change();
				});

			}
		});
	}
	openBoxPolizas = function (id) {
		$.colorbox({
			title: 'Cliente/Pólizas',
			href: 'box-cliepoli.php',
			width: '700px',
			height: '600px',
			onComplete: function () {

				// -------------------- GENERAL ---------------------

				// Initialize buttons
				$("#btnBox").button();

				// Disable forms
				formDisable('frmBox', 'ui', true);

				// Populate DIVs
				populateDiv_Cliente_Info(id);
				populateDiv_Polizas(id);

				// -------------------- FORM 1 ----------------------

				// Populate drop-downs, then initialize form
				$.when(
					populateListSeguro('box-seguro_id', 'box')
				).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_id": {
								required: true
							},
							"box-productor_seguro_codigo": {
								required: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							insertFormProdSeg(id);
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});

	}
	openBoxAltaPoliza = function (tipo, subtipo, cliente_id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-poliza_alta.php?section=1&tipo='+tipo,
			width: '700px',
			height: '100%',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable forms
				formDisable('frmSelectClient', 'normal', true);
				formDisable('frmBox', 'ui', true);

				// FORM INSERT POLIZA
				// Populate drop-downs, then initialize
				$.when(
					populateListSuc('box-sucursal_id', 'box'),
					populateListSeguro('box-seguro_id', 'box'),
					populateListTipoPoliza('box-tipo_poliza_id', 'box', tipo),
					populateListPoliza_Vigencia('box-poliza_vigencia', 'box'),
					populateListPoliza_Cuotas('box-poliza_cuotas', 'box'),
					populateListPoliza_MP('box-poliza_medio_pago', 'box')
				).then(function () {
					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					var empty = '<option value="">Todos</option>';
					$("#box-tipo_poliza_id").change(function () {
						$('#box-subtipo_poliza_id').html(loading);
						populateListSubtipoPoliza($(this).val(), 'box-subtipo_poliza_id', 'box');
						// Si el tipo de póliza es PERSONAS, ampliar rango de selección de vigencia
						populateListPoliza_Vigencia('box-poliza_vigencia', 'box', $(this).val());
					});
					$('#box-sucursal_id').change(function () {
						$('#box-productor_seguro_id').html(loading);
						populateListProductorSeguro_Productor($("#box-seguro_id").val(), $(this).val(), 'box-productor_seguro_id', 'box');
						populateFormPFC($(this).val());
						$('#box-poliza_cant_cuotas').attr('readonly', $(this).val()==1 && $('#box-subtipo_poliza_id').val()==6);
					})
					$("#box-poliza_vigencia, #box-poliza_validez_desde").change(function () {
						var months;
						switch ($("#box-poliza_vigencia").val()) {
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
						if ($("#box-poliza_vigencia").val() !== '') {
							if ($("#box-poliza_vigencia").val() == 'Otra') {
								$("#box-poliza_vigencia_dias").attr('readonly', false);
								$("#box-poliza_vigencia_dias").focus();
							} else {
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
					$("#box-poliza_vigencia_dias").change(function () {
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
					$("#box-poliza_medio_pago, #box-poliza_cuotas").change(function() {
						if ($('#box-seguro_id').val()=='4') {
							switch ($(this).prop('id')) {
							case 'box-poliza_cuotas':
								var mp;
								switch ($(this).val()) {
								case 'Mensual':
									mp = ['Tarjeta de Credito / CBU - 1 Cuota'];
									var options = '';
									for (var i = 0; i < mp.length; i++) {
										options += '<option value="' + mp[i] + '">' + mp[i] + '</option>';
									}
									$('#box-poliza_medio_pago').html(options);
									$('#box-poliza_medio_pago').val('Tarjeta de Credito / CBU - 1 Cuota').change();
									break;
								case 'Semestral':
									mp = ['1 Pago Cupon Contado', '1 Pago Tarjeta de Credito', '6 Cuotas Pago Cupones', '6 Cuotas Pago Tarj/CBU'];
									var options = '';
									for (var i = 0; i < mp.length; i++) {
										options += '<option value="' + mp[i] + '">' + mp[i] + '</option>';
									}
									$('#box-poliza_medio_pago').html(options);
									$('#box-poliza_medio_pago').val('6 Cuotas Pago Cupones').change();
									break;
								}
								break;
							case 'box-poliza_medio_pago':
								var cuotas = '';
								switch ($(this).val()) {
								case 'Tarjeta de Credito / CBU - 1 Cuota':
								case '1 Pago Cupon Contado':
								case '1 Pago Tarjeta de Credito':
									cuotas = 1;
									break;
								case '6 Cuotas Pago Cupones':
								case '6 Cuotas Pago Tarj/CBU':
									cuotas = 6;
									break;
								case 'Tarjeta de Crédito':
									cuotas = 6;
									break;
								}
								$('#box-poliza_cant_cuotas').val(cuotas);
								break;
							}
						}
						else {	$('#pfc')[($('#box-poliza_medio_pago').val()=='Directo'?'show':'hide')]().children().eq(0).attr('disabled', ($('#box-poliza_medio_pago').val()=='Directo'?false:true));
							if ($(this).prop('id')=='box-poliza_cuotas')
								populateListPoliza_MP('box-poliza_medio_pago', 'box');
							var cuotas = '';
							if ($('#box-poliza_cuotas').val()=='Total') {
								cuotas = 1;
							}
							else {
								if ($('#box-subtipo_poliza_id').val()=='15' && $('#box-seguro_id').val()=='1') {
									cuotas = 12;
								}
								else {
									// casos generales automotor
									if ($('#box-subtipo_poliza_id').val()==6) {
										switch ($(this).val()) {
										case 'Cuponera':
											cuotas = 5;
											break;
										case 'Directo':
											cuotas = 5;
											$('#box-sucursal_pfc').attr('checked', true);
											break;
										case 'Débito Bancario':
										case 'Tarjeta de Crédito':
											cuotas = 6;
											break;
										}
									}
									else {
										// casos generales otros riesgos/personas
										
									}
								}
							}
							$('#box-poliza_cant_cuotas').val(cuotas);
						}
						if ($('#box-poliza_medio_pago').val()=='Directo') $('#cuota_monto').show();
						else $('#cuota_monto').hide();
					});
					$('#box-subtipo_poliza_id, #box-seguro_id').change(function() {
						switch ($(this).attr('id')) {
							case 'box-subtipo_poliza_id':
								break;
							case 'box-seguro_id':
								if ($(this).val()==4) {
									// si es allianz, prima obligatoria y mostrar campo de descuento
									$('#box-poliza_prima').addClass('required');
									$('#poliza_descuento').show();
								}
								else {
									$('#box-poliza_prima').removeClass('required');
									$('#poliza_descuento').hide();
								}
								$('#box-productor_seguro_id').html(loading);
								populateListProductorSeguro_Productor($(this).val(), $('#box-sucursal_id').val(), 'box-productor_seguro_id', 'box');
								populateListPoliza_Cuotas('box-poliza_cuotas', 'box', $(this).val());
								$('#box-poliza_cuotas, #box-poliza_medio_pago').change();								
								populateFormFlota($(this).val());
								break;
						}
						if ($('#box-subtipo_poliza_id').val()=='15' && $('#box-seguro_id').val()=='1') {
							$('#box-poliza_vigencia').val('Anual').change().children().each(function() {
								$(this).attr('disabled', $(this).val()=='Anual'?false:true);
							});
							$('#box-poliza_recargo').val('20').change().attr('readonly', true);
							$('#box-poliza_cant_cuotas').val('12').change();
							$('#box-poliza_premio').attr('readonly', true);
							$('.poliza_plan').show();
							$('#box-poliza_plan_id, #box-poliza_pack_id').addClass('required');
							$('#box-poliza_plan_id').html(loading);
							populateListPoliza_Plan('box-poliza_plan_id', 'box', $('#box-subtipo_poliza_id').val(), $('#box-seguro_id').val());
							$('#box-poliza_plan_flag').val(1);
						}
						else {
							$('#box-poliza_vigencia').children().each(function() {
								$(this).attr('disabled', false);
							});
							$('#box-poliza_recargo').attr('readonly', false);
							$('#box-poliza_premio').attr('readonly', false);
							$('#box-poliza_plan_id, #box-poliza_pack_id').removeClass('required');
							$('.poliza_plan').hide();
							$('#box-poliza_plan_flag').val(0);
						}
					})
					$('#box-poliza_plan_id').change(function() {
						$('#box-poliza_pack_id').html(loading);
						populateListPoliza_Pack('box-poliza_pack_id', 'box', $(this).val());
					})
					$('#box-poliza_pack_id').change(function() {
						populateFormPolizaPackPremio($(this).val());
					})
					// Set default values
					$('#box-poliza_validez_desde').val(Date.today().clearTime().toString("dd/MM/yy"));
					$('#box-poliza_fecha_solicitud').val(Date.today().clearTime().toString("dd/MM/yy"));
					$('#box-poliza_medio_pago').val('Directo').change();
					if (tipo) {
						$('#box-tipo_poliza_id').val(tipo);
						populateListPoliza_Vigencia('box-poliza_vigencia', 'box', tipo);
						$.when(populateListSubtipoPoliza(tipo, 'box-subtipo_poliza_id', 'box')).then(function() {
							if (subtipo) {
								$('#box-subtipo_poliza_id').val(subtipo).change();
								if (subtipo==6) {
									$('#box-poliza_vigencia').val('Semestral').change();
								}
							}
						});
					}
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-tipo_poliza_id": {
								required: true
							},
							"box-sucursal_id": {
								required: true
							},
							"box-cliente_nombre": {
								required: true
							},
							"box-subtipo_poliza_id": {
								required: true
							},
							"box-seguro_id": {
								required: true
							},
							"box-productor_seguro_id": {
								required: true
							},
							"box-poliza_vigencia": {
								required: true
							},
							"box-poliza_validez_desde": {
								required: true,
								dateAR: true
							},
							"box-poliza_validez_hasta": {
								required: true,
								dateAR: true,
								enddate: "#box-poliza_validez_desde"
							},
							"box-poliza_cuotas": {
								required: true
							},
							"box-poliza_cant_cuotas": {
								required: true,
								digits: true,
								min: 1,
								max: 255
							},
							"box-poliza_fecha_solicitud": {
								dateAR: true
							},
							"box-poliza_fecha_emision": {
								dateAR: true
							},
							"box-poliza_fecha_recepcion": {
								dateAR: true
							},
							"box-poliza_fecha_entrega": {
								dateAR: true
							},
							"box-poliza_prima": {
								min: 0,
								max: 99999999.99
							},
							"box-poliza_premio": {
								required: true,
								min: 0,
								max: 99999999.99
							},
							"box-poliza_medio_pago": {
								required: true
							},
							"box-poliza_recargo": {
								required: true,
								min: 0,
								max: 100
							}
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?\n\nEsta acción no puede deshacerse.')) {
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								insertFormPoliza($('#box-poliza_flota').prop('checked') && !$('#box-poliza_flota').prop('disabled'));
							}
						}
					});
				});

				if (cliente_id) {
					assignClientToPoliza(cliente_id);
				}
				else {
					// FORM SELECT CLIENT
					// Initialize special fields
					initAutocompleteCliente('box0-cliente_nombre', 'box');
					// Assign functions to buttons
					$("#BtnSearchCliente").click(function () {
						// If a field was completed
						if ($('#box0-cliente_nombre').val() != '' || $('#box0-cliente_nro_doc').val() != '') {
							populateDiv_Cliente_Results();
						} else {
							$('#divBoxClienteSearchResults').html('Debe ingresar información en al menos un campo.');
						}
					});
					// Submit on Enter
					$("#frmSelectClient :input[type=text]").each(function () {
						$(this).keypress(function (e) {
							if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
								$("#BtnSearchCliente").click();
							}
						});
					});
					// Enable form
					formDisable('frmSelectClient', 'normal', false);
					// Set focus on search
					$("#box0-cliente_nombre").focus();
				}
			}
		});
	}
	openBoxModPoliza = function (id, tipo) {
		$.colorbox({
			title: 'Registro',
			href: 'box-poliza_mod.php?section=1&tipo='+tipo,
			width: '700px',
			height: '100%',
			onComplete: function () {
				
				// Navegación
				$("#navegacion-detalle").click(function() {
					openBoxPolizaDet(id, false);
				});
				$('#navegacion-cert').click(function() {
					openBoxPolizaCert(id);
				});
				$('#navegacion-cuotas').click(function() {
					openBoxCuota(id);
				});
				
				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxPoliza(id)).then(function (ismaster) {

					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					$('#box-sucursal_id').change(function () {
						$('#box-productor_seguro_id').html(loading);
						populateListProductorSeguro_Productor($("#box-seguro_id").val(), $(this).val(), 'box-productor_seguro_id', 'box');
					})
					$("#box-seguro_id").change(function () {
						if ($(this).val()==4) {
							// si es allianz, prima obligatoria y mostrar campo de descuento
							$('#box-poliza_prima').addClass('required');
							$('#poliza_descuento').show();
						}
						else {
							$('#box-poliza_prima').removeClass('required');
							$('#poliza_descuento').hide();
						}
						$('#box-productor_seguro_id').html(loading);
						populateListProductorSeguro_Productor($(this).val(), $('#box-sucursal_id').val(), 'box-productor_seguro_id', 'box');
					});

					if ($("#box-seguro_id").val()==4) {
						// si es allianz, prima obligatoria y mostrar campo de descuento
						$('#box-poliza_prima').addClass('required');
						$('#poliza_descuento').show();
					}
					else {
						$('#box-poliza_prima').removeClass('required');
						$('#poliza_descuento').hide();
					}
					$('#box-poliza_medio_pago').change(function() {
						if ($('#box-poliza_medio_pago').val()=='Directo') $('#cuota_monto').show();
						else $('#cuota_monto').hide();
					}).change();
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_id": {
								required: true
							},
							"box-productor_seguro_id": {
								required: true
							},
							"box-poliza_fecha_solicitud": {
								dateAR: true
							},
							"box-poliza_fecha_emision": {
								dateAR: true
							},
							"box-poliza_fecha_recepcion": {
								dateAR: true
							},
							"box-poliza_fecha_entrega": {
								dateAR: true
							},
							"box-poliza_prima": {
								min: 0,
								max: 99999999.99
							},
							"box-poliza_medio_pago": {
								required: true
							},
							"box-poliza_recargo": {
								required: true,
								min: 0,
								max: 100
							}
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormPoliza();
						}
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					$('#box-cuota_pfc').prop('disabled', !ismaster);
				});

			}
		});
	}
	openBoxPolizaRen = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-poliza_ren.php?section=1&ren=1',
			width: '700px',
			height: '100%',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxPolizaRen(id)).then(function () {

					// Initialize datepickers
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');

					// On Change: Selects
					var loading = '<option value="">Cargando...</option>';
					$("#box-seguro_id").change(function (a) {
						a = a || true;
						if(a)populateListProductorSeguro_Productor($(this).val(), $('#box-sucursal_id').val(), 'box-productor_seguro_id', 'box');
						populateFormFlota($(this).val());
						if ($(this).val()==4) {
							// si es allianz, prima obligatoria y mostrar campo de descuento
							$('#box-poliza_prima').addClass('required');
							$('#poliza_descuento').show();
						}
						else {
							$('#box-poliza_prima').removeClass('required');
							$('#poliza_descuento').hide();
						}
						
						if ($('#box-subtipo_poliza_id').val()=='15' && $('#box-seguro_id').val()=='1') {
							$('#box-poliza_vigencia').val('Anual').change().children().each(function() {
								$(this).attr('disabled', $(this).val()=='Anual'?false:true);
							});
							$('#box-poliza_recargo').val('20').change().attr('readonly', true);
							$('#box-poliza_cant_cuotas').val('12').change();
							$('#box-poliza_premio').attr('readonly', true);
							$('.poliza_plan').show();
							$('#box-poliza_plan_id, #box-poliza_pack_id').addClass('required');
							$('#box-poliza_plan_id').html(loading);
							populateListPoliza_Plan('box-poliza_plan_id', 'box', $('#box-subtipo_poliza_id').val(), $('#box-seguro_id').val());
							$('#box-poliza_plan_flag').val(1);
						}
						else {
							$('#box-poliza_vigencia').children().each(function() {
								$(this).attr('disabled', false);
							});
							$('#box-poliza_recargo').attr('readonly', false);
							$('#box-poliza_premio').attr('readonly', false);
							$('#box-poliza_plan_id, #box-poliza_pack_id').removeClass('required');
							$('.poliza_plan').hide();
							$('#box-poliza_plan_flag').val(0);
						}
					}).change(false);
					$("#box-poliza_vigencia").change(function () {
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
							} else {
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
					}).change();
					$("#box-poliza_vigencia_dias").change(function () {
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
					$("#box-poliza_medio_pago, #box-poliza_cuotas").change(function() {
						var cuotas = '';
						$('#pfc')[($('#box-poliza_medio_pago').val()=='Directo'?'show':'hide')]().children().eq(0).attr('disabled', ($('#box-poliza_medio_pago').val()=='Directo'?false:true));
						if ($('#box-subtipo_poliza_id').val()==6) {
							switch ($('#box-poliza_medio_pago').val()) {
							case 'Cuponera':
								cuotas = 5;
								break;
							case 'Directo':
								cuotas = 5;
								$('#box-sucursal_pfc').attr('checked', true);
								break;
							case 'Débito Bancario':
							case 'Tarjeta de Crédito':
								cuotas = 6;
								break;
							}
						}
						else {
							// casos generales otros riesgos/personas
							
						}
						$('#box-poliza_cant_cuotas').val(cuotas);
						if ($('#box-poliza_medio_pago').val()=='Directo') $('#cuota_monto').show();
						else $('#cuota_monto').hide();
					}).change();
					$('#box-poliza_plan_id').change(function() {
						$('#box-poliza_pack_id').html(loading);
						populateListPoliza_Pack('box-poliza_pack_id', 'box', $(this).val());
					})
					$('#box-poliza_pack_id').change(function() {
						populateFormPolizaPackPremio($(this).val());
					})
					// Set default values
					$('#box-poliza_fecha_solicitud').val(Date.today().clearTime().toString("dd/MM/yy"));
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_id": {
								required: true
							},
							"box-productor_seguro_id": {
								required: true
							},
							"box-poliza_vigencia": {
								required: true
							},
							"box-poliza_validez_desde": {
								required: true,
								dateAR: true
							},
							"box-poliza_validez_hasta": {
								required: true,
								dateAR: true,
								enddate: "#box-poliza_validez_desde"
							},
							"box-poliza_cuotas": {
								required: true
							},
							"box-poliza_cant_cuotas": {
								required: true,
								digits: true,
								min: 1,
								max: 255
							},
							"box-poliza_fecha_solicitud": {
								dateAR: true
							},
							"box-poliza_fecha_emision": {
								dateAR: true
							},
							"box-poliza_fecha_recepcion": {
								dateAR: true
							},
							"box-poliza_fecha_entrega": {
								dateAR: true
							},
							"box-poliza_prima": {
								min: 0,
								max: 99999999.99
							},
							"box-poliza_premio": {
								required: true,
								min: 0,
								max: 99999999.99
							},
							"box-poliza_medio_pago": {
								required: true
							},
							"box-poliza_recargo": {
								min: 0,
								max: 100
							}
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?\n\nEsta acción no puede deshacerse.')) {
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								processFormPolizaRen(id);
							}
						}
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxPolizaDet = function (id, fromcreate, flota) {
		flota = flota || 0;
		$.colorbox({
			title: 'Registro',
			href: 'box-polizadet.php?section=2&id=' + id,
			width: '900px',
			height: '100%',
			onComplete: function () {

				// Set button text
				if (fromcreate === true) {
					$(".btnBox").val('Siguiente');
				} else if(fromcreate == 'ren') {
					$(".btnBox").val('Renovar');
				} else {
					// Navegación
					$("#navegacion-datos").click(function() {
						openBoxModPoliza(id);
					});
					$('#navegacion-cert').click(function() {
						openBoxPolizaCert(id);
					});
					$('#navegacion-cuotas').click(function() {
						openBoxCuota(id);
					});
					$(".btnBox").val('Aceptar');
				}

				// Initialize buttons
				$(".btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxPolizaDet(id, flota)).then(function () {

					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');

					$('#box-suma_asegurada').val($('#box-valor_total').val());
					
					if (fromcreate===true) {
						$('#box-automotor_tipo_id').val(1);
						$('#box-automotor_tipo_id').change();
					}

					// Validate form
					var validateForm = $("#frmBox").validate();

					// Button action
					$(".btnBox").click(function () {
						// if (customValidations()) {
						if (validateForm.form() && customValidations()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							processFormPolizaDet(id, fromcreate, flota);
						}
						// }
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxPolizaCert = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-polizacert.php?section=3&id=' + id,
			width: '750px',
			height: '600px',
			onComplete: function () {

				// Navegación
				$("#navegacion-detalle").click(function() {
					openBoxPolizaDet(id, false);
				});
				$("#navegacion-datos").click(function() {
					openBoxModPoliza(id);
				});
				$('#navegacion-cuotas').click(function() {
					openBoxCuota(id);
				});
				
				$('#btnFinalizar').button().click(function() {
					$.colorbox.close();
				});

				populateDiv_Envios('1,2,3,4,7,8', id);

				// Button action
				$("#btnCCp").button().click(function () {
					window.open('print-poliza.php?type=cc&id=' + id + '&print');
				});
				$("#btnCCd").button().click(function () {
					window.open('print-poliza.php?type=cc&id=' + id);
				});
				$("#btnPE").button().click(function () {
					window.open('print-poliza.php?type=pe&mc=0&id=' + id);
				});
				$("#btnPEMC").button().click(function () {
					window.open('print-poliza.php?type=pe&mc=1&id=' + id);
				});
				$("#btnPR").button().click(function () {
					window.open('print-poliza.php?type=pe&re=1&id=' + id);
				});
				
				// Button email action
				$('#doc').buttonset();
				$("#btnBox1").button().click(function () {
					$('#btnBox1').button("option", "disabled", true);
					var url = '';
					switch ($('input[name="type"]:checked', '#frmBox1').val()) {
						case 'fotos':
							url = 'send-fotos.php';
							break;
						case 'rast':
							url = 'send-rastreo.php';
							break;
						case 'insp':
							url = 'send-inspeccion.php';
							break;
						default:
							url = 'print-poliza.php';
							break;
					}
					$.ajax({
						url: url,
						data: $('#frmBox1').serializeArray(),
						success: function(data) {
							showBoxConf(data, false, 'always', 3000, function () {
								$('#btnBox1').button("option", "disabled", false);
								populateDiv_Envios('1,2,3,4,7,8', id);
							});
						},
						error: function() {
							showBoxConf('Error. Intente nuevamente.', false, 'always', 3000, function () {
								$('#btnBox1').button("option", "disabled", false);
							});
						}
					});
					return false;
				});
			}
		});
	}
	openBoxCuota = function (id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-cuota.php?section=4&id='+id,
			width: '900px',
			height: '100%',
			onComplete: function () {

				$('#btnBox').button();
				
				// Navegación
				$("#navegacion-detalle").click(function() {
					openBoxPolizaDet(id, false);
				});
				$("#navegacion-datos").click(function() {
					openBoxModPoliza(id);
				});
				$('#navegacion-cert').click(function() {
					openBoxPolizaCert(id);
				});

				// Populate DIVs
				populateDiv_Poliza_Info(id);
				populateDiv_Cuotas(id);
				populateDiv_Envios('6', id, '1');
				populateDiv_CuotasOperaciones(id, '2');

				formDisable('frmBox', 'ui', true);

				$.when(
					populateFormBoxPolizaObservaciones(id)
				).then(function () {
					$('#btnBox').click(function () {
						updateFormPolizaObservaciones(id);
						return false;
					})
					
					// Email form
					$("#btnBox1").button().click(function () {
						$('#btnBox1').button("option", "disabled", true);
						var arr = $('#frmBox1').serializeArray();
						arr.push({name: 'id', value: $('#cuota-id').val()});
						$.ajax({
							url: 'print-cuota.php',
							data: arr,
							success: function(data) {
								alert(data);
								$('#btnBox1').button("option", "disabled", false);
								populateDiv_Envios('6', id, '1');
							},
							error: function() {
								alert('Error. Intente nuevamente.');
								$('#btnBox1').button("option", "disabled", false);
							}
						});
						return false;
					});
					$("#btnVerPDF").button().click(function() {
						window.open('print-cuota.php?id='+$('#cuota-id').val());
					})
					formDisable('frmBox', 'ui', false);
				});
			}
		});
	}
	editInBoxAsegurado = function (id) {
		// Disable form
		formDisable('frmBoxAsegurado', 'ui', true);
		$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
		$.when(populateFormBoxAsegurado(id)).then(function () {
			$("#box-accidentes_asegurado_beneficiario_nombre, #box-accidentes_asegurado_beneficiario_documento, #box-accidentes_asegurado_beneficiario_nacimiento, #box-accidentes_asegurado_beneficiario_tomador").prop('disabled', !($("#box-accidentes_asegurado_beneficiario").prop('checked')));
			var suma_asegurada = isNaN($("#box-accidentes_asegurado_suma_asegurada").val()) ? 0 : $("#box-accidentes_asegurado_suma_asegurada").val();
			var gastos_medicos = isNaN($("#box-accidentes_asegurado_gastos_medicos").val()) ? 0 : $("#box-accidentes_asegurado_gastos_medicos").val();
			$("#box-accidentes_asegurado_total").val(Number(suma_asegurada) + Number(gastos_medicos));

			// Append hidden input to form
			$('<input>').prop({
				type: 'hidden',
				id: 'box-accidentes_asegurado_id',
				name: 'box-accidentes_asegurado_id'
			}).val(id).appendTo($('#frmBoxAsegurado'));
			$("#box-action").val('edit');
			$("#btnBoxAseguradoReset").button('option', 'label', 'Cancelar').click(function () {
				// Clear form
				$('#frmBoxAsegurado').each(function () {
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
			formDisable('frmBoxAsegurado', 'ui', false);
			$("#box-accidentes_asegurado_nombre").focus();
		});
	}
	openBoxAltaEndoso = function () {
		var dfd = new $.Deferred();
		$.colorbox({
			title: 'Endoso',
			href: 'box-endoso_alta.php',
			width: '700px',
			height: '100%',
			onComplete: function () {

				$("#btnBox").button();


				formDisable('frmSelectPoliza', 'normal', false);
				formDisable('frmBox', 'ui', true);

				$.when(
					populateListEndosoTipo('box-endoso_tipo_id', 'box')
				).then(function () {
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					$('#box-endoso_fecha_pedido').val(Date.today().clearTime().toString("dd/MM/yy"));

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-endoso_fecha_pedido": {
								required: true,
								dateAR: true
							},
							"box-endoso_tipo": {
								required: true
							},
							"box-endoso_fecha_compania": {
								dateAR: true
							},
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action
					$("#btnBox").click(function () {
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
				$("#BtnSearchPoliza").click(function () {
					// If a field was completed
					if ($('#box0-poliza_numero').val() != '' || $('#box0-cliente_nombre').val() != '') {
						populateDiv_Poliza_Results('endoso');
					} else {
						$('#divBoxPolizaSearchResults').html('Debe ingresar información en al menos un campo.');
					}
				});
				// Submit on Enter
				$("#frmSelectPoliza :input[type=text]").each(function () {
					$(this).keypress(function (e) {
						if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
							$("#BtnSearchPoliza").click();
						}
					});
				});
				// Enable form
				formDisable('frmSelectPoliza', 'normal', false);
				// Set focus on search
				$("#box0-poliza_numero").focus();
				dfd.resolve();
			}

		});
		return dfd.promise();	
	}
	openBoxModEndoso = function (id) {
		$.colorbox({
			title: 'Endoso',
			href: 'box-endoso_mod.php?id='+id,
			width: '700px',
			height: '100%',
			onComplete: function () {

				$("#btnBox").button();
				$("#btnBoxExport").button();
				$('#enviar_a').buttonset();
				
				formDisable('frmBox', 'ui', true);

				populateDiv_Fotos('endoso', id);
				$("#endoso_id").val(id);
				// AJAX file form
				$("#fileForm").ajaxForm({
					beforeSend: function () {
						$("#fotosLoading").show();
					},
					uploadProgress: function (event, position, total, percentComplete) {

					},
					complete: function (xhr) {
						if (xhr.responseText.indexOf('Error:') != -1) {
							alert(xhr.responseText);
						} else {
							$("#fotosLoading").hide();
						}
						populateDiv_Fotos('endoso', id);
					}
				});

				$.when(
					populateFormBoxEndoso(id)
				).then(function () {
					var poliza_id = $('#box-poliza_id').val();
					
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');

					$('#box-endoso_tipo_id').change(function() {
						var tipo_endoso = $(':selected', this).parent().attr('label');
						$('#mail-subject').val('Pedido de '+(tipo_endoso=='Anulación'?'anulación':'endoso')+' PZA. '+$('#box-poliza_numero').val());
					}).change();

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-endoso_fecha_pedido": {
								required: true,
								dateAR: true
							},
							"box-endoso_tipo": {
								required: true
							},
							"box-endoso_fecha_compania": {
								dateAR: true
							},
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormEndoso();
						}
					});
					$("#btnBoxExport").click(function () {
						window.open('print-poliza.php?type=pe&en=1&id=' + poliza_id + '&endoso_id=' + id);
					})
					
					populateDiv_Envios('5', poliza_id);
					
					// Email form
					$("#btnBox1").button().click(function () {
						$('#btnBox1').button("option", "disabled", true);
						var arr = $('#frmBox1').serializeArray();
						arr.push({name: 'id', value: $('#box-poliza_id').val()});
						arr.push({name: 'endoso_id', value: id});
						arr.push({name: 'type', value: 'peen'});
						arr.push({name: 'en', value: 1});
						$.ajax({
							url: 'print-poliza.php',
							data: arr,
							success: function(data) {
								alert(data);
								$('#btnBox1').button("option", "disabled", false);
								populateDiv_Envios('5', poliza_id);
							},
							error: function() {
								alert('Error. Intente nuevamente.');
								$('#btnBox1').button("option", "disabled", false);
							}
						});
						return false;
					});
					
					// Enable form
					formDisable('frmBox', 'ui', false);
				});
			}

		});
	}
	openBoxEndosos = function (id, poliza_numero) {
		$.colorbox({
			title: 'Póliza/Endosos',
			href: 'box-poliendosos.php',
			width: '700px',
			height: '600px',
			onComplete: function () {

				// -------------------- GENERAL ---------------------

				$('#btnNuevoEndoso').button();

				// Populate DIVs
				populateDiv_Poliza_Info(id);
				populateDiv_Endosos(id, poliza_numero);
			}
		});
	}
	openBoxPayCuota = function (poliza_id, cuota_id) {
		$.colorbox({
			title: 'Pagar Cuota',
			href: 'box-pay_cuota.php',
			width: '900px',
			height: '100%',
			onComplete: function () {

				$("#btnBox").button();
				$("#btnCancel").button().click(function () {
					openBoxCuota(poliza_id);
					return false;
				});

				formDisable('frmBox', 'ui', true);

				// Populate DIVs
				populateDiv_Poliza_Info(poliza_id);

				$.when(
					populateFormBoxPayCuota(cuota_id)
				).then(function () {
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					initDateTimePicker('box-datetime');
					$('#box-cuota_fe_pago').val(Date.now().toString("dd/MM/yyyy HH:mm"));

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-cuota_fe_pago": {
								required: true,
								datetime: true
							},
							"box-cuota_monto": {
								required: true
							},
							"box-cuota_vencimiento": {
								dateAR: true
							},
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date, .box-datetime').datepicker('option', 'dateFormat', 'yy-mm-dd');
							processFormPayCuota(poliza_id, cuota_id);
						}
					});
					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	editInBoxContacto = function (id) {
		// Disable form
		formDisable('frmBoxContacto', 'ui', true);
		$.when(populateFormBoxContacto(id)).then(function () {

			// Append hidden input to form
			$('<input>').prop({
				type: 'hidden',
				id: 'box-contacto_id',
				name: 'box-contacto_id'
			}).val(id).appendTo($('#frmBoxContacto'));
			$("#box-action").val('edit');
			$("#btnBoxResetContacto").button('option', 'label', 'Cancelar');
			$("#btnBoxContacto").button('option', 'label', 'Guardar');
			formDisable('frmBoxContacto', 'ui', false);
			$('#box-localidad_id').trigger('chosen:updated');
			$("#box-contacto_domicilio").focus();
		});
	}
	openBoxAltaCob = function (id) {
		$.colorbox({
			title: 'Seguro/Cobertura',
			href: 'box-segcob_alta.php',
			width: '700px',
			height: '600px',
			onComplete: function () {

				// -------------------- GENERAL ---------------------
				
				$("#btnBox").button();
				
				formDisable('frmBox', 'ui', false);
				$("#btnBoxCancelar").button().click(function(event) {
					event.preventDefault();
					openBoxModSeguro(id);
				});
				
				$('<input>').prop({
					type: 'hidden',
					id: 'box-seguro_id',
					name: 'box-seguro_id'
				}).val(id).appendTo($('#frmBox'));

				$.when(
					populateListLimiteRC('box-seguro_cobertura_tipo_limite_rc_id', 'box')
				).then(function() {
					
					$("#box-seguro_cobertura_tipo_antiguedad").keyup(function() {
						if ($(this).val() != '') {
							var d = new Date;
							$('#antiguedad').text('('+d.getFullYear() + ' - ' + (d.getFullYear()-parseInt($(this).val())).toString() + ')');
						}
						else {
							$('#antiguedad').text('');
						}
					});
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_cobertura_tipo_nombre": {
								required: true
							},
							"box-seguro_cobertura_tipo_limite_rc_id": {
								required: true
							},
							"box-seguro_cobertura_tipo_gruas": {
								required: true
							},
							"box-seguro_cobertura_tipo_franquicia": {
								required: function() {
									return $('#box-seguro_cobertura_tipo_todo_riesgo').prop('checked');
								}
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							insertFormSegCob(id);
						}
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
				});
			}
		});
	}
	openBoxModCob = function(id, seguro_id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-segcob_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();
				$("#btnBoxCancelar").button().click(function(event) {
					event.preventDefault();
					openBoxModSeguro(seguro_id);
				});

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxCob(id)).then(function () {
					
					$("#box-seguro_cobertura_tipo_antiguedad").keyup(function() {
						if ($(this).val() != '') {
							var d = new Date;
							$('#antiguedad').text('('+d.getFullYear() + ' - ' + (d.getFullYear()-parseInt($(this).val())).toString() + ')');
						}
						else {
							$('#antiguedad').text('');
						}
					}).keyup();
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-seguro_cobertura_tipo_nombre": {
								required: true
							},
							"box-seguro_cobertura_tipo_limite_rc_id": {
								required: true
							},
							"box-seguro_cobertura_tipo_gruas": {
								required: true
							},
							"box-seguro_cobertura_tipo_franquicia": {
								required: function() {
									return $('#box-seguro_cobertura_tipo_todo_riesgo').prop('checked');
								}
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							updateFormSegCob(seguro_id);
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxAltaCod = function (id) {
		$.colorbox({
			title: 'Productor/Seguro/Código',
			href: 'box-prodseg_alta.php',
			width: '700px',
			height: '400px',
			onComplete: function () {

				// -------------------- GENERAL ---------------------
				
				$("#btnBox").button();
				
				formDisable('frmBox', 'ui', false);
				$("#btnBoxCancelar").button().click(function(event) {
					event.preventDefault();
					openBoxModSeguro(id);
				});
				
				$('<input>').prop({
					type: 'hidden',
					id: 'box-seguro_id',
					name: 'box-seguro_id'
				}).val(id).appendTo($('#frmBox'));

				$.when(
					populateListProductor("box-productor_id", "box"),
					populateListSuc("box-sucursal_id", "box"),
					populateListOrg("box-organizador_id", "box")
				).then(function() {
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-productor_id": {
								required: true
							},
							"box-sucursal_id": {
								required: true
							},
							"box-productor_seguro_codigo": {
								required: true
							}
						}
					});

					$('#box-productor_seguro_organizacion_flag').change(function() {
						$('#box-productor_seguro_organizacion_nombre, #box-productor_seguro_organizacion_tipo_persona, #box-productor_seguro_organizacion_matricula, #box-productor_seguro_organizacion_cuit').prop('disabled', !$(this).prop('checked'));
						if ($(this).prop('checked')) $('#box-productor_seguro_organizacion_nombre').focus();
					});
					
					// Button action
					$("#btnBox").click(function () {
						insertFormProdSeg(id);
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					
					$('#box-productor_seguro_organizacion_flag').change();
				});
			}
		});
	}
	openBoxModCod = function(id, seguro_id) {
		$.colorbox({
			title: 'Productor/Seguro/Código',
			href: 'box-prodseg_mod.php',
			width: '700px',
			height: '400px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();
				$("#btnBoxCancelar").button().click(function(event) {
					event.preventDefault();
					openBoxModSeguro(seguro_id);
				});
				
				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxCod(id)).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-productor_id": {
								required: true
							},
							"box-seguro_id": {
								required: true
							},
							"box-sucursal_id": {
								required: true
							},
							"box-productor_seguro_codigo": {
								required: true
							}
						}
					});
					
					$('#box-productor_seguro_organizacion_flag').change(function() {
						$('#box-productor_seguro_organizacion_nombre, #box-productor_seguro_organizacion_tipo_persona, #box-productor_seguro_organizacion_matricula, #box-productor_seguro_organizacion_cuit').prop('disabled', !$(this).prop('checked'));
						if ($(this).prop('checked')) $('#box-productor_seguro_organizacion_nombre').focus();
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							updateFormSegCod(id);
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
					$('#box-productor_seguro_organizacion_flag').change();
				});

			}
		});
	}
	openBoxPolizaFotos = function(id) {
		$.colorbox({
			title: 'Fotos',
			href: 'box-polizafotos.php',
			width: '700px',
			height: '100%',
			onComplete: function() {
				populateDiv_Fotos('automotor', id, 'Automotor');
				populateDiv_Fotos('automotor_micrograbado', id, 'Micrograbado');
				populateDiv_Fotos('automotor_gnc', id, 'GNC');
				populateDiv_Archivos('automotor_cert_rodamiento', id, 'CertRodamiento');
				populateDiv_Fotos('automotor_cedula_verde', id, 'CedulaVerde');
				
				// AJAX file form
				$(".fileForm").each(function (i,e) {
					$(e).ajaxForm({
						data: { automotor_id: id },
						beforeSend: function () {
							$("#fotosLoading"+$(e).prop('id')).show();
						},
						uploadProgress: function (event, position, total, percentComplete) {

						},
						complete: function (xhr) {
							if (xhr.responseText.indexOf('Error:') != -1) {
								alert(xhr.responseText);
							} else {
								$("#fotosLoading"+$(e).prop('id')).show().hide();
							}
							switch ($(e).prop('id')) {
							case 'cert_rodamiento':
								populateDiv_Archivos('automotor_'+$(e).prop('id'), id, $(e).attr('suffix'));
								break;
							default:
								populateDiv_Fotos(($(e).prop('id')!='automotor'?'automotor_':'')+$(e).prop('id'), id, $(e).attr('suffix'));
								break;
							}
						}
					});
				})
			}
		});
	}
	openBoxClieAcciones = function(id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-cliente_acciones.php',
			width: '700px',
			height: '250px',
			onComplete: function () {
				$('#btnAutomotor').button().click(function() {
					openBoxAltaPoliza(2, 6, id);
				});
				$('#btnPatrimoniales').button().click(function() {
					openBoxAltaPoliza(2, null, id);
				});
				$('#btnPersonas').button().click(function() {
					openBoxAltaPoliza(3, null, id);
				});
				$('#btnNada').button().click(function() {
					$.colorbox.close();
				});
				
			}
		});
		
	}
	openBoxPolizaFlota = function(tipo, id, fromcreate) {
		fromcreate = fromcreate || false;
		$.colorbox({
			title: 'Flota',
			href: 'box-poliza_flota.php?id=' + id + '&tipo='+tipo,
			width: '750px',
			height: '400px',
			onComplete: function () {
				$('#create').button().click(function() {
					switch (tipo) {
					case 'detalle':
						openBoxPolizaDet(id, fromcreate, 'new');
						break;
					}
				});
				if (fromcreate) {
					$('#certificados').button().click(function() {
						openBoxPolizaCert(id);
					});
				}
				else 
					$('#certificados').remove();
				$('.flotaedit').button().click(function() {
					switch (tipo) {
					case 'detalle':
						openBoxPolizaDet(id, false, $(this).attr('polizadet'));
						break;
					case 'imagenes':
						openBoxPolizaFotos($(this).attr('polizadet'));
						break;
					case 'siniestros':
						openBoxSiniestros($(this).attr('polizadet'));
						break;
					}
				});
			}
		});
	}
	openBoxAltaZonaRiesgo = function(id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-rie_alta.php',
			width: '700px',
			height: '450px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();
				$("#btnBoxCancelar").button().click(function(event) {
					event.preventDefault();
					openBoxModSeguro(id);
				});
				
				// Disable form
				formDisable('frmBox', 'ui', true);

				$('<input>').prop({
					type: 'hidden',
					id: 'box-seguro_id',
					name: 'box-seguro_id'
				}).val(id).appendTo($('#frmBox'));

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-zona_riesgo_nombre": {
							required: true
						}
					}
				});

				// Button action
				$("#btnBox").click(function () {
					if (validateForm.form()) {
						insertFormRie(id);
					};
				});
				
				// Enable form
				formDisable('frmBox', 'ui', false);
			}
		});
	}
	openBoxModZonaRiesgo = function(id, seguro_id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-rie_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();
				$("#btnBoxCancelar").button().click(function(event) {
					event.preventDefault();
					openBoxModSeguro(seguro_id);
				});
				
				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxRie(id)).then(function () {
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-zona_riesgo_nombre": {
								required: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							updateFormRie(seguro_id);
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
				});

			}
		});
	}
	openBoxEmitirRecibo = function(cliente_id) {
		$.colorbox.settings.onClosed = null;
		$.colorbox({
			title: 'Endoso',
			href: 'box-emitirrecibo.php',
			width: '900px',
			height: '100%',
			onComplete: function () {

				$("#btnBox").button();
				
				
				// FORM SELECT POLIZA
				// Initialize special fields
				initAutocompletePoliza('box0-poliza_numero', 'box');
				
				// Assign functions to buttons
				$("#BtnSearchPoliza").click(function () {
					// If a field was completed
					if ($('#box0-poliza_numero').val() != '' || $('#box0-cliente_id').val() != '' || $('#box0-patente').val() != '') {
						populateDiv_Poliza_Results('recibo');
					} else {
						$('#divBoxPolizaSearchResults').html('Debe ingresar información en al menos un campo.');
					}
				});
				// Submit on Enter
				$("#frmSelectPoliza :input[type=text]").each(function () {
					$(this).keypress(function (e) {
						if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
							$("#BtnSearchPoliza").click();
						}
					});
				});
				$.when(populateListClientes('box0-cliente_id', 'main')).then(function() {
					$('#box0-cliente_id').chosen().change(function() {
						$("#BtnSearchPoliza").click();
					});
					$('#box0_cliente_id_chosen .chosen-drop .chosen-search input').focus();
					
					// Enable form
					formDisable('frmSelectPoliza', 'normal', false);
					if (cliente_id) {
						$('#box0-cliente_id').val(cliente_id).trigger("chosen:updated");
						$("#BtnSearchPoliza").click();
					}
				});
			}
			
		});
	}
	openBoxAltaOrg = function() {
		$.colorbox({
			title: 'Registro',
			href: 'box-org_alta.php',
			width: '700px',
			height: '520px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate drop-downs, then initialize form
				$.when(
					
				).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-organizador_nombre": {
								required: true
							},
							"box-organizador_iva": {
								required: true
							},
							"box-organizador_cuit": {
								required: true
							},
							"box-organizador_matricula": {
								required: true
							},
							"box-organizador_email": {
								email: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								insertFormOrg();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxModOrg = function(id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-org_mod.php',
			width: '700px',
			height: '520px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxOrg(id)).then(function () {

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-organizador_nombre": {
								required: true
							},
							"box-organizador_iva": {
								required: true
							},
							"box-organizador_cuit": {
								required: true
							},
							"box-organizador_matricula": {
								required: true
							},
							"box-organizador_email": {
								email: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormOrg();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);

				});

			}
		});
	}
	openBoxSiniestros = function(id) {
		$.colorbox({
			title: 'Póliza/Siniestros',
			href: 'box-polizasiniestros.php?section=1',
			width: '700px',
			height: '600px',
			onComplete: function() {
				
				$('#btnNuevoSiniestro').button().click(function() {
					openBoxSiniestrosAlta(id);
				});
				
				// Populate DIVs
				populateDiv_Poliza_Info(id, 'automotor');
				populateDiv_Siniestros(id);
			}
		})
	}
	openBoxSiniestrosAlta = function(id) {
		$.colorbox({
			title: 'Siniestro',
			href: 'box-siniestro_alta.php?section=2',
			width: '900px',
			height: '100%',
			onComplete: function () {

				$("#btnBox").button();


				formDisable('frmSelectPoliza', 'normal', false);
				formDisable('frmBox', 'ui', true);

				// Populate drop-downs, then initialize form
				$.when(
					
				).then(function () {

					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');

					$('#frmBox input, #frmBox select').not('input[type="checkbox"]').change(function() { 
						if ($(this).val()!='') {
							$(this).css('box-shadow', '0px 0px 2pt 0.5pt limegreen');
						}
					}).change();

					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-fecha_denuncia": {
								required: true
							},
							"box-hora_denuncia": {
								required: true
							},
							"box-lugar_denuncia": {
								required: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea crear el registro?')) {
								$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
								insertFormSiniestro();
							}
						};
					});

					// FORM SELECT POLIZA
					// Initialize special fields
					initAutocompletePoliza('box0-poliza_numero', 'box');
					// Assign functions to buttons
					$("#BtnSearchPoliza").click(function () {
						// If a field was completed
						if ($('#box0-poliza_numero').val() != '' || $('#box0-cliente_nombre').val() != '') {
							populateDiv_Poliza_Results('siniestro');
						} else {
							$('#divBoxPolizaSearchResults').html('Debe ingresar información en al menos un campo.');
						}
					});
					// Submit on Enter
					$("#frmSelectPoliza :input[type=text]").each(function () {
						$(this).keypress(function (e) {
							if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
								$("#BtnSearchPoliza").click();
							}
						});
					});
					// Enable form
					formDisable('frmSelectPoliza', 'normal', false);
					$('#box0-poliza_numero').focus();
					if (id != undefined) {
						$.ajax({
							url: "get-json-prodseg_codigo.php?id="+id,
							dataType: 'json',
							success: function (j) {
								if (j.error == 'expired') {
									sessionExpire(context);
								} else if (j.empty == true) {
									// Record not found
									$.colorbox.close();
								} else {
									assignPolizaToSiniestro(id, j.productor_seguro_codigo);
								}
							}
						});
					}

				});

			}
		});
	}
	openBoxModSiniestro = function(id, focuselement) {
		focuselement = focuselement || undefined;
		$.colorbox({
			title: 'Siniestro',
			href: 'box-siniestro_mod.php?section=2',
			width: '900px',
			height: '100%',
			onComplete: function () {
				$("#btnBox, #btnNuevoDatosTercero, #btnNuevoLesionesTercero").button();
				$("#btnBoxExport").button();

				formDisable('frmBox', 'ui', true);
				
				$.when(
					populateFormBoxSiniestro(id)
				).then(function () {
					
					populateDiv_SiniestroLesionesTerceros(id);
					
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
					$('#box-siniestro_id').val(id);
					
					$('#box-conductor_asegurado').change(function() {
						$('.conductor-vehiculo input, .conductor-vehiculo select').not(this).prop('disabled', $(this).val()==1);
					}).change();
					
					$('#frmBox input, #frmBox select').not('input[type="checkbox"]').change(function() { 
						if ($(this).val()!='') {
							$(this).css('box-shadow', '0px 0px 2pt 0.5pt limegreen');
						}
					}).change();
					
					$('.draggable-autos').draggable();
					$('.draggable').draggable({
						helper: 'clone',
					});
					$('#droppable').droppable({
						drop: function(event, ui) {
							if (ui.helper.hasClass('draggable')) {
								var offset = ui.helper.offset();
								var position = ui.helper.position();
								ui.helper.css({'position': 'absolute', 'top': position.top, 'left': position.left}).removeClass('draggable').addClass('dragged').draggable({'helper': 'original'}).appendTo( this ).offset( offset );
								$.ui.ddmanager.current.cancelHelperRemoval = true;
							}
						}
					});
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-fecha_denuncia": {
								required: true
							},
							"box-hora_denuncia": {
								required: true
							},
							"box-lugar_denuncia": {
								required: true
							}
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							$.when(renderCroquis()).then(function() {
								updateFormSiniestro(id);
							});
						}
					});
					// Navegación
					$("#navegacion-siniestros").click(function() {
						openBoxSiniestros($('#box-automotor_id').val());
					});
					$('#navegacion-cert_siniestro').click(function() {
						openBoxSiniestroCert(id);
					});
					
					$("#btnNuevoDatosTercero").click(function(event) {
						openBoxAltaSiniestroDatosTercero(id);
						event.preventDefault();
					});
					$("#btnNuevoLesionesTercero").click(function(event) {
						openBoxAltaSiniestroLesionesTercero(id);
						event.preventDefault();
					});
					
					formDisable('frmBox', 'ui', false);
					if (focuselement!=undefined) {
						$('#'+focuselement)[0].scrollIntoView();
					}
				});
			}
		});
	}
	openBoxAltaSiniestroDatosTercero = function(id) {
		$.colorbox({
			title: 'Siniestro/Datos de tercero',
			href: 'box-siniestrodatostercero_alta.php',
			width: '900px',
			height: '100%',
			onComplete: function() {
				$('#btnBox, #btnBoxCancelar').button();
				
				formDisable('frmBox', 'ui', true);
				
				initDatePickersDaily('box-date', false, null);
				$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
				
				$('#frmBox input, #frmBox select').not('input[type="checkbox"]').change(function() { 
					if ($(this).val()!='') {
						$(this).css('box-shadow', '0px 0px 2pt 0.5pt limegreen');
					}
				}).change();
				
				$('#box-siniestro_id').val(id);
				
				var validateForm = $("#frmBox").validate({
					rules: {
						
					},
					errorPlacement: function (error, element) {
						error.insertAfter(element.parent("p").children().last());
					}
				});
				
				// Button action
				$("#btnBox").click(function () {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							insertFormSiniestroDatosTercero(id);
						}
					};
				});
				$("#btnBoxCancelar").click(function() {
					openBoxModSiniestro(id, 'fieldset-datos-terceros');
				});
				
				formDisable('frmBox', 'ui', false);
			}
		})
	}
	openBoxModSiniestroDatosTercero = function(id, siniestro_id) {
		$.colorbox({
			title: 'Siniestro/Datos de tercero',
			href: 'box-siniestrodatostercero_mod.php',
			width: '900px',
			height: '100%',
			onComplete: function() {
				$('#btnBox, #btnBoxCancelar').button();
				
				formDisable('frmBox', 'ui', true);
				
				$.when(
					populateFormBoxSiniestroDatosTercero(id)
				).then(function() {
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
					$('#frmBox input, #frmBox select').not('input[type="checkbox"]').change(function() { 
						if ($(this).val()!='') {
							$(this).css('box-shadow', '0px 0px 2pt 0.5pt limegreen');
						}
					}).change();
					
					$('#box-siniestros_datos_terceros_id').val(id);
					
					var validateForm = $("#frmBox").validate({
						rules: {
						
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormSiniestroDatosTercero(siniestro_id);
						};
					});
					$("#btnBoxCancelar").click(function() {
						openBoxModSiniestro(siniestro_id, 'fieldset-datos-terceros');
					});
					
					formDisable('frmBox', 'ui', false);
					
				});
				
			}
		})
	}
	openBoxAltaSiniestroLesionesTercero = function(id) {
		$.colorbox({
			title: 'Siniestro/Lesiones a tercero',
			href: 'box-siniestrolesionestercero_alta.php',
			width: '900px',
			height: '100%',
			onComplete: function() {
				$('#btnBox, #btnBoxCancelar').button();
				
				formDisable('frmBox', 'ui', true);
				
				initDatePickersDaily('box-date', false, null);
				$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
				
				$('#frmBox input, #frmBox select').not('input[type="checkbox"]').change(function() { 
					if ($(this).val()!='') {
						$(this).css('box-shadow', '0px 0px 2pt 0.5pt limegreen');
					}
				}).change();
				
				$('#box-siniestro_id').val(id);
				
				var validateForm = $("#frmBox").validate({
					rules: {
						
					},
					errorPlacement: function (error, element) {
						error.insertAfter(element.parent("p").children().last());
					}
				});
				
				// Button action
				$("#btnBox").click(function () {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							insertFormSiniestroLesionesTercero(id);
						}
					};
				});
				$("#btnBoxCancelar").click(function() {
					openBoxModSiniestro(id, 'fieldset-lesiones-terceros');
				});
				
				formDisable('frmBox', 'ui', false);
			}
		})
	}
	openBoxModSiniestroLesionesTercero = function(id, siniestro_id) {
		$.colorbox({
			title: 'Siniestro/Datos de tercero',
			href: 'box-siniestrolesionestercero_mod.php',
			width: '900px',
			height: '100%',
			onComplete: function() {
				$('#btnBox, #btnBoxCancelar').button();
				
				formDisable('frmBox', 'ui', true);
				
				$.when(
					populateFormBoxSiniestroLesionesTercero(id)
				).then(function() {
					initDatePickersDaily('box-date', false, null);
					$('.box-date').datepicker('option', 'dateFormat', 'dd/mm/yy');
					
					$('#frmBox input, #frmBox select').not('input[type="checkbox"]').change(function() { 
						if ($(this).val()!='') {
							$(this).css('box-shadow', '0px 0px 2pt 0.5pt limegreen');
						}
					}).change();
					
					$('#box-siniestros_lesiones_terceros_id').val(id);
					
					var validateForm = $("#frmBox").validate({
						rules: {
						
						},
						errorPlacement: function (error, element) {
							error.insertAfter(element.parent("p").children().last());
						}
					});
					
					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							$('.box-date').datepicker('option', 'dateFormat', 'yy-mm-dd');
							updateFormSiniestroLesionesTercero(siniestro_id);
						};
					});
					$("#btnBoxCancelar").click(function() {
						openBoxModSiniestro(siniestro_id, 'fieldset-lesiones-terceros');
					});
					
					formDisable('frmBox', 'ui', false);
					
				});
				
			}
		})
	}
	openBoxSiniestroCert = function(id) {
		$.colorbox({
			title: 'Registro',
			href: 'box-siniestrocert.php?section=3&id=' + id,
			width: '750px',
			height: '100%',
			onComplete: function () {
				populateDiv_Fotos('automotor_cedula_verde', $('#box-automotor_id').val(), 'CedulaVerde');
				populateDiv_Fotos('cliente', $('#box-cliente_id').val(), 'Registro');
				populateDiv_Archivos('siniestro_denuncia_policial', id, 'DenunciaPolicial');
				populateDiv_Poliza_Info($('#box-automotor_id').val(), 'automotor');

				// Navegación
				$("#navegacion-detalle").click(function() {
					openBoxPolizaDet(id, false);
				});
				$("#navegacion-datos").click(function() {
					openBoxModPoliza(id);
				});
				
				$('#btnFinalizar').button().click(function() {
					$.colorbox.close();
				});


				// Button action
				$("#btnDE").button().click(function () {
					window.open('print-siniestro.php?id=' + id);
				});
				$("#btnCV").button().click(function () {
					// window.open('print-poliza.php?type=cc&id=' + id);
				});
				$("#btnRE").button().click(function () {
					// window.open('print-poliza.php?type=pe&mc=0&id=' + id);
				});
				
				populateDiv_Envios('9', id);
				
				// Email form
				$('#doc').buttonset();
				$("#btnBox1").button().click(function () {
					$('#btnBox1').button("option", "disabled", true);
					var arr = $('#frmBox1').serializeArray();
					$.ajax({
						url: 'print-siniestro.php?email',
						data: arr,
						success: function(data) {
							alert(data);
							$('#btnBox1').button("option", "disabled", false);
							populateDiv_Envios('9', id);
						},
						error: function() {
							alert('Error. Intente nuevamente.');
							$('#btnBox1').button("option", "disabled", false);
						}
					});
					return false;
				});
				
				$("#navegacion-siniestros").click(function() {
					openBoxSiniestros($('#box-automotor_id').val());
				});
				$('#navegacion-detalle_siniestro').click(function() {
					openBoxModSiniestro(id);
				});
				
				// AJAX Forms
				$('#cedula_verde').ajaxForm({
					data: { automotor_id: $('#box-automotor_id').val() },
					beforeSend: function () {
						$("#fotosLoadingcedula_verde").show();
					},
					uploadProgress: function (event, position, total, percentComplete) {
						
					},
					complete: function (xhr) {
						if (xhr.responseText.indexOf('Error:') != -1) {
							alert(xhr.responseText);
						} else {
							$("#fotosLoadingcedula_verde").show().hide();
						}
						populateDiv_Fotos('automotor_cedula_verde', $('#box-automotor_id').val(), 'CedulaVerde');
					}
				});
				$('#cliente_foto').ajaxForm({
					data: { cliente_id: $('#box-cliente_id').val() },
					beforeSend: function () {
						$("#fotosLoadingcliente_foto").show();
					},
					uploadProgress: function (event, position, total, percentComplete) {
						
					},
					complete: function (xhr) {
						if (xhr.responseText.indexOf('Error:') != -1) {
							alert(xhr.responseText);
						} else {
							$("#fotosLoadingcliente_foto").show().hide();
						}
						populateDiv_Fotos('cliente', $('#box-cliente_id').val(), 'Registro');
					}
				});
				$('#denuncia_policial').ajaxForm({
					data: { siniestro_id: id },
					beforeSend: function () {
						$("#fotosLoadingdenuncia_policial").show();
					},
					uploadProgress: function (event, position, total, percentComplete) {
						
					},
					complete: function (xhr) {
						if (xhr.responseText.indexOf('Error:') != -1) {
							alert(xhr.responseText);
						} else {
							$("#fotosLoadingdenuncia_policial").show().hide();
						}
						populateDiv_Archivos('siniestro_denuncia_policial', id, 'DenunciaPolicial');
					}
				});
			}
		});
	}
	openBoxAltaAutomotorMarca = function() {
		$.colorbox({
			title: 'Marca',
			href: 'box-auto_marca_alta.php',
			width: '700px',
			height: '450px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-automotor_marca_nombre": {
							required: true
						}
					}
				});

				// Button action
				$("#btnBox").click(function () {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormAutoMarca();
						}
					};
				});

				// Enable form
				formDisable('frmBox', 'ui', false);
			}
		});
	}
	openBoxModAutoMarca = function(id) {
		$.colorbox({
			title: 'Marca',
			href: 'box-auto_marca_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxAutoMarca(id)).then(function () {
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-automotor_marca_nombre": {
								required: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormAutoMarca();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
				});

			}
		});
	}
	openBoxAltaAutomotorModelo = function() {
		$.colorbox({
			title: 'Modelo',
			href: 'box-auto_modelo_alta.php',
			width: '700px',
			height: '450px',
			onComplete: function () {
				
				populateListAutoMarca('box-automotor_marca_id', 'box');
				
				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-automotor_marca_id": {
							required: true
						},
						"box-automotor_modelo_nombre": {
							required: true
						}
					}
				});

				// Button action
				$("#frmBox").submit(function (event) {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormAutoModelo();
						}
					};
					event.preventDefault();
				});

				// Enable form
				formDisable('frmBox', 'ui', false);
			}
		});
	}
	openBoxModAutoModelo = function(id) {
		$.colorbox({
			title: 'Modelo',
			href: 'box-auto_modelo_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxAutoModelo(id)).then(function () {
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-automotor_modelo_nombre": {
								required: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormAutoModelo();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
				});

			}
		});
	}
	openBoxAltaAutomotorVersion = function() {
		$.colorbox({
			title: 'Versión',
			href: 'box-auto_version_alta.php',
			width: '700px',
			height: '450px',
			onComplete: function () {
				
				populateListAutoMarca('box-automotor_marca_id', 'box');
				
				$('#box-automotor_marca_id').change(function() {
					populateListAutoModelo('box-automotor_modelo_id', 'box', $(this).val());
				});
				
				var years = getYears(1950, 1).reverse();
				var options = '';
				$.each(years, function(i,e) {
					options += '<option value="' + e + '">' + e + '</option>';
				});
				$('#box-automotor_anos').html(options);
				
				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Validate form
				var validateForm = $("#frmBox").validate({
					rules: {
						"box-automotor_marca_id": {
							required: true
						},
						"box-automotor_modelo_id": {
							required: true
						},
						"box-automotor_version_nombre": {
							required: true
						},
						"box-automotor_anos[]": {
							required: true
						}
					}
				});

				// Button action
				$("#frmBox").submit(function (event) {
					if (validateForm.form()) {
						if (confirm('Está seguro que desea crear el registro?')) {
							insertFormAutoVersion();
						}
					};
					event.preventDefault();
				});

				// Enable form
				formDisable('frmBox', 'ui', false);
			}
		});
	}
	openBoxModAutoVersion = function(id) {
		$.colorbox({
			title: 'Versión',
			href: 'box-auto_version_mod.php',
			width: '700px',
			height: '500px',
			onComplete: function () {

				// Initialize buttons
				$("#btnBox").button();

				// Disable form
				formDisable('frmBox', 'ui', true);

				// Populate form, then initialize
				$.when(populateFormBoxAutoVersion(id)).then(function () {
					
					// Validate form
					var validateForm = $("#frmBox").validate({
						rules: {
							"box-automotor_version_nombre": {
								required: true
							}
						}
					});

					// Button action
					$("#btnBox").click(function () {
						if (validateForm.form()) {
							if (confirm('Está seguro que desea modificar el registro?')) {
								updateFormAutoVersion();
							}
						};
					});

					// Enable form
					formDisable('frmBox', 'ui', false);
				});

			}
		});
	}
});