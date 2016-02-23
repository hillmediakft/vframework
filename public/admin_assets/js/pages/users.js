/**
Users oldal
**/
var Users = function () {

    var usersTable = function () {

        var table = $('#users');

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

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            // "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "columns": [
                {"orderable": false}, //chechkbox
                {"orderable": false}, //kép
                {"orderable": true}, //felhasználói név
                {"orderable": true}, //név
                {"orderable": false}, //e-mail
                {"orderable": false}, //telefon
                {"orderable": true}, //jogosultság
                {"orderable": true}, //státusz
                {"orderable": false} //menü
            ],
        
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Összes"] // change per page values here
            ],
            // set the initial value
            "pageLength": 20,            

            "pagingType": "bootstrap_full_number", // pagination type(bootstrap, bootstrap_full_number or bootstrap_extended)

            "columnDefs": [
                {'searchable': false, 'targets': 0},
                {'searchable': false, 'targets': 1},
                {'searchable': true, 'targets': 2},
                {'searchable': true, 'targets': 3},
                {'searchable': true, 'targets': 4},
                {'searchable': false, 'targets': 5},
                {'searchable': false, 'targets': 6},
                {'searchable': false, 'targets': 7},
                {"searchable": false, "targets": 8}
            ],
            
            "order": [
                [2, "asc"]
            ] // set column as a default sort by asc

        });


        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                    // $(this).parents('tr').addClass("active");
                } else {
                    $(this).attr("checked", false);
                    // $(this).parents('tr').removeClass("active");
                }
            });
            jQuery.uniform.update(set);
        });

    };

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            usersTable();


            vframework.deleteItems({
                table_id: "users",
                url: "admin/users/delete_user_AJAX"
            });

            vframework.changeStatus({
                url: "admin/users/change_status",
            });

            vframework.printTable({
                print_button_id: "print_users", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "users",
                title: "Admin felhasználók"
            }); 

            vframework.hideAlert();

        }

    };

}();

$(document).ready(function() {
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	//Demo.init(); // init demo features 
	Users.init(); // init users page
});