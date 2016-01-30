var newTestimonial = function () {

    var submitTestimonial = function () {
        $('#new_testimonial_form').submit(function (e) {
            e.preventDefault();
            currentForm = this;
            // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
            $('#new_testimonial_form').append($("<input>").attr("type", "hidden").attr("name", "submit_new_testimonial").val("submit_new_testimonial"));
            Metronic.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });
            currentForm.submit();
        });
    }

    var hideAlert = function () {
        $('div.alert.alert-success, div.alert.alert-danger').delay(3000).slideUp(750);
    }
    return {
        init: function () {
            submitTestimonial();
            hideAlert();
        },
    };
}();
$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features 
    newTestimonial.init(); // init users page
});