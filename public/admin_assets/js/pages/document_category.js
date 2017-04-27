var Document_category = function () {

    var handleTable = function () {

// ------ ALAPFÜGGVÉNYEK ------------
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
// ------ ALAPFÜGGVÉNYEK VÉGE ------------



        // tábla beállítások
        var table = $('#document_category');
        var oTable = table.dataTable({

            "language": {
                // metronic specific
                    //"metronicGroupActions": "_TOTAL_ sor kiválasztva: ",
                    //"metronicAjaxRequestGeneralError": "A kérés nem hajtható végre, ellenőrizze az internet kapcsolatot!",

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
            // save datatable state(pagination, sort, etc) in cookie.
            "bStateSave": true,
            // change per page values here
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"]
            ],
            // set the initial value
            "pageLength": 20,            
            "pagingType": "bootstrap_full_number",
            "order": [
                [0, "asc"]
            ] // set column as a default sort by asc            

        });




        // szerkesztett és új elem értékének alapbeállítása
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
				
                return false;
				
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
                        url: "admin/documents/category_delete",
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


        // mégsem gomb megnyomásakor
        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            if (nNew) {
                // sor törlése a DOM-ból
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

                        // sor id-jének meghatározása
                        var id = reference.closest('tr').attr('data-id');
                        // új elem létrehozásakor még nincs data-id attribútum, ezért az id-nek adunk egy 0 értéket, ebből a php feldolgozó tuni fogja, hogy insert lekérdezést kell csinálni
                        if (typeof id === 'undefined') {
                            id = null;
                        }

                        var data = reference.closest('tr').find('input').val();
                        var ajax_message = $('#ajax_message');    

                        $.ajax({
                            type: "POST",
                            data: {
                                id: id,
                                data: data
                            },
                            url: "admin/documents/category_insert_update",
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


                                    // ha új sor került a táblázatba (mert van a result objektumnak inserted_id tulajdonsága)
                                    if (result.inserted_id) {
                                        // az új tr elemnek adunk egy data-id attribútumot az új id-vel    
                                        $(nEditing).attr('data-id', result.inserted_id);
                                        // a táblázatban az ehhez a listaelemhez kapcsolódó elemek alapból 0 
                                        $(nRow).find(':nth-child(2)').text('0');
                                    }

                                    // sor mentése
                                    saveRow(oTable, nEditing);
                                    // alapbeállítások visszaállítása
                                    nEditing = null;
                                    nNew = false;
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
            vframework.hideAlert();
            vframework.printTable({
                print_button_id: "print_document_category", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "document_category",
                title: "Document kategóriák"
            });
        }

    };

}();

$(document).ready(function() {    
	Document_category.init();
});