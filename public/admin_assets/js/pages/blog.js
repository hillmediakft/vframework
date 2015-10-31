/**
Blog oldal
**/
var Blog = function () {

    var blogTable = function () {

        var table = $('#blog');
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

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "columns": [{
                "orderable": false
            }, {
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": false
            }],
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,            
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
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [2, "asc"]
            ] // set column as a default sort by asc
			
		
        });

        var tableWrapper = jQuery('#blog_wrapper');

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
	
	var deleteOneBlogConfirm = function () {
	 		$('[id*=delete_blog]').on('click', function(e){
               	e.preventDefault();
				var deleteLink = $(this).attr('href');
				var blogName = $(this).closest("tr").find('td:nth-child(2)').text();
				
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan törölni akarja " + blogName + " című bejegyzést?", function(result) {
					if (result) {
						window.location.href = deleteLink; 	
					}
                }); 
            });	
	 
	}
	
	var deleteBlogConfirm = function () {
			$('#del_blog_form').submit(function(e){
                e.preventDefault();
				currentForm = this;
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan törölni akarja a blogbejegyzést?", function(result) {
					if (result) {
						// a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
						$('#del_blog_form').append($("<input>").attr("type", "hidden").attr("name", "delete_blog").val("submit_delete_blog"));
						currentForm.submit(); 	
					}
                }); 
            });	 		
	}

	var enableDisableButtons = function () {
		
		var deleteUserSubmit = $('button[name="del_blog_submit"]');
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


	
	var printTable = function () {
		$('#print_blog').on('click', function(e){
		e.preventDefault();
		var divToPrint = document.getElementById("blog");
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

            blogTable();
			deleteOneBlogConfirm();
			deleteBlogConfirm();
			enableDisableButtons();
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
	Blog.init(); // init users page
});