var Photo_category = function () {

/*
    var PhotoCategoryTable = function () {
        var table = $('#photo_category');
	
		table.dataTable({

            "language": {
                // metronic specific
                "metronicGroupActions": "_TOTAL_ sor kiválasztva: ",
                "metronicAjaxRequestGeneralError": "A kérés nem hajtható végre, ellenőrizze az internet kapcsolatot!",

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
                    "previous":   "Előző",
                    "next":       "Következő",
                    "last":       "Utolsó",
                    "first":      "Első",
                    "pageOf":     "&nbsp;/&nbsp;"
                },
                "aria": {
                    "sortAscending":  ": aktiválja a növekvő rendezéshez",
                    "sortDescending": ": aktiválja a csökkenő rendezéshez"
                }            
            },
            
            // set default column settings
            "columnDefs": [
                {"orderable": true, "searchable": true, "targets": 0},
                {"orderable": true, "searchable": false, "targets": 1},
                {"orderable": false, "searchable": false, "targets": 2}
            ],

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            // "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
        
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 20,            
            "pagingType": "bootstrap_full_number",
            "order": [
                [0, "asc"]
            ] // set column as a default sort by asc
		
        });

    };
*/


    var handleTable = function () {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '">';
            jqTds[2].innerHTML = '<a class="edit" href=""><i class="fa fa-check"></i> Mentés</a>';
            jqTds[3].innerHTML = '<a class="cancel" href=""><i class="fa fa-close"></i> Mégse</a>';
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);

            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            //oTable.fnUpdate('huba', nRow, 1, false);
            oTable.fnUpdate('<a class="edit" href=""><i class="fa fa-edit"></i> Szerkeszt</a>', nRow, 2, false);
            oTable.fnUpdate('<a class="delete" href=""><i class="fa fa-trash"></i> Töröl</a>', nRow, 3, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate('<a class="edit" href=""><i class="fa fa-edit"></i> Szerkeszt</a>', nRow, 2, false);
            oTable.fnDraw();
        }





        var table = $('#photo_category');

        var oTable = table.dataTable({
 
            "language": {
                // metronic specific
                "metronicGroupActions": "_TOTAL_ sor kiválasztva: ",
                "metronicAjaxRequestGeneralError": "A kérés nem hajtható végre, ellenőrizze az internet kapcsolatot!",

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
                    "previous":   "Előző",
                    "next":       "Következő",
                    "last":       "Utolsó",
                    "first":      "Első",
                    "pageOf":     "&nbsp;/&nbsp;"
                },
                "aria": {
                    "sortAscending":  ": aktiválja a növekvő rendezéshez",
                    "sortDescending": ": aktiválja a csökkenő rendezéshez"
                }            
            },
            
            // set default column settings
            "columnDefs": [
                {"orderable": true, "searchable": true, "targets": 0},
                {"orderable": true, "searchable": false, "targets": 1},
                {"orderable": false, "searchable": false, "targets": 2},
                {"orderable": false, "searchable": false, "targets": 3}
            ],

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            // "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
        
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 20,            
            "pagingType": "bootstrap_full_number",
            "order": [
                [0, "asc"]
            ] // set column as a default sort by asc

        });

