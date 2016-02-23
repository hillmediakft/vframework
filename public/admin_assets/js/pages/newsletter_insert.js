var newNewsletter = function () {

    var newNewsletterConfirm = function () {
        $('#new_newsletter_form').submit(function (e) {
            e.preventDefault();
            currentForm = this;
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan menti a hírlevelet?", function (result) {
                if (result) {
                    // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
                    $('#new_newsletter_form').append($("<input>").attr("type", "hidden").attr("name", "submit_new_newsletter").val("submit_new_newsletter"));
                    Metronic.blockUI({
                        boxed: true,
                        message: 'Feldolgozás...'
                    });

                    // végrehajtás késleltetése, hogy helyesen jelenjen meg a töltéstjelző spinner
                    setTimeout(function(){
						currentForm.submit();
					}, 500);

                }
            });
        });
    }


	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}

    var ckeditorInit = function () {
        CKEDITOR.replace( 'newsletter_body', {customConfig: 'config_custom3.js'});
    }

    return {
        //main function to initiate the module
        init: function () {
			newNewsletterConfirm();
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
	newNewsletter.init();
});