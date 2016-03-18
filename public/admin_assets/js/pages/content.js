var Content = function () {

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	};

    return {

        //main function to initiate the module
        init: function () {
			hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Content.init();
});