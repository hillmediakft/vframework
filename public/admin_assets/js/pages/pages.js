var Pages = function () {

    return {
        //main function to initiate the module
        init: function () {
			vframework.hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	// Demo.init(); // init demo features
	Pages.init();
});