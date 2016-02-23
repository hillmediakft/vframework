var Testimonial_insert = function () {

    var submitTestimonial = function () {
        $('#new_testimonial_form').submit(function (e) {
            e.preventDefault();
            currentForm = this;

            Metronic.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });

            setTimeout(function() {
                currentForm.submit();
            }, 300);
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
    // Demo.init(); // init demo features 
    Testimonial_insert.init(); // init users page
});