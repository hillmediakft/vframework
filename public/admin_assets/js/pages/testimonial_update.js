var Testimonial_update = function () {

	var updateTestimonialConfirm = function () {
			$('#update_testimonial_form').submit(function(e){
                e.preventDefault();
				currentForm = this;
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan menti a módosításokat?", function(result) {
					if (result) {

						Metronic.blockUI({
				            boxed: true,
				            message: 'Feldolgozás...'
				        });	

						setTimeout(function() {
							currentForm.submit();
						}, 300); 	
					}
                }); 
            });	 		
	}
	
    var hideAlert = function () {
        $('div.alert.alert-success, div.alert.alert-danger').delay(3000).slideUp(750);
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
	// Demo.init(); // init demo features
	Testimonial_update.init();
	



	
});