var Testimonial_insert = function () {

    /**
     *  Form adatok elküldése
     */
    var send_form = function(){

        $("#testimonial_form").submit(function (e){
            e.preventDefault();

            App.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });

            var currentForm = this;

            setTimeout(function(){
                currentForm.submit();
            }, 300);

        });
    };

    return {
        init: function () {
            send_form();
            vframework.hideAlert(6000);
        },
    };
}();

$(document).ready(function () {
    Testimonial_insert.init();
});