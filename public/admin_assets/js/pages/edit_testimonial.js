/**
EditTestimonial oldal
**/
var EditTestimonial = function () {


	var updateTestimonialConfirm = function () {
			$('#update_testimonial_form').submit(function(e){
                e.preventDefault();
				currentForm = this;
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan menti a módosításokat?", function(result) {
					if (result) {
						// a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
						$('#update_testimonial_form').append($("<input>").attr("type", "hidden").attr("name", "submit_update_testimonial").val("submit_update_testimonial"));
						currentForm.submit(); 	
					}
                }); 
            });	 		
	}
	
	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}	
	

    return {

        //main function to initiate the module
        init: function () {
			updateTestimonialConfirm();
			hideAlert();

			
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	EditTestimonial.init();
	



	
});