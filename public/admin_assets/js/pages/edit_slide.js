/**
 EditSlide oldal
 **/
var EditSlide = function () {

    var updateSlideConfirm = function () {
        $('#update_slide').submit(function (e) {
            e.preventDefault();
            currentForm = this;
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan menti a módosításokat?", function (result) {
                if (result) {
                    // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
                    $('#update_slide').append($("<input>").attr("type", "hidden").attr("name", "submit_update_slide").val("submit_update_slide"));
                    Metronic.blockUI({
                        boxed: true,
                        message: 'Feldolgozás...'
                    });
                    currentForm.submit();
                }
            });
        });
    }
    var ckeditorInit = function () {
        CKEDITOR.replace('slider_text', {customConfig: 'config_minimal1.js'});
    }
    return {
        //main function to initiate the module
        init: function () {
            updateSlideConfirm();
            ckeditorInit();
        }
    };
}();

$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features
    EditSlide.init();
});