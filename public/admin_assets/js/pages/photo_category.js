var Photo_category = function () {

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

    var update_category = function(){

        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.inputclass = 'form-control';
    
        var items = new Array();

        $('.xedit').editable({
            url: 'admin/photo_gallery/update_kategoria',
            ajaxOptions: { dataType: 'json' },
            validate: function(value) {
                if($.trim(value) == '') {
                    return 'Nem lehet üres a kategórianév!';
                }
            },
            success: function(response, newValue) {
                if(response.success == 'false') {
                    return response.msg;
                }
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'A szerver nem elérhető. Próbálja meg később.';
                } else {
                    return response.responseText;
                }
            }
        });

    };


	
    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            update_category();
            PhotoCategoryTable();

            vframework.hideAlert();
            
            vframework.printTable({
                print_button_id: "print_photo_category", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "photo_category",
                title: "Fotó kategóriák"
            });

            vframework.deleteItems({
                table_id: "photo_category", // táblázat html elem id attribútuma
                url: "admin/photo_gallery/delete_category_AJAX", // ajax hívás url-je
            });
	
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	//Demo.init(); // init demo features 
	Photo_category.init(); // init users page
});