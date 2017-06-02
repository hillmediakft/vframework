/**
 Referenciák oldal
 **/
var gyikCategory = function () {

    var gyikCategoryTable = function () {

        var table = $('#gyik_category');
        // begin first table


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

       //     "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "columns": [{
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
                [0, "asc"]
            ] // set column as a default sort by asc


        });

        var tableWrapper = jQuery('#gyik_category_wrapper');

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


    var enableDisableButtons = function () {

        var checkAll = $('input.group-checkable');
        var checkboxes = $('input.checkboxes');

        checkboxes.change(function () {
            $(this).closest("tr").find('.btn-group a').attr('disabled', $(this).is(':checked'));
        });
        checkAll.change(function () {
            checkboxes.closest("tr").find('.btn-group a').attr('disabled', $(this).is(':checked'));
        });
    }

    var hideAlert = function () {
        $('div.alert').delay(2500).slideUp(750);
    }

    var printTable = function () {
        $('#print_gyik_category').on('click', function (e) {
            e.preventDefault();
            var divToPrint = document.getElementById("gyik");
            console.log(divToPrint);
//		divToPrint = $('#users tr').find('th:last, td:last').remove();
            newWin = window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        })

    }

    var deleteOneCategoryConfirm = function () {
        $('[id*=delete_category]').on('click', function (e) {
            e.preventDefault();
            var deleteLink = $(this).attr('href');
            //var employerName = $(this).closest("tr").find('td:nth-child(3)').text();

            bootbox.setDefaults({
                locale: "hu",
            });
            bootbox.confirm("Biztosan törölni akarja a kategóriát?", function (result) {
                if (result) {
                    window.location.href = deleteLink;
                }
            });
        });

    }

    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            gyikCategoryTable();
            enableDisableButtons();
            hideAlert();
            printTable();
            deleteOneCategoryConfirm();

        }

    };

}();

$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features 
    gyikCategory.init(); // init gyik category page
});