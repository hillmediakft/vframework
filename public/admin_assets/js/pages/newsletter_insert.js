var Newsletter_insert = function () {

    var newNewsletterConfirm = function () {
        $('#newsletter_insert_form').submit(function (e) {
            e.preventDefault();
            var currentForm = this;
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan menti a hírlevelet?", function (result) {
                if (result) {
                    
                    App.blockUI({
                        boxed: true,
                        message: 'Feldolgozás...'
                    });

                    // végrehajtás késleltetése, hogy helyesen jelenjen meg a töltéstjelző spinner
                    setTimeout(function(){
						currentForm.submit();
					}, 300);

                }
            });
        });
    };

    var ckeditorInit = function () {
        CKEDITOR.replace( 'newsletter_body', {customConfig: 'config_custom3.js'});
    };

    return {
        //main function to initiate the module
        init: function () {
			newNewsletterConfirm();
			ckeditorInit();
			vframework.hideAlert();
        }
    };

}();

jQuery(document).ready(function() {    
	Newsletter_insert.init();
});