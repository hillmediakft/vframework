/**
EditPage oldal
**/
var EditPage = function () {


	var updatePageConfirm = function () {
			$('#update_pages_form').submit(function(e){
                e.preventDefault();
				currentForm = this;
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan menti a módosításokat?", function(result) {
					if (result) {
						// a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
						$('#update_pages_form').append($("<input>").attr("type", "hidden").attr("name", "submit_update_page").val("submit_update_page"));
						currentForm.submit(); 	
					}
                }); 
            });	 		
	}
	
	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}	
	
	var ckeditorInit = function () {
		//CKEDITOR.replace( 'page_body');							
		CKEDITOR.replace( 'page_body', {customConfig: 'config_minimal2.js'});	
	}

    return {

        //main function to initiate the module
        init: function () {
			updatePageConfirm();
			hideAlert();
			ckeditorInit();
			
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	EditPage.init();
	



	
});