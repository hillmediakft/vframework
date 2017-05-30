var Blog_insert = function () {

	/**
	 *	Form adatok elküldése
	 */
	var send_form = function(){

		$("#new_blog_form").submit(function (e){
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

    var ckeditorInit = function () {

    	var langs = $('html').attr('data-langs');
		var langs_array = langs.split(",");

		// bejárjuk a checkboxokat tartalmazó objektumot
		$.each(langs_array, function(index, val) {
    		CKEDITOR.replace('blog_body_' + val, {customConfig: 'config_custom3.js'});
		});


    };

    return {
        //main function to initiate the module
        init: function () {
			send_form();
			ckeditorInit();
			vframework.hideAlert();
        }
    };

}();

jQuery(document).ready(function() {    
	Blog_insert.init();
});