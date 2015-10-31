/**
Newsletter_stats oldal
**/
var Newsletter = function () {

    var newsletterTable = function () {

        var table = $('#newsletter_table');
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
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
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

        var tableWrapper = jQuery('#newsletter_table_wrapper');

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
	
	var deleteOneNewsletterConfirm = function () {
	 		$('[id*=delete_newsletter]').on('click', function(e){
               	e.preventDefault();
				//var deleteLink = $(this).attr('href');
				var newsletterName = $(this).closest("tr").find('td:nth-child(2)').text();
				
				// az <a> html elemet hozzárendeljük a link nevű változóhoz
				var link = $(this);
				
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan törölni akarja a <strong>" + newsletterName + "</strong> hírlevelet?", function(result) {
					if (result) {
						//window.location.href = deleteLink;
						
						// paraméter az <a> elem amire klikkeltünk
						delete_newsletter_AJAX(link);						
					}
                }); 
            });	
	 
	}
	
	var deleteNewsletterTableConfirm = function () {
			$('#del_newsletter_form').submit(function(e){
                e.preventDefault();
				currentForm = this;
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm("Biztosan törölni akarja a kijelölt hírleveleket?", function(result) {
					if (result) {
						// a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
						$('#del_newsletter_form').append($("<input>").attr("type", "hidden").attr("name", "delete_newsletter").val("submit_delete_newsletter"));
						currentForm.submit(); 	
					}
                }); 
            });	 		
	}

	
	
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
	 
	}
	
	
	
	
	var enableDisableButtons = function () {
		
		var deleteUserSubmit = $('button[name="del_newsletter_submit"]');
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
	

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}

	
	var printTable = function () {
		$('#print_newsletter').on('click', function(e){
		e.preventDefault();
		var divToPrint = document.getElementById("newsletter_table");
		console.log(divToPrint);
//		divToPrint = $('#newsletter_table tr').find('th:last, td:last').remove();
		newWin= window.open("");
		newWin.document.write(divToPrint.outerHTML);
		newWin.print();
		newWin.close();
		})
	}
	
	
	

	


	/**
	 *	Hírlevél küldés AJAX (jQuery)
	 *
	 */
