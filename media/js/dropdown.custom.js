// JavaScript Document
var timeout = 500;
var closetimer = 0;
var ddmenuitem = 0;

function jsddm_open() {
	jsddm_canceltimer();
	jsddm_close();
	ddmenuitem = $(this).find('ul').css('visibility', 'visible');
}

function jsddm_close() {
	if (ddmenuitem) ddmenuitem.css('visibility', 'hidden');
}

function jsddm_timer() {
	closetimer = window.setTimeout(jsddm_close, timeout);
}

function jsddm_canceltimer() {
	if(closetimer) {
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

$(document).ready(function() {
	$('#jsddm > li').bind('mouseover', jsddm_open)
	$('#jsddm > li').bind('mouseout',  jsddm_timer)
	
	// CUSTOM: BREADCRUMB MENU

	/* General variables */
   	var pathName = window.location.pathname;
	var pageName = pathName.substring(pathName.lastIndexOf("/") + 1, pathName.length);
	var txtSelectedTop = '';
	var txtSelectedChild = '';	
	var txtTrail = '';
	
	/* Process Top Level links */
	$('ul#jsddm > li > a').each(function() {
		var linkName = $(this).attr('href');
		// If page corresponds to Top Level link  
		if (linkName == pageName) {
			// Set class for Top Level link
			$(this).addClass('jsddm-lia-selected');
			// Set Top Level text and trail
			txtSelectedTop = $(this).text();			
			txtTrail = txtSelectedTop;
		}
	});	
	
	// If page does not correspond to Top Level Link
	if (txtSelectedTop=='') {
		/* Process Child links */
		$('ul#jsddm > li > ul > li > a').each(function() {		
			var linkName = $(this).attr('href');  
			// If page corresponds to Child link  
			if (linkName == pageName) {
				// Determine Top Level link for Child
				var parentID = $(this).parent().parent().parent().children("a");
				// Set class for Top Level link	
				parentID.addClass('jsddm-lia-selected');
				// Set Top Level/Child text and trail											
				txtSelectedTop = parentID.text();				
				txtSelectedChild = $(this).text();
				txtTrail += '<strong>' + txtSelectedTop + '</strong> | ' + txtSelectedChild;
			}		
		});			
	}

	// Set HTML to trail
	$('#divHeaderTitle').html(txtTrail);					
	
	// END CUSTOM: BREADCRUMB MENU

});

document.onclick = jsddm_close;