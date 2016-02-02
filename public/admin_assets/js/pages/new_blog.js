var newBlog = function () {

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}

    var ckeditorInit = function () {
        CKEDITOR.replace( 'blog_body', {customConfig: 'config_custom3.js'});
    }

    return {
        //main function to initiate the module
        init: function () {
			hideAlert();
			ckeditorInit();
        }
    };

}();

jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features	
	newBlog.init();
});