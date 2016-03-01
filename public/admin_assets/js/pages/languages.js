jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	// Demo.init(); // init demo features
    
	$.fn.editable.defaults.mode = 'popup';
	$.fn.editable.defaults.inputclass = 'form-control';
    $('.xedit').editable({
		url: 'admin/languages/save',
		ajaxOptions: { dataType: 'json' },
		success: function(response, newValue) {
			if(response.success === false) {
			return response.msg;
			}
		}
		
	});
});
