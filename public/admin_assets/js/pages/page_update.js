var Page_update = function () {

	var updatePageConfirm = function () {
		$('#update_page_form').submit(function(e){
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

					setTimeout(function(){
						currentForm.submit();
					}, 300); 	
				}
            }); 
        });	 		
	}

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
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	// Demo.init(); // init demo features
	Page_update.init();
});