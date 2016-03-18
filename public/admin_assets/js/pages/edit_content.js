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
                    $('#update_content_form').append($("<input>").attr("type", "hidden").attr("name", "submit_update_content").val("submit_update_content"));
                    currentForm.submit(); 	
                }
            }); 
        });	 		
	};
	
	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	};	

    return {

        //main function to initiate the module
        init: function () {
			updateContentConfirm();
			hideAlert();
        }

    };

}();

$(document).ready(function() {    
	EditContent.init();
});