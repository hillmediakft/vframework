var Page_update = function () {

	var updatePageConfirm = function () {
		$('#page_update_form').submit(function(e){
            e.preventDefault();
			
			var currentForm = this;
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan menti a módosításokat?", function(result) {
				if (result) {

					App.blockUI({
			            boxed: true,
			            message: 'Feldolgozás...'
			        });

					setTimeout(function(){
						currentForm.submit();
					}, 300); 	
				}
            }); 
        });	 		
	};

    var ckeditorInit = function () {

    	var langs = $('html').attr('data-langs');
		var langs_array = langs.split(",");

		// bejárjuk a checkboxokat tartalmazó objektumot
		$.each(langs_array, function(index, val) {
    		CKEDITOR.replace('body_' + val, {customConfig: 'config_minimal2.js'});
		});
    };


    return {

        //main function to initiate the module
        init: function () {
			updatePageConfirm();
			ckeditorInit();
			vframework.hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Page_update.init();
});