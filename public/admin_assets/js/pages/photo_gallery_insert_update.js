var PhotoGallery_insert_update = function () {

	/**
	 *	Form adatok elküldése
	 */
	var upload_photo = function(){

		$("#photo_form").submit(function (e){
			e.preventDefault();

			App.blockUI({
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
	PhotoGallery_insert_update.init();	
});