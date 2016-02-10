/**
 Crew_members oldal
 **/
var Clients = function () {

    var clientsTable = function () {

        var table = $('#clients');

        table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "_START_ - _END_ elem _TOTAL_ elemből",
                "infoEmpty": "Nincs megjeleníthető adat!",
                "infoFiltered": "(Szűrve _MAX_ elemből)",
                "lengthMenu": "Show _MENU_ entries",
                "search": "Search:",
                "zeroRecords": "Nincs egyező elem"
            },
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            // "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            // "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "columns": [{
                    "orderable": false
                }, {
                    "orderable": true
                }, {
                    "orderable": true
                }, {
                    "orderable": false
                }],
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Összes"] // change per page values here
            ],
            // set the initial value
            "pageLength": 15,
            "pagingType": "bootstrap_full_number",
            "language": {
                "search": "Keresés: ",
                "lengthMenu": "  _MENU_ elem/oldal",
                "paginate": {
                    "previous": "Előző",
                    "next": "Következő",
                    "last": "Utolsó",
                    "first": "Első"
                }
            },
            "columnDefs": [{// set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
            "order": [
                [1, "asc"]
            ] // set column as a default sort by asc
        });

        var tableWrapper = jQuery('#clients_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).attr("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            jQuery.uniform.update(set);
        });

        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });

        tableWrapper.find('.dataTables_length select').addClass("form-control input-sm input-inline"); // modify table per page dropdown
    }

    /**
     * Kliens törlése ajax-al
     */
    var deleteClient = function () {
        $('[id*=delete_client]').on('click', function (e) {
            e.preventDefault();
            
            // a törlendő elem id-je
            var deleteID = $(this).attr('data-id');
            // a deleteHtml változóhoz rendeljük a html táblázat törlendő sorát <tr>
            var deleteHtml = $(this).closest("tr");
            // üzenet elem
            var message = $('#ajax_message');
            
            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan törölni akarja a partnert?", function (result) {
                if (result) {

                    $.ajax({
                        url: 'admin/clients/delete_client_AJAX',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            client_id: deleteID
                        },
                        beforeSend: function() {
                            Metronic.blockUI({
                                boxed: true,
                                message: 'Feldolgozás...'
                            });
                        },
                        complete: function(){
                            Metronic.unblockUI();
                        },
                        success: function (result) {
                            if(result.status == 'success') {
   
                                Metronic.alert({
                                    type: 'success',
                                    //icon: 'warning',
                                    message: result.message,
                                    container: $('#ajax_message'),
                                    place: 'append',
                                    close: true, // make alert closable
                                    //reset: true, // close all previouse alerts first
                                    //focus: true, // auto scroll to the alert after shown
                                    closeInSeconds: 3 // auto close after defined seconds
                                });                                
                              
                                deleteHtml.remove();

                            }
                            if(result.status == 'error') {
                                
                                Metronic.alert({
                                    type: 'danger',
                                    //icon: 'warning',
                                    message: result.message,
                                    container: $('#ajax_message'),
                                    place: 'append',
                                    close: true, // make alert closable
                                    //reset: true, // close all previouse alerts first
                                    //focus: true, // auto scroll to the alert after shown
                                    closeInSeconds: 3 // auto close after defined seconds
                                });
                            }
                        },
                        error: function(result, status, e){
                            alert(e);
                        } 

                    }); // ajax end

                } // bootbox confirm end
            
            });
        });

    }


    var hideAlert = function () {
        $('div.alert.alert-success, div.alert.alert-danger').delay(3000).slideUp(750);
    }

    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            clientsTable();
            deleteClient();
            hideAlert();
        }
    };
}();

$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    //Demo.init(); // init demo features 
    Clients.init(); // init clients page
});