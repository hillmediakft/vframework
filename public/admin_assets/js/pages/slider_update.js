var Slider_update = function () {

    var updateSlideConfirm = function () {
        $('#slider_update_form').submit(function (e) {
            e.preventDefault();
            
            currentForm = this;
            
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan menti a módosításokat?", function (result) {
                if (result) {

                    App.blockUI({
                        boxed: true,
                        message: 'Feldolgozás...'
                    });
                    
                    setTimeout(function(){
                        currentForm.submit();
                    }, 300);
                }
            });
        });
    };

    var ckeditorInit = function () {
        var langs = $('html').attr('data-langs');
        var langs_array = langs.split(",");
        // bejárjuk a checkboxokat tartalmazó objektumot
        $.each(langs_array, function(index, val) {
            CKEDITOR.replace('text_' + val, {customConfig: 'config_minimal1.js'});
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            updateSlideConfirm();
            ckeditorInit();
        }
    };
}();

$(document).ready(function () {
    Slider_update.init();
});