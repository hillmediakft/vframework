jQuery(document).ready(function() {    
    
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
