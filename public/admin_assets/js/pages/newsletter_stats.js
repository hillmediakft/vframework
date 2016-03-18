/**
Newsletter_stats oldal
**/
var Newsletter_stats = function () {

    var newsletterTable = function () {

        var table = $('#newsletter_table');
	
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

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            // "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "columnDefs": [
            	{"orderable": true, "searchable": true, "targets": 0},
            	{"orderable": true, "searchable": true, "targets": 1},
            	{"orderable": true, "searchable": false, "targets": 2},
            	{"orderable": false, "searchable": false, "targets": 3},
            	{"orderable": false, "searchable": false, "targets": 4},
            	{"orderable": false, "searchable": false, "targets": 5},
            	{"orderable": false, "searchable": false, "targets": 6},
            	{"orderable": false, "searchable": false, "targets": 7},
            	{"orderable": true, "searchable": false, "targets": 8},
            	{"orderable": false, "searchable": false, "targets": 9}
            ],

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],

            "pageLength": 5,            
            "pagingType": "bootstrap_full_number",
            "order": [
                [2, "asc"]
            ] // set column as a default sort by asc
        });

    };
	

	var submitNewsletterConfirm = function () {
		$('[id*=submit_newsletter]').on('click', function(e){
			e.preventDefault();

			var newsletterName = $(this).closest("tr").find('td:nth-child(2)').text();
			
			// az <a> html elemet hozzárendeljük a link nevű változóhoz
			var link = $(this);
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan el akarja küldeni a <strong>" + newsletterName + "</strong> hírlevelet?", function(result) {
				if (result) {
					//window.location.href = deleteLink;
					
					// paraméter az <a> elem amire klikkeltünk
					submit_newsletter_AJAX_2(link);						
				}
			}); 
		});	
	 
	};
	

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            newsletterTable();
			submitNewsletterConfirm();

			vframework.deleteItems({
                table_id: "newsletter_table",
                url: "admin/newsletter/delete_newsletter_AJAX"
            });

/*
            vframework.changeStatus({
                url: "admin/newsletter/change_status",
            });
*/
            vframework.printTable({
                print_button_id: "print_newsletter", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "newsletter_table",
                title: "Hírlevél statisztikák"
            }); 

			vframework.hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Newsletter_stats.init();
});