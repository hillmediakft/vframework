var Content = function () {

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	};


    var ckeditorInit = function () {
    	// csak a create oldalon lesz ckeditor inicializálva
    	var insertForm = document.getElementsByName("insert_content_form");
    	if(insertForm.length == 0){
    		return false;
    	}

    	var langs = $('html').attr('data-langs');
		var langs_array = langs.split(",");

		// bejárjuk a checkboxokat tartalmazó objektumot
		$.each(langs_array, function(index, val) {
    		CKEDITOR.replace('body_' + val, {customConfig: 'config_max1.js'});
		});
    };


    return {

        //main function to initiate the module
        init: function () {
			hideAlert();
			ckeditorInit();
        }

    };

}();

$(document).ready(function() {    
	Content.init();
});