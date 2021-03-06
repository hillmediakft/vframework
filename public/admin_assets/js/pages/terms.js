var Terms = function () {

    /**
     * Táblázat oszlop indexek és egyéb beállítások megadása 
     *
     * tableID ->       html tábla elem id-je
     * insertButtonID -> új elem hozzáadása gomb azonosítója
     *
     * tableSetup ->    datatable táblázat beállításai
     *
     * colNumbers ->    Táblázat összes oszlopának száma.
     * modCols ->       Ezeknek az oszlopoknak az adatai módosíthatók!
     *                  A modCols elem kulcsai lehetnek bármilyen nevűek (a feldolgozáskor csak az értékre van szükség)
     * anotherCols ->   Azok az oszlopok amik nem módosulnak (ha vannak ilyenek).
     *                  Akkor kell neki értéket adni, ha új elem létrehozásakor akarunk egy default értéket adni egy oszlopnak. (pl.: bejegyzések száma - 0 vagy nincs)  
     *                  Ha nincsenek ilyen oszlopok, vagy új elem létrehozásakor nem kell default érték akkor hagyjuk üresen!  
     *                  Ez az elem is egy objektum! Minden elem tartalmaz egy objektumot ami megadja az oszlop számát és a default értékét.
     *                  Pl:
     *                      ez_a_nev_barmi_lehet: {
     *                          column: 3,
     *                          default_value: "0"
     *                      },
     *
     * urlInsert -> annak a php feldolgozónak az url-je ami végrehajtja az új elem hozzáadást és módosítást
     * urlUpdate -> annak a php feldolgozónak az url-je ami végrehajtja azelem módosítást
     * urlDelete -> annak a php feldolgozónak az url-je ami végrehajtja a törlést
     *   
     */
    var setup = {
        tableID: "#terms",
        insertButtonID: "#term_insert_button",

        tableSetup: {
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
                {"orderable": false, "searchable": false, "targets": 2},
                {"orderable": false, "searchable": false, "targets": 3}
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
        },

    // táblázat oszlop elrendezés
        colNumbers: 4,
        modCols: {
            hu: 0,
            en: 1
        },
        anotherCols: {
        },
        controlCols: {
            edit_save: 2,
            delete_cancel: 3
        },

    // feldolgozó url-ek
        urlInsert: "admin/terms/insert_update",
        urlUpdate: "admin/terms/insert_update",
        urlDelete: "admin/terms/delete"
    };
    
    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            // A DatatableEditable.handler() metodusa a feldolgozó aminek át kell adni a beállításokat 
            DatatableEditable.handler(setup);
    
            vframework.hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Terms.init();
});