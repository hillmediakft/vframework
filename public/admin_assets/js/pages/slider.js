/**
 Slider oldal
 **/
var Slider = function () {
// sortable list ajax hívás után is működni fog
    var slideOrder = function () {
        $('tbody#slider_list').sortable({
            distance: 10,
            cursor: "move",
            axis: "y",
            revert: true,
            opacity: 0.7,
            tolerance: "pointer",
            containment: "parent",
            update: function (event, ui) {
                Metronic.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
                $.ajax({
                    url: "admin/slider/order",
                    type: 'POST',
                    data: {
                        // a slider_id elemekből képez egy tömböt
                        order: $(this).sortable("serialize"),
                        action: 'update_slider_order'
                    },
                    success: function (msg) {
                        //console.log(msg);
                        Metronic.unblockUI();
                        $('#ajax_message .alert-success').html(msg).show();
                        hideAlert();
                    }
                });

            }

        });
    }

    // üzenet doboz eltüntetése
    var hideAlert = function () {
        $('div.alert.alert-success, div.alert.alert-danger').delay(2500).slideUp(750);
    }

    var deleteSlideConfirm = function () {
        $('[id*=delete]').on('click', function (e) {
            e.preventDefault();
            var deleteLink = $(this).attr('href');
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan törölni akarja a slide-ot?", function (result) {
                if (result) {
                    Metronic.blockUI({
                        boxed: true,
                        message: 'Feldolgozás...'
                    });
                    window.location.href = deleteLink;
                }
            });
        });
    }
    return {
        //main function to initiate the module
        init: function () {
            slideOrder();
            hideAlert();
            deleteSlideConfirm();
        }
    };

}();

$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features	
    Slider.init();
});