/*
        var tableWrapper = $("#photo_category_wrapper");

        tableWrapper.find(".dataTables_length select").select2({
            showSearchInput: false //hide search box with special css class
        }); // initialize select2 dropdown
*/
        var nEditing = null;
        var nNew = false;

        // kategória hozzáadása
        $('#category_insert_button').click(function (e) {
            e.preventDefault();

            // ha van szerkesztett elem, VAGY létre van hozva egy új hozzáadása elem
            if (nNew || nEditing) {
                
                App.alert({
                    container: $('#ajax_message'), // $('#elem'); - alerts parent container(by default placed after the page breadcrumbs)
                    place: "append", // "append" or "prepend" in container 
                    type: 'warning', // alert's type (success, danger, warning, info)
                    message: "A szerkesztett elemet mentse el, vagy klikkel-jen a mégse gombra.", // alert's message
                    close: true, // make alert closable
                    reset: true, // close all previouse alerts first
                    // focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 10 // auto close after defined seconds
                    // icon: "warning" // put icon before the message
                });

                return;

/*
                if (confirm("A szerkesztett sort nem mentette el. Akarja menteni?")) {
                    //saveRow(oTable, nEditing); // save
                    //$(nEditing).find("td:first").html("Untitled");
                    nEditing = null;
                    nNew = false;

                } else {
                    oTable.fnDeleteRow(nEditing); // cancel
                    nEditing = null;
                    nNew = false;

                    return;
                }
*/

            }


            var aiNew = oTable.fnAddData(['', '', '', '']);
            var nRow = oTable.fnGetNodes(aiNew[0]);
            
            editRow(oTable, nRow);
            nEditing = nRow;
            nNew = true;
        });

        // törlés
        table.on('click', '.delete', function (e) {
            e.preventDefault();
            reference = $(this);
            bootbox.setDefaults({
                locale: "hu"
            });
            bootbox.confirm("Biztosan törölni akarja?", function (result) {
                if (result) {

                    var nRow = reference.parents('tr')[0];
                    var id = reference.closest('tr').attr('data-id');
                    var ajax_message = $('#ajax_message');
                    
                    $.ajax({
                        type: "POST",
                        data: {
                            item_id: id
                        },
                        url: "admin/photo-gallery/delete_category",
                        dataType: "json",
                        beforeSend: function () {
                            App.blockUI({
                                boxed: true,
                                message: 'Feldolgozás...'
                            });
                        },
                        complete: function () {
                            App.unblockUI();
                        },
                        success: function (result) {
                            
                            if (result.status == 'success') {

                                if(result.message_success) {
                                    App.alert({
                                        type: 'success',
                                        //icon: 'warning',
                                        message: result.message_success,
                                        container: ajax_message,
                                        place: 'append',
                                        close: true, // make alert closable
                                        reset: false, // close all previouse alerts first
                                        //focus: true, // auto scroll to the alert after shown
                                        closeInSeconds: 3 // auto close after defined seconds
                                    });                                
                                }
                                if (result.message_error) {
                                    App.alert({
                                        type: 'warning',
                                        //icon: 'warning',
                                        message: result.message_error,
                                        container: ajax_message,
                                        place: 'append',
                                        close: true, // make alert closable
                                        reset: false, // close all previouse alerts first
                                        //focus: true, // auto scroll to the alert after shown
                                        closeInSeconds: 3 // auto close after defined seconds
                                    });  
                                }
                                // sor törlése a DOM-ból
                                oTable.fnDeleteRow(nRow);
                            }

                            if (result.status == 'error') {
                                App.alert({
                                    container: ajax_message, // $('#elem'); - alerts parent container(by default placed after the page breadcrumbs)
                                    place: "append", // "append" or "prepend" in container 
                                    type: 'danger', // alert's type (success, danger, warning, info)
                                    message: result.message, // alert's message
                                    close: true, // make alert closable
                                    reset: true, // close all previouse alerts first
                                    // focus: true, // auto scroll to the alert after shown
                                    closeInSeconds: 4 // auto close after defined seconds
                                    // icon: "warning" // put icon before the message
                                });
                            }
                        },
                        error: function(xhr, textStatus, errorThrown){
                            console.log(errorThrown);
                            console.log("Hiba történt: " + textStatus);
                            console.log("Rendszerválasz: " + xhr.responseText); 
                        } 
                    });
                }

            });

        });

        // cancel
        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        // elem szerkesztése
        table.on('click', '.edit', function (e) {
            e.preventDefault();
            
            var reference = $(this);
            /* Get the row as a parent of the link that was clicked on */
            var nRow = reference.parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else if (nEditing == nRow && this.innerHTML == '<i class="fa fa-check"></i> Mentés') {
                /* Editing this row and want to save it */
                bootbox.setDefaults({
                    locale: "hu"
                });
                bootbox.confirm("Biztosan menteni akarja a módosítást?", function (result) {
                    if (result) {

                        var id = reference.closest('tr').attr('data-id');
                        if (typeof id === 'undefined') {
                            id = 0;
                        }    
                        var data = reference.closest('tr').find('input').val();
                        var ajax_message = $('#ajax_message');    

                        $.ajax({
                            type: "POST",
                            data: {
                                id: id,
                                data: data
                            },
                            url: "admin/photo_gallery/category_insert_update",
                            dataType: "json",
                            beforeSend: function () {
                                App.blockUI({
                                    boxed: true,
                                    message: 'Feldolgozás...'
                                });
                            },
                            complete: function () {
                                App.unblockUI();
                            },
                            success: function (result) {
                                if (result.status == 'success') {
                                    App.alert({
                                        container: ajax_message, // $('#elem'); - alerts parent container(by default placed after the page breadcrumbs)
                                        place: "append", // "append" or "prepend" in container 
                                        type: 'success', // alert's type (success, danger, warning, info)
                                        message: result.message, // alert's message
                                        close: true, // make alert closable
                                        // reset: true, // close all previouse alerts first
                                        // focus: true, // auto scroll to the alert after shown
                                        closeInSeconds: 4 // auto close after defined seconds
                                        // icon: "warning" // put icon before the message
                                    });
                                    
                                    saveRow(oTable, nEditing);
                                    nEditing = null;

                                    // új kategória hozzáadásakor a képek száma oszlopba berakunk egy 0-át
                                    if (result.action == 'insert') {
                                        $(nRow).find(':nth-child(2)').text('0');
                                    }
                                }

                                if (result.status == 'error') {
                                    App.alert({
                                        container: ajax_message, // $('#elem'); - alerts parent container(by default placed after the page breadcrumbs)
                                        place: "append", // "append" or "prepend" in container 
                                        type: 'danger', // alert's type (success, danger, warning, info)
                                        message: result.message, // alert's message
                                        close: true, // make alert closable
                                        // reset: true, // close all previouse alerts first
                                        // focus: true, // auto scroll to the alert after shown
                                        closeInSeconds: 4 // auto close after defined seconds
                                        // icon: "warning" // put icon before the message
                                    });
                                }
                            },
                            error: function(xhr, textStatus, errorThrown){
                                console.log(errorThrown);
                                console.log("Hiba történt: " + textStatus);
                                console.log("Rendszerválasz: " + xhr.responseText); 
                            }
                        });
   
                    }
                });

            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });
    };









	
    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            handleTable();
            // PhotoCategoryTable();

            vframework.hideAlert();
            
            vframework.printTable({
                print_button_id: "print_photo_category", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "photo_category",
                title: "Fotó kategóriák"
            });

/*
            vframework.deleteItems({
                table_id: "photo_category", // táblázat html elem id attribútuma
                url: "admin/photo-gallery/delete_category", // ajax hívás url-je
            });
*/	
        }

    };

}();

$(document).ready(function() {    
	Photo_category.init(); // init users page
});