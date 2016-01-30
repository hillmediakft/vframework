/**
 NewSlide oldal
 **/
var NewSlide = function () {

    var submitSlide = function () {
        $('#new_slide').submit(function (e) {
            e.preventDefault();
            currentForm = this;
            // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
            $('#new_slide').append($("<input>").attr("type", "hidden").attr("name", "submit_new_slide").val("submit_new_slide"));
            Metronic.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });
            currentForm.submit();
        });
    }

    var ckeditorInit = function () {
        CKEDITOR.replace('slider_text', {customConfig: 'config_minimal1.js'});
    }
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
    Demo.init(); // init demo features
    NewSlide.init();
});