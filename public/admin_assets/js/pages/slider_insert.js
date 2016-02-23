var Slider_insert = function () {

    var submitSlide = function () {
        $('#slider_insert_form').submit(function (e) {
            e.preventDefault();
            
            currentForm = this;

            Metronic.blockUI({
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
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    // Demo.init(); // init demo features
    Slider_insert.init();
});