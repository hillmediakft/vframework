var Newsletter = function () {

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
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 20,            
            "pagingType": "bootstrap_full_number",
            "columnDefs": [
            	{"orderable": false, "searchable": false, "targets": 0},
            	{"orderable": true, "searchable": true, "targets": 1},
            	{"orderable": true, "searchable": true, "targets": 2},
            	{"orderable": true, "searchable": false, "targets": 3},
            	{"orderable": true, "searchable": false, "targets": 4},
            	{"orderable": true, "searchable": false, "targets": 5},
            	{"orderable": false, "searchable": false, "targets": 6},
            ],
            "order": [
                [1, "asc"]
            ] // set column as a default sort by asc
        });

        // var tableWrapper = jQuery('#newsletter_table_wrapper');

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
/*
        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });

        tableWrapper.find('.dataTables_length select').addClass("form-control input-sm input-inline"); // modify table per page dropdown
*/   
    };
	
	var submitNewsletterConfirm = function () {
		$('.submit_newsletter').on('click', function(e){
			e.preventDefault();

			var newsletterName = $(this).closest("tr").find('td:nth-child(2)').text();
			var newsletter_id = $(this).attr('data-id');
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan el akarja küldeni a <strong>" + newsletterName + "</strong> hírlevelet?", function(result) {
				if (result) {
				
					startTask(newsletter_id);
				}
			}); 
		});	
	 
	};
	
/*--------------------------------------------------*/	
	
	/**
	 * Változó az eventsource objektumnak
	 */
	var es;
	
	/**
	 * Hírlevél küldését végző metódus
	 *
	 * @param  newsletter_id  az elküldendő hírlevél id-je
	 */  
	var startTask = function(newsletter_id) {
		
		var progress_bar = document.getElementById('progress_bar');
		var progress_pc = document.getElementById('progress_pc');
		var message_done = document.getElementById('message_done');
		var message_box = document.getElementById('message');		
	
		message_box.style.display = 'block';
		progress_bar.style.display = 'block';
	
		var query_string = 'admin/newsletter/send_newsletter?newsletter_id=' + newsletter_id;
	
		es = new EventSource(query_string);
		
		console.log(es);
		
		//a message is received
		es.addEventListener('message', function(e) {
			var result = JSON.parse( e.data );
			  
			addLog(result.message);      
			  
			if(e.lastEventId == 'CLOSE') {
				addLog('Hírlevelek küldése kész');
				es.close();
				progress_bar.value = progress_bar.max; //max out the progress bar
			}
			else {
				progress_bar.value = result.progress;
				progress_pc.innerHTML   = result.progress  + "%";
				progress_pc.style.width = (Math.floor(progress_bar.clientWidth * (result.progress/100)) + 15) + 'px';
			}
		});
		  
		es.addEventListener('error', function(e) {
			addLog('Error occurred');
			es.close();
		});
	};
	  
	var stopTask = function() {
		es.close();
		addLog('Interrupted');
	};
	  
	var addLog = function(message) {
		var message_box = document.getElementById('message');
		message_box.innerHTML += message + '<br>';
		message_box.scrollTop = message_box.scrollHeight;
	};		
	
	
/*--------------------------------------------------*/		
	
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
                title: "Hírlevelek listája"
            }); 

			vframework.hideAlert();
        }

    };

}();

$(document).ready(function() {    
	Newsletter.init(); // init Newsletter page
});