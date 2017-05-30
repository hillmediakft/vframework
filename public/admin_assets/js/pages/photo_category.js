var Photo_category = function () {

    /**
     * Táblázat oszlop indexek megadása 
     *
     * colNumbers -> táblázat oszlopainak száma
     * modCols -> ezeknek az oszlopoknak az adatai módosíthatók
     * anotherCols -> azok az oszlopok amik nem módosulnak (ha vannak ilyenek)
     * urlInsertUpdate -> annak a php feldolgozónak az url-je ami végrehajtja a hozzáadást és módosítást
     * urlDelete -> annak a php feldolgozónak az url-je ami végrehajtja a törlést
     *
     * A modCols elem kulcsai lehetnek bármilyen nevűek (a feldolgozáskor csak az értékre van szükség)
     * anotherCols elemet csak akkor kell megadni, ha a feldolgozás során valamit módosítani akarunk rajta (pl.: default értéket akarunk neki adni)
     */
    var setup = {
        colNumbers: 5,
        modCols: {
            hu: 0,
            en: 1
        },
        anotherCols: {
            kepek_szama: 2
        },
        controlCols: {
            edit_save: 3,
            delete_cancel: 4
        },
        urlInsertUpdate: "admin/photo-gallery/category_insert_update",
        urlDelete: "admin/photo-gallery/delete_category"
    };


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

            //bejárjuk a módosítható mezők oszlop indexét tartalmazó objektumot
            $.each(setup.modCols, function(index, val) {
                jqTds[val].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[val] + '">';
            }); 

            jqTds[setup.controlCols.edit_save].innerHTML = '<a class="edit" href=""><i class="fa fa-check"></i> Mentés</a>';
            jqTds[setup.controlCols.delete_cancel].innerHTML = '<a class="cancel" href=""><i class="fa fa-close"></i> Mégse</a>';
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            
            //bejárjuk a módosítható mezők oszlop indexét tartalmazó objektumot
            $.each(setup.modCols, function(index, val) {
                oTable.fnUpdate(jqInputs[val].value, nRow, val, false);
            }); 

            oTable.fnUpdate('<a class="edit" href=""><i class="fa fa-edit"></i> Szerkeszt</a>', nRow, setup.controlCols.edit_save, false);
            oTable.fnUpdate('<a class="delete" href=""><i class="fa fa-trash"></i> Töröl</a>', nRow, setup.controlCols.delete_cancel, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            
            //bejárjuk a módosítható mezők oszlop indexét tartalmazó objektumot
            $.each(setup.modCols, function(index, val) {
                oTable.fnUpdate(jqInputs[val].value, nRow, val, false);
            });             

            oTable.fnUpdate('<a class="edit" href=""><i class="fa fa-edit"></i> Szerkeszt</a>', nRow, setup.controlCols.edit_save, false);
            oTable.fnDraw();
        }
// ------ ALAPFÜGGVÉNYEK VÉGE ------------



        // tábla beállítások
        var table = $('#photo_category');
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
                {"orderable": true, "searchable": true, "targets": 1},
                {"orderable": true, "searchable": false, "targets": 2},
                {"orderable": false, "searchable": false, "targets": 3},
                {"orderable": false, "searchable": false, "targets": 4}
            ],
            // save datatable state(pagination, sort, etc) in cookie.
            "bStateSave": true,
            // change per page values here
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Összes"]
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
            
            // legyártjuk az üres stringeket tartalmazó tömböt
            var $temp = new Array(); 
            for (var i = 0; i < setup.colNumbers; i++) {
                $temp.push("");
            }

            var aiNew = oTable.fnAddData($temp);
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
                        url: setup.urlDelete,
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

                                if(result.message) {
                                    App.alert({
                                        type: 'success',
                                        //icon: 'warning',
                                        message: result.message,
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
                                
                                if (result.message) {
                                    App.alert({
                                        type: 'danger',
                                        //icon: 'warning',
                                        message: result.message,
                                        container: ajax_message,
                                        place: 'append',
                                        close: true, // make alert closable
                                        reset: true, // close all previouse alerts first
                                        //focus: true, // auto scroll to the alert after shown
                                        closeInSeconds: 4 // auto close after defined seconds
                                    });  
                                }

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

                        var ajax_message = $('#ajax_message');

                        // sor id-jének meghatározása
                        var id = reference.closest('tr').attr('data-id');

                        // asszociatív tömböt kell küldeni a php-nak
                        var data = {};

                        // INSERT
                        // új elem létrehozásakor még nincs data-id attribútum, ezért az id-nek adunk egy null értéket, ebből a php feldolgozó tuni fogja, hogy insert lekérdezést kell csinálni
                        if (typeof id === 'undefined') {
                            id = null;

                            var langs = $('html').attr('data-langs');
                            var langs_array = langs.split(",");

                            // bejárjuk az input elemeket, és az value attribútum értékét berakjuk a data objektumba
                            $.each(reference.closest('tr').find('input'), function(index, val) {
                                var index1 = index;
                                var input = val;
                                // bejárjuk a nyelvek tömbjét, és az value attribútum értékét berakjuk a data objektumba
                                $.each(langs_array, function(index, lcode) {
                                    if (index1 == index) {
                                        data[lcode] = $(input).val();
                                    }
                                });
                            });

                        // UPDATE    
                        } else {
                            
                            // bejárjuk az input elemeket, és az value attribútum értékét berakjuk a data objektumba vagy tömbbe
                            $.each(reference.closest('tr').find('input'), function(index, val) {
                                var langcode = $(val).parents('td').attr('data-lang');
                                data[langcode] = $(this).val();
                                //data.push($(this).val());
                            });
                        }

                        $.ajax({
                            type: "POST",
                            data: {
                                id: id,
                                data: data
                            },
                            url: setup.urlInsertUpdate,
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
                                        $(nRow).find(':nth-child(' + (setup.anotherCols.kepek_szama + 1) + ')').text('0');
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
                print_button_id: "print_photo_category", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "photo_category",
                title: "Fotó kategóriák"
            });
        }

    };

}();

$(document).ready(function() {    
    Photo_category.init();
});