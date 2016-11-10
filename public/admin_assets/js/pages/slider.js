var Slider = function () {
    
    var sliderTable = function () {

        var table = $('#slider_table').DataTable({

            "language": {
                // metronic specific
                    // "metronicGroupActions": "_TOTAL_ sor kiválasztva: ",
                    // "metronicAjaxRequestGeneralError": "A kérés nem hajtható végre, ellenőrizze az internet kapcsolatot!",
                // data tables specific                
                "decimal":        "",
                "emptyTable":     "Nincs megjeleníthető adat!",
                "info":           "_START_ - _END_ elem &nbsp; _TOTAL_ elemből",
                "infoEmpty":      "Nincs megjeleníthető adat!",
                "infoFiltered":   "(Szűrve _MAX_ elemből)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     " _MENU_ elem/oldal",
                "loadingRecords": "Betöltés...",
                "processing":     "Feldolgozás...",
                "search":         "Keresés:",
                "zeroRecords":    "Nincs egyező elem",
                "paginate": {
                    "previous":   "&laquo;",
                    "next":       "&raquo;",
                    "last":       "Utolsó",
                    "first":      "Első",
                    "pageOf":     "&nbsp;/&nbsp;"
                },
                "aria": {
                    "sortAscending":  ": aktiválja a növekvő rendezéshez",
                    "sortDescending": ": aktiválja a csökkenő rendezéshez"
                }            
            },
            "rowReorder": {
                "selector": "td:first-child"
            },
            "searching": false,
            // change per page values here
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Összes"]
            ],
            "autoWidth": false,
            // set the initial value
            "pageLength": 5,            
            // pagination type
            //"pagingType": "bootstrap-full_number",
            "paging": false,
            "columnDefs": [
                { "targets": 0, "orderable": false, "searchable": false, "visible": false },
                { "targets": 1, "orderable": false, "searchable": false, "visible": false },
                { "targets": 2, "orderable": false, "searchable": false },
                { "targets": 3, "orderable": false, "searchable": false },
                { "targets": 4, "orderable": false, "searchable": false },
                { "targets": 5, "orderable": false, "searchable": false },
                { "targets": 6, "orderable": false, "searchable": false },
                { "targets": 7, "orderable": false, "searchable": false }
            ]
        });

        // ez a string fog menni a php feldolgozónak pl.: 12=8&45=7&14=6 ahol: id=sorszam&id=sorszam...
        var serialized_data = '';

        table.on( 'row-reorder', function ( e, diff, edit ) {
     
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            
                var rowData = table.row( diff[i].node ).data();
                serialized_data += rowData[1] + '=' + diff[i].newData;
                if( i<ien-1 ) {
                    serialized_data += '&';
                }
            }
            console.log(serialized_data);

            if(serialized_data !== ''){

                jQuery.ajax({
                    url: 'admin/slider/order',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        order: serialized_data
                    },
                    beforeSend: function() {
                        App.blockUI({
                            boxed: true,
                            message: 'Feldolgozás...'
                        });
                    },
                    complete: function(xhr, textStatus) {
                        App.unblockUI();
                    },
                    success: function(result, textStatus, xhr) {
                        if (result.status == 'success') {
                            console.log('A sorrend megváltozott!');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                        console.log("Hiba történt: " + textStatus);
                        console.log("Rendszerválasz: " + xhr.responseText);
                    }
                });

                // üresre állítjuk a serialized_data változó értékét
                serialized_data = '';
            }
        });
    
    };


    return {
        //main function to initiate the module
        init: function () {
            sliderTable();

            vframework.hideAlert();

            vframework.deleteItems({
                table_id: "slider_table",
                url: "admin/slider/delete"
            });
        }
    };

}();

$(document).ready(function () {
    Slider.init();
});

