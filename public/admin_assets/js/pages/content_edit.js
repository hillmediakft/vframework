var EditContent = function () {

	var updateContentConfirm = function () {
        $('#update_content_form').submit(function(e){
            e.preventDefault();
            var currentForm = this;
            bootbox.setDefaults({
                locale: "hu", 
            });
            bootbox.confirm("Biztosan meni a módosításokat?", function(result) {
                if (result) {
                    // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
                    //$('#update_content_form').append($("<input>").attr("type", "hidden").attr("name", "submit_update_content").val("submit_update_content"));
                    currentForm.submit(); 	
                }
            }); 
        });	 		
	};
	
    var ckeditorInit = function () {

        var langs = $('html').attr('data-langs');
        var langs_array = langs.split(",");

        // bejárjuk a checkboxokat tartalmazó objektumot
        $.each(langs_array, function(index, val) {
            CKEDITOR.replace('body_' + val, {customConfig: 'config_max1.js'});
        });
    };

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	};	

    return {

        //main function to initiate the module
        init: function () {
			updateContentConfirm();
			ckeditorInit();
            hideAlert();
        }

    };

}();

$(document).ready(function() {    
	EditContent.init();
});