/*
	var submit_newsletter_AJAX = function(link) {
		//$('button[id*=submit_newsletter]').click(function(event){
		//	event.preventDefault();    
	
			//a button value attribútumának értékét változóhoz rendeljük
			var newsletter_id = link.attr('rel');
			
			//var data = "newsletter_id=" + newsletter_id;
			
			//var lastsent = $(this).closest("tr").find('td:nth-child(5)').text();
			var lastsent = link.closest("tr").find('td:nth-child(5)');
			
			//üzenet elem
			var message = $('#message');

			//megjelenítjük a loading animációt
			$('#loadingDiv').html('<img src="public/admin_assets/img/loader.gif">');
			$('#loadingDiv').show();

			//végrehajtjuk az AJAX hívást
            $.ajax({
                type: "POST",
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    newsletter_id: newsletter_id
                },
                // a feldolgozó url-je
                url: "admin/newsletter/send_newsletter",
                // kész a hívás... utána ez történjen
                complete: function(){
                    $('#loadingDiv').hide();
                },
                // itt kapjuk meg (és dolgozzuk fel) a feldolgozó php által visszadott adatot 
                success: function(result){
					//JSON string elemeinek elhelyezése egy objektumba
					var response = $.parseJSON(result);	
					
					if(response.status == 'success'){
						//$('#result').html('Sikeresen elküldött e-mailek száma: ' + response.success + '<br />' + 'Sikertelen kézbesítések száma: ' + response.fail);
						// frissítjük a táblázatban a hírlevél küldés időpontját
						
						
						message.append('<div class="alert alert-success">' + response.success + ' ' + response.message + '</div>');
						$('#message > div').delay( 2500 ).slideUp( 750, function(){
							$(this).remove();
						} );
					
					
						lastsent.text(response.date);	


						if(response.fail != 0){
							message.append('<div class="alert alert-danger">' + response.fail + ' e-mail küldése sikertelen!</div>');
							$('#message > div').delay( 2500 ).slideUp( 750, function(){
								$(this).remove();
							} );
						}
						
						
					}

					if(response.status == 'error'){
						message.append('<div class="alert alert-danger">' + response.message + '</div>');
						$('#message > div').delay( 2500 ).slideUp( 750, function(){
							$(this).remove();
						} );
					}

				},
                error: function(result, status, e){
                        alert(e);
                } 
            });		
		
		//});
	
		
	} // Hírlevél küldés AJAX (jQuery)
*/




	
	
	/**
	 *	Hírlevél küldés AJAX (XMLHttpRequest)
	 *
	 */
	var submit_newsletter_AJAX_2 = function(link) {
		//$('button[id*=submit_newsletter]').click(function(event){
		//	event.preventDefault();    
	
		// üzenet és statusbar elemek 
			var progress_bar = document.getElementById('progress_bar');
			var progress_pc = document.getElementById('progress_pc');
			var message_box = document.getElementById('message');
			var message_done = document.getElementById('message_done');
	
			//a button value attribútumának értékét változóhoz rendeljük
			var newsletter_id = link.attr('rel');
			
			//var lastsent = $(this).closest("tr").find('td:nth-child(5)').text();
			var lastsent = link.closest("tr").find('td:nth-child(5)');

			
		//végrehajtjuk az AJAX hívást

			// XMLHttpRequest objektum létrehozása
			var xmlhttp = new XMLHttpRequest();
			
			// ez a változó tárolja az utolsó (új) választ 
			var new_response;
			//ez a változó tárolja az előző választ
			var previous_text = '';
			
	
			// a readyState tulajdonság tárolja hol tart a lekérdezés (0: request not initialized, 1: server connection established, 2: request received, 3: processing request, 4: request finished and response is ready)
			// a onreadystatechange tulajdonság egy függvényt tárol, ami meghívódik, amikor a readyState tulajdonság értéke változik
			xmlhttp.onreadystatechange = function(){
				try{
				
					if(xmlhttp.readyState==1) {
						//megjelenítjük a loading animációt
						$('#loadingDiv').html('<img src="public/admin_assets/img/loader.gif">');
						$('#loadingDiv').show();
					}
					/*
					else if(xmlhttp.readyState==2) {
						console.log('2-es_fazis');
					}
					*/
					else if(xmlhttp.readyState == 3) {
						
						// az új válasz megadása és leválasztása az előzőekről
						// a substring() metódus (1 paraméterrel meghívva) levágja a string elejéről a megadott számú karaktert
						// levágja a előző válaszokat, így csak az utolsó válasz stringje marad meg, amit feldolgoz a JSON.parse()
						new_response = xmlhttp.responseText.substring(previous_text.length);
						
					console.log("responseText: " + new_response);
						
						// átalakítjuk a kapott json stringet objektummá
						var result = JSON.parse(new_response);

						// html5 <progress> elem value attribútumának módosítása (csík) ,és az elem megjelenítése
						//progress_bar.attr('value', result.progress); //jQuery
						progress_bar.style.display = 'block';
						progress_bar.setAttribute('value', result.progress);

						progress_pc.innerHTML = result.progress + '%';
						//message_box.innerHTML += result.message + '<br />';
						$( "#message-box" ).addClass( "alert alert-info" );
						//a válasz üzenet previous_text tulajdonsághoz rendelése (ezt fogjuk levágni a következő üzenet elejéről) 
						previous_text = xmlhttp.responseText;
					}
					
					else if(xmlhttp.readyState==4 && xmlhttp.status==200){
						console.log('done!');
						//console.log("összes_válasz hossza: " + xmlhttp.responseText.length);
						//console.log("utolsó válasz hossza: " + new_response.length);
						
						//a teljes válasz hossza és az új válasz hosszának a különbsége adja meg, hogy mennyit kell levágni a teljes válasz elejéről, hogy az utolsó választ megkapjuk
						var clip = xmlhttp.responseText.length - new_response.length;

						// átalakítjuk a kapott json stringet feldolgozható objektummá
						var result = JSON.parse(xmlhttp.responseText.substring(clip));
						
						//eltüntetjük a loading animációt	
						$('#loadingDiv').hide();	

						// berakjuk a message_done div-be az utolsó választ
						message_done.innerHTML = "Sikeresen elküldött e-mailek száma: " + result.success + "<br />";
						message_done.innerHTML += "Sikertelen küldések száma: " + result.fail + "<br />";
						message_done.innerHTML += result.message + "<br />";
						$('div.alert-info').delay( 6500 ).slideUp( 750 );
					}
				
				}
				catch (e){
					console.log("[XMLHTTP STATECHANGE] Exception: " + e);
				} 
			}
			
			// kérés előkészítése egy php fájlnak, vagy metódusnak (a harmadik paraméter true, ezért a kérés asszinkron, vagy is nem áll le az oldal működése a kérés teljesüléséig)
			xmlhttp.open("POST","admin/newsletter/send_newsletter",true);
			xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest");
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			
			// mivel nem küldünk adatot, csak kérést a send() metódust paraméter nélkül hívjuk meg (mert ide kerülne az elküldendő adat ami a POST tömbbe kerülne)
			xmlhttp.send("newsletter_id="+newsletter_id);            	
		
		//});
	
		
	} // Hírlevél küldés AJAX (XMLHttpRequest)





	/**
	 *	Hírlevél törlése
	 *
	 */
	var delete_newsletter_AJAX = function(link) {
		//$('a[id*=delete_newsletter]').click(function(event){
			//event.preventDefault();    
		
			
			//a link rel attribútumának értékét változóhoz rendeljük (ez tartalmazza az id-t)
			var newsletter_id = link.attr('rel');
			
			// a del_tr változóhoz rendeljük a html táblázat törlendő sorát
			var del_tr = link.closest("tr");
			
			//üzenet elem
			var message = $('#message');
			
			//megjelenítjük a loading animációt
			$('#loadingDiv').html('<img src="public/admin_assets/img/loader.gif">');
			$('#loadingDiv').show();

			//végrehajtjuk az AJAX hívást
            $.ajax({
                type: "POST",
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    newsletter_id: newsletter_id
                },
                // a feldolgozó url-je
                url: "admin/newsletter/delete_newsletter_AJAX",
                // kész a hívás... utána ez történjen
                complete: function(){
                    $('#loadingDiv').hide();
                },
                // itt kapjuk meg (és dolgozzuk fel) a feldolgozó php által visszadott adatot 
                success: function(result){
					//JSON string elemeinek elhelyezése egy objektumba
					var response = $.parseJSON(result);	
					
					if(response.status == 'success'){
						message.append('<div class="alert alert-success">' + response.message + '</div>');
						$('#message > div').delay( 2500 ).slideUp( 750, function(){
							$(this).remove();
						} );
						del_tr.remove();
					}

					if(response.status == 'error'){
						message.append('<div class="alert alert-danger">' + response.message + '</div>');
						$('#message > div').delay( 2500 ).slideUp( 750, function(){
							$(this).remove();
						} );	
					}

				},
                error: function(result, status, e){
                        alert(e);
                } 
            });		
		
		//});
	
		
	}	
	
	

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            newsletterTable();
			deleteOneNewsletterConfirm();
			deleteNewsletterTableConfirm();
			submitNewsletterConfirm();
			enableDisableButtons();
			hideAlert();
			printTable();
			submit_newsletter_AJAX_2;
			
        }

    };

}();

$(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features 
	Newsletter.init(); // init Newsletter page
		
});