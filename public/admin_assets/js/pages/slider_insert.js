var Slider_insert = function () {

    var submitSlide = function () {
        $('#slider_insert_form').submit(function (e) {
            e.preventDefault();
            
            currentForm = this;

            App.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });

            setTimeout(function(){
                currentForm.submit();
            }, 300);

        });
    };

    var ckeditorInit = function () {
        CKEDITOR.replace('slider_text', {customConfig: 'config_minimal1.js'});
    };

    return {
        //main function to initiate the module
        init: function () {
            submitSlide();
            ckeditorInit();
        }
    };
}();

$(document).ready(function () {
    Slider_insert.init();
});