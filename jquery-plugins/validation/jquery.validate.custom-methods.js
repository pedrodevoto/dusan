jQuery.validator.addMethod("notEqualToField", function(value, element, param) {
	var startval = $(param).val();
	return this.optional(element) || value != startval;
}, "El nuevo valor debe ser diferente al anterior.");

$.validator.addMethod("enddate", function(value, element, params) {
	var startdatevalue = $(params).val();
	return Date.parse(startdatevalue) < Date.parse(value);
}, "La fecha de finalización debe ser mayor a la de inicio.");