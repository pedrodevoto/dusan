$(document).ready(function() {	
	// JEditable Custom Input (datepicker)
	jQuery.editable.addInputType('datepicker', {
		element: function (settings, original) {
			var input = jQuery('<input />');
			// Catch the blur event on month change
			settings.onblur = function (e) {
			};
			input.datepicker({
				dateFormat: 'yy-mm-dd',                        
				onClose: function (dateText, inst) {
					if (original.revert == input.val()) {
						original.reset();
						return false;
					} else {
						jQuery(this).submit();
					}
				}
			});
			jQuery(this).append(input);
			return (input);
		}
	});	
});