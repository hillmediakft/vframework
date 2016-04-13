var Testimonial_update = function () {

    /**
     *  Form adatok elküldése
     */
	var send_form = function () {
		$('#testimonial_form').submit(function(e){
            e.preventDefault();
			currentForm = this;
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan menti a módosításokat?", function(result) {
				if (result) {

					App.blockUI({
			            boxed: true,
			            message: 'Feldolgozás...'
			        });	

					setTimeout(function() {
						currentForm.submit();
					}, 300); 	
				}
            }); 
        });	 		
	};
	
   return {

        //main function to initiate the module
        init: function () {
			send_form();
			// vframework.hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Testimonial_update.init();
});