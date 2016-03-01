var PhotoGallery_insert = function () {

	/**
	 *	Form adatok elküldése
	 */
	var upload_photo = function(){

		$("#photo_insert_form").submit(function (e){
			e.preventDefault();

			Metronic.blockUI({
	            boxed: true,
	            message: 'Feldolgozás...'
	        });

			var currentForm = this;

			setTimeout(function(){
				currentForm.submit();
			}, 300);

		});
	};

    return {
        //main function to initiate the module
        init: function () {

        	upload_photo();
			vframework.hideAlert();
        }
    };

}();

jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	// Demo.init(); // init demo features	
	PhotoGallery_insert.init();	
});