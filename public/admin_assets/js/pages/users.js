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
                {"orderable": true}, //munkák száma
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
                {'searchable': false, 'targets': 8},
                {"searchable": false, "targets": 9}
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
	
	var deleteOneUserConfirm = function () {
	 		$('[id*=delete_user]').on('click', function(e){
               	e.preventDefault();
				var deleteLink = $(this).attr('href');
				var userName = $(this).closest("tr").find('td:nth-child(4)').text();
				
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan törölni akarja " + userName + " felhasználót?", function(result) {
					if (result) {
						window.location.href = deleteLink; 	
					}
                }); 
            });	
	 
	}
	
	var deleteUsersConfirm = function () {
			$('#del_user_form').submit(function(e){
                e.preventDefault();
				currentForm = this;
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan törölni akarja a felhasználókat?", function(result) {
					if (result) {
						// a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
						$('#del_user_form').append($("<input>").attr("type", "hidden").attr("name", "delete_user").val("submit_delete_user"));
						currentForm.submit(); 	
					}
                }); 
            });	 		
	}

	var enableDisableButtons = function () {
		
		var deleteUserSubmit = $('button[name="del_user_submit"]');
		var checkAll = $('input.group-checkable');
		var checkboxes = $('input.checkboxes');
			
		deleteUserSubmit.attr('disabled', true);
			
		checkboxes.change(function(){
			$(this).closest("tr").find('.btn-group a').attr('disabled', $(this).is(':checked'));
			deleteUserSubmit.attr('disabled', !checkboxes.is(':checked'));
        });		
		checkAll.change(function(){
			checkboxes.closest("tr").find('.btn-group a').attr('disabled', $(this).is(':checked'));
			deleteUserSubmit.attr('disabled', !checkboxes.is(':checked'));
        });	
		
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

	var hideSearchPortlet = function () {
		$('#search-portlet').hide();						 		
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
		//üzeneteket megjelenítő elemek
		var success_div = $("#ajax_message .alert-success");
		var error_div = $("#ajax_message .alert-danger");
		
		$.ajax({
			type: "POST",
			data: {
				id: userId,
				action: action
			},
			url: "admin/users/change_status",
			dataType: "json",
			beforeSend: function() {
				$('#loadingDiv').show();
			},
			complete: function(){
				$('#loadingDiv').hide();
			},
			success: function (result) {
				if(result.status == 'success') {
				
					if(action == 'make_inactive') {
						$(elem).html('<i class="fa fa-check"></i> Aktivál');
						$(elem).attr('data-action', 'make_active');
						$(elem).closest('td').prev().html('<span class="label label-sm label-danger">Inaktív</span>');
						success_div.html(result.message).show();
					}
					else if(action == 'make_active') {
						$(elem).html('<i class="fa fa-ban"></i> Blokkol');
						$(elem).attr('data-action', 'make_inactive');
						$(elem).closest('td').prev().html('<span class="label label-sm label-success">Aktív</span>');
						success_div.html(result.message).show();
					}
				
				}
				if(result.status == 'error') {
					//console.log('Hiba: az adatbázis művelet nem történt meg!');
					error_div.html(result.message).show();
				}
				//üzenet elem elrejtése
				hideAlert();
			},
			error: function(result, status, e){
				alert(e);
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
			deleteUsersConfirm();
			enableDisableButtons();
			resetSearchForm();
			hideAlert();
			//hideSearchPortlet();
			makeActiveConfirm();
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