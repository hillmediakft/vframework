var Newsletter_update = function () {

    var updateNewsletterConfirm = function () {
        $('#newsletter_update_form').submit(function (e) {
            e.preventDefault();
            var currentForm = this;
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan menti a módosításokat?", function (result) {
                if (result) {
                    // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
                    //$('#update_newsletter_submit').append($("<input>").attr("type", "hidden").attr("name", "update_newsletter_submit").val("update_newsletter_submit"));
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
			updateNewsletterConfirm();
            ckeditorInit();
			vframework.hideAlert();
        }
    };

}();

jQuery(document).ready(function() {    
	Newsletter_update.init();
});