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

    return {

        //main function to initiate the module
        init: function () {
			updatePageConfirm();

			vframework.hideAlert();
			
			vframework.ckeditorInit({
				page_body: "config_minimal2"
			});
			
        }

    };

}();

$(document).ready(function() {    
	Page_update.init();
});