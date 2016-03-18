var Blog = function () {

    var blogTable = function () {

        var table = $('#blog');
	
		table.dataTable({

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
                {"orderable": false, "searchable": false, "targets": 0},
                {"orderable": true, "searchable": true, "targets": 1},
                {"orderable": true, "searchable": false, "targets": 2},
                {"orderable": true, "searchable": true, "targets": 3},
                {"orderable": false, "searchable": false, "targets": 4}
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
                [1, "asc"]
            ] // set column as a default sort by asc
        });

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    //$(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    //$(this).parents('tr').removeClass("active");
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

            blogTable();

            vframework.deleteItems({
                table_id: "blog",
                url: "admin/blog/delete_blog_AJAX"
            });

/*
            vframework.changeStatus({
                url: "admin/blog/change_status",
            });
*/
            vframework.printTable({
                print_button_id: "print_blog", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "blog",
                title: "Blogok listája"
            });

            vframework.hideAlert();
			
        }

    };

}();

$(document).ready(function() {    
	Blog.init();
});