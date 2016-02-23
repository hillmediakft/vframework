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
                        Metronic.unblockUI();
                        
                        Metronic.alert({
                            container: $('#ajax_message'), // $('#elem'); - alerts parent container(by default placed after the page breadcrumbs)
                            place: "append", // "append" or "prepend" in container 
                            type: 'success', // alert's type (success, danger, warning, info)
                            message: msg, // alert's message
                            close: true, // make alert closable
                            //reset: true, // close all previouse alerts first
                            //focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 3, // auto close after defined seconds
                            //icon: "warning" // put icon before the message
                        });
                    }
                });

            }

        });
    };

    // üzenet doboz eltüntetése
    var hideAlert = function () {
        $('div.alert.alert-success, div.alert.alert-danger').delay(2500).slideUp(750);
    };

    return {
        //main function to initiate the module
        init: function () {
            slideOrder();
            hideAlert();

            vframework.deleteItems({
                table_id: "slider_table",
                datatable: false,
                url: "admin/slider/delete_slider_AJAX"
            });
        }
    };

}();

$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    // Demo.init(); // init demo features	
    Slider.init();
});

