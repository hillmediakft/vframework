/**
Users oldal
**/
var Users = function () {

    var usersTable = function () {

        var table = $('#users');
		// begin first table
        
	
		table.dataTable({

            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                //"decimal":        "",
                "emptyTable":     "Nincs megjeleníthető adat!",
                "info":           "_START_ - _END_ elem _TOTAL_ elemből",
                "infoEmpty":      "Nincs megjeleníthető adat!",
                "infoFiltered":   "(Szűrve _MAX_ elemből)",
                //"infoPostFix":    "",
                //"thousands":      ",",
                "lengthMenu":     " _MENU_ elem/oldal",
                "loadingRecords": "Betöltés...",
                "processing":     "Feldolgozás...",
                "search":         "Keresés:",
                "zeroRecords":    "Nincs egyező elem",
                "paginate": {
                    "first":      "Első",
                    "last":       "Utolsó",
                    "next":       "Következő",
                    "previous":   "Előző"
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
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

            "pagingType": "bootstrap_full_number",

            "columnDefs": [
                {'searchable': false, 'targets': 0},
                {'searchable': false, 'targets': 1},
                {'searchable': false, 'targets': 2},
                {'searchable': false, 'targets': 3},
                {'searchable': false, 'targets': 4},
                {'searchable': false, 'targets': 5},
                {'searchable': false, 'targets': 6},
                {'searchable': false, 'targets': 7},
                {"searchable": false, "targets": 8}
            ],
            
            "order": [
                [2, "asc"]
            ] // set column as a default sort by asc

        });

        var tableWrapper = jQuery('#users_wrapper');

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
/*
        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });
*/
        tableWrapper.find('.dataTables_length select').addClass("form-control input-sm input-inline"); // modify table per page dropdown
    }

	/**
	 * Egy user törlése ajax-al confirm
	 */
	var deleteOneUserConfirm = function () {
 		$('[id*=delete_user]').on('click', function(e){
           	e.preventDefault();

           	// A törlendő user neve
			var userName = $(this).closest("tr").find('td:nth-child(4)').text();
            // a törlendő elem id-je
            var deleteID = $(this).attr('data-id');
            // a deleteHtml változóhoz rendeljük a html táblázat törlendő sorát <tr>
            var deleteRow = $(this).closest("tr");
            // üzenet elem
            var message = $('#ajax_message');
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan törölni akarja " + userName + " felhasználót?", function(result) {
				if (result) {
					deleteUser(deleteID, deleteRow);
				}
            }); 
        });	
	}

	/**
	 * Userek csoportos törlése ajax-al confirm
	 */
	var deleteGroupUserConfirm = function () {
		$('#del_user_group').on('click', function(){

			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan törölni akarja felhasználókat?", function(result) {
				if (result) {

					// tömb, ami azokat az id-ket fogja tartalmazni, ahol be van kapcsolva a checkbox
					var id_array = new Array(); // a törlendő id-ket tartalamzó tömb
					// checkbox objektumok
					var checkboxes = $('input.checkboxes');

					// bejárjuk a checkboxokat tartalmazó objektumot
					$.each(checkboxes, function(index, val) {
						if( $(this).is(':checked') ){
							// ha be van kapcsolva az aktuális checkbox, akkor a value értékét berakjuk a id_array tömbbe
							id_array.push($(this).val());
						}
					});
					
					// átalakítjuk a tömböt felsorolás stringre pl. 12,54,65
					var id_string = id_array.toString();
					// a második paraméter a törlendő html elem, de csoportos törlésnél 
					deleteUser(id_string, null);
				}
			});
		});
	}	

	/**
	 * User(ek) törlése ajax-al
	 *
	 * @param array 				id_string 	törlendő id-ket tartalamzó string: "12,45,78" vagy "23"
	 * @param objektum vagy null 	deleteRow 	HTML elem, amit törölni kell a dom-ból (csoportos törlésnél null az értéke!)
	 */
	var deleteUser = function (id_string, deleteRow) {

        // üzenet elem
        var message = $('#ajax_message');
		// törlendő HTML elem
        var deleteTR;

        $.ajax({
            url: 'admin/users/teszt_delete_user',
            type: 'POST',
            dataType: 'json',
            data: {
                user_id: id_string
            },
            beforeSend: function() {
                Metronic.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
            },
            complete: function(){
                Metronic.unblockUI();
            },
            success: function (result) {
                if(result.message_success) {

                	if(deleteRow != null){
						// HTML <tr> törlése
                    	deleteRow.remove();
                	}
                	else {
                      	var checkboxes = $('input.checkboxes');
                        $.each(checkboxes, function(index, val) {
							if( $(this).is(':checked') ){
								// a deleteTR változóhoz rendeljük a html táblázat törlendő sorát <tr>
    							deleteTR = $(this).closest("tr");
								// HTML <tr> törlése
								deleteTR.remove();
							}
                        });	
                	}

                    Metronic.alert({
                        type: 'success',
                        //icon: 'warning',
                        message: result.message_success,
                        container: $('#ajax_message'),
                        place: 'append',
                        close: true, // make alert closable
                        //reset: true, // close all previouse alerts first
                        //focus: true, // auto scroll to the alert after shown
                        closeInSeconds: 3 // auto close after defined seconds
                    });                                
                }
                if(result.message_error) {

                    Metronic.alert({
                        type: 'danger',
                        //icon: 'warning',
                        message: result.message_error,
                        container: $('#ajax_message'),
                        place: 'append',
                        close: true, // make alert closable
                        //reset: true, // close all previouse alerts first
                        //focus: true, // auto scroll to the alert after shown
                        closeInSeconds: 3 // auto close after defined seconds
                    });                                
                }
                if(result.status) {
                	if(result.status == 'error') {
                        Metronic.alert({
                            type: 'danger',
                            //icon: 'warning',
                            message: result.message,
                            container: $('#ajax_message'),
                            place: 'append',
                            close: true, // make alert closable
                            //reset: true, // close all previouse alerts first
                            //focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 3 // auto close after defined seconds
                        });
                	}
                }
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(errorThrown);
                console.log("Hiba történt: " + textStatus);
				console.log("Rendszerválasz: " + xhr.responseText); 
            } 
        }); // ajax end
	}

	var resetSearchForm = function () {
		$('#reset_search_form').on('click', function(){
		$(':input', '#users_search_form')
		.not(':button, :submit, :reset, :hidden')
		.val('')
		.removeAttr('checked')
		.removeAttr('selected');
    }); 								 		
	}

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}

    var makeActiveConfirm = function () {
		$('[id*=make_active], [id*=make_inactive]').on('click', function(e){
			e.preventDefault();
			
			var action = $(this).attr('data-action');
			var userId = $(this).attr('rel');
			var elem = this;
			var userName = $(this).closest("tr").find('td:nth-child(4)').text();
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan módosítani akarja " + userName + " státuszát?", function(result) {
				if (result) {
					makeActive(userId, action, elem);
				}
			}); 
		});	 		
	}
	
	var makeActive = function (userId, action, elem) {
		//üzeneteket tartalamzó elem
		var ajax_message = $("#ajax_message");
		
		$.ajax({
			type: "POST",
			data: {
				id: userId,
				action: action
			},
			url: "admin/users/change_status",
			dataType: "json",
			beforeSend: function() {
                Metronic.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
			},
			complete: function(){
				Metronic.unblockUI();
			},
			success: function (result) {
				if(result.status == 'success') {
				
					if(action == 'make_inactive') {
						$(elem).html('<i class="fa fa-check"></i> Aktivál');
						$(elem).attr('data-action', 'make_active');
						$(elem).closest('td').prev().html('<span class="label label-sm label-danger">Inaktív</span>');
						
                        Metronic.alert({
                            type: 'success',
                            //icon: 'warning',
                            message: result.message,
                            container: $('#ajax_message'),
                            place: 'append',
                            close: true, // make alert closable
                            //reset: true, // close all previouse alerts first
                            //focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 3 // auto close after defined seconds
                        });

					}
					else if(action == 'make_active') {
						$(elem).html('<i class="fa fa-ban"></i> Blokkol');
						$(elem).attr('data-action', 'make_inactive');
						$(elem).closest('td').prev().html('<span class="label label-sm label-success">Aktív</span>');
						
						Metronic.alert({
                            type: 'success',
                            //icon: 'warning',
                            message: result.message,
                            container: $('#ajax_message'),
                            place: 'append',
                            close: true, // make alert closable
                            //reset: true, // close all previouse alerts first
                            //focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 3 // auto close after defined seconds
                        });
					}
				
				}
				if(result.status == 'error') {
					console.log('Hiba: az adatbázis művelet nem történt meg!');
					Metronic.alert({
	                            type: 'danger',
	                            //icon: 'warning',
	                            message: result.message,
	                            container: $('#ajax_message'),
	                            place: 'append',
	                            close: true, // make alert closable
	                            closeInSeconds: 5 // auto close after defined seconds
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
	
	
	var printTable = function () {
		$('#print_users').on('click', function(e){
		e.preventDefault();
		var divToPrint = document.getElementById("users");
		console.log(divToPrint);
//		divToPrint = $('#users tr').find('th:last, td:last').remove();
		newWin= window.open("");
		newWin.document.write(divToPrint.outerHTML);
		newWin.print();
		newWin.close();
		})
	
	}
	

	
    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            usersTable();
			deleteOneUserConfirm();
            deleteGroupUserConfirm();
			makeActiveConfirm();
			resetSearchForm();
			hideAlert();
			printTable();
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features 
	Users.init(); // init users page
});