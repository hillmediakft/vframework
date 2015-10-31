/**
Content oldal
**/
var Content = function () {

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}	

    return {

        //main function to initiate the module
        init: function () {
			hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	Content.init();
	



	
});