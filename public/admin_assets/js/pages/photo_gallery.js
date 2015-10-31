var Portfolio = function () {

		// üzenet doboz eltüntetése
	var mixGrid = function () {
		 $('.mix-grid').mixitup();						 		
	}
	
	// üzenet doboz eltüntetése
	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}
	
	var deletePhotoConfirm = function () {
		$('[id*=delete_photo]').on('click', function(e){
            e.preventDefault();
			var deleteLink = $(this).attr('href');
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan törölni akarja a fotót?", function(result) {
				if (result) {
					window.location.href = deleteLink; 	
				}
            }); 
        });			
	}	
	
    return {
        //main function to initiate the module
        init: function () {
            mixGrid();
			hideAlert();
			deletePhotoConfirm();
        }

    };

}();

jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features	
	Portfolio.init();
});