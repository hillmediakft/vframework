/**
 * vframework objektum admin
 *
 */
var vframework = function () {

    /**
     * Ajax üzenet html elem  
     */
    var ajax_message = $('#ajax_message');

	/**
	 * Egy elem törlése ajax-al confirm
	 */
	var _deleteOneConfirm = function (options) {
 		$('table#' + options.table_id).on('click', '.delete_item', function(e){
           	e.preventDefault();

			//var name = $(this).closest("tr").find('td:nth-child(3)').text();
            var id = $(this).attr('data-id'); // a törlendő elem id-je
            var deleteRow = $(this).closest("tr"); // a deleteHtml változóhoz rendeljük a html táblázat törlendő sorát <tr>
			
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm(options.confirm_message, function(result) {
				if (result) {
					_delete_item(id, deleteRow, options);
				}
            }); 
        });	
	};

	/**
	 * Elemek csoportos törlése ajax-al confirm
     *
     * @param objektum options  a beállításokat tartalmazza
	 */
	var _deleteGroupConfirm = function (options) {
		$('#delete_group').on('click', function(){

			var id_array = new Array(); // a törlendő id-ket tartalamzó tömb
			var checkboxes = $('#' + options.table_id + ' input.checkboxes'); // a táblában lévő checkbox objektumok

			// bejárjuk a checkboxokat tartalmazó objektumot
			$.each(checkboxes, function(index, val) {
				if( $(this).is(':checked') ){
					// ha be van kapcsolva az aktuális checkbox, akkor a value értékét berakjuk a id_array tömbbe
					id_array.push($(this).val());
				}
			});

			// ellenőrizzük, hogy be van-e jelölve checkbox (ha az id_array tömb üres nincs bejelölve semmi)
			if(id_array.length == 0){
                App.alert({
                    type: 'warning',
                    icon: 'warning',
                    message: "&nbsp;Nincs elem kiválasztva!",
                    container: ajax_message,
                    place: 'append',
                    close: true, // make alert closable
                    //reset: true, // close all previouse alerts first
                    //focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 3 // auto close after defined seconds
                });
			}
			else {
				bootbox.setDefaults({
					locale: "hu", 
				});
				bootbox.confirm(options.confirm_message_group, function(result) {
					if (result) {
						// átalakítjuk a tömböt felsorolás stringre pl. 12,54,65
						var id = id_array.toString();
						// a második paraméter a törlendő html elem, de csoportos törlésnél 
						_delete_item(id, null, options);
					}
				});
			}

		});
	};

	/**
	 * Tábla elemek törlése ajax-al
	 *
	 * @param array 				id         	törlendő id-ket tartalamzó string: "12,45,78" vagy csak egyet "23"
	 * @param objektum vagy null 	deleteRow 	HTML elem, amit törölni kell a dom-ból (csoportos törlésnél null az értéke!)
	 * @param objektum 				options 	a beállításokat tartalmazza
	 */
	var _delete_item = function (id, deleteRow, options) {

        $.ajax({
            url: options.url,
            type: 'POST',
            dataType: 'json',
            data: {
                item_id: id
            },
            beforeSend: function() {
                App.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
            },
            complete: function(){
                App.unblockUI();
            },
            success: function (result) {
                
                if (result.status == 'success') {

                    // ha datatable-et használ a táblázat
                    if (options.datatable == true) {

                        // datatable objektum hozzárendelése a table változóhoz
                        var table = $('#' + options.table_id).DataTable();

                        // HTML elemek törlése a DOM-ból (csoportos törlésnél a deleteRow-nak null az értéke)
                        if(deleteRow != null){
                            // HTML táblázat sorának kiválasztása, törlése, és a táblázat újrarajzolása
                            table.row( deleteRow ).remove().draw();
                        }
                        else {
                            var checkboxes = $('#' + options.table_id + ' input.checkboxes');
                            $.each(checkboxes, function(index, val) {
                                if( $(this).is(':checked') ){
                                    // HTML táblázat aktuális sorának kiválasztása, törlése
                                    table.row( $(this).closest("tr") ).remove();
                                }
                                // A táblázat újrarajzolása
                                table.draw();
                            }); 
                        }
                    } else {
                        deleteRow.remove(); // HTML elem törlése a DOM-ból
                    }

                    if(result.message_success) {
                        App.alert({
                            type: 'success',
                            //icon: 'warning',
                            message: result.message_success,
                            container: ajax_message,
                            place: 'append',
                            close: true, // make alert closable
                            reset: false, // close all previouse alerts first
                            //focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 3 // auto close after defined seconds
                        });                                
                    }
                    if (result.message_error) {
                        App.alert({
                            type: 'warning',
                            //icon: 'warning',
                            message: result.message_error,
                            container: ajax_message,
                            place: 'append',
                            close: true, // make alert closable
                            reset: false, // close all previouse alerts first
                            //focus: true, // auto scroll to the alert after shown
                            closeInSeconds: 3 // auto close after defined seconds
                        });  
                    }
                
                }    
                else if (result.status == 'error') {
                    App.alert({
                        type: 'danger',
                        //icon: 'warning',
                        message: result.message,
                        container: ajax_message,
                        place: 'append',
                        close: true, // make alert closable
                        //reset: false, // close all previouse alerts first
                        //focus: true, // auto scroll to the alert after shown
                        closeInSeconds: 5 // auto close after defined seconds
                    });
                }
            },
            error: function(xhr, textStatus, errorThrown){
                console.log(errorThrown);
                console.log("Hiba történt: " + textStatus);
				console.log("Rendszerválasz: " + xhr.responseText); 
            } 
        }); // ajax end
	};

    /**
     * statusz megvaltoztatása
     */
    var _change_status = function (options) {
		$('.change_status').on('click', function(e){
			e.preventDefault();
			
			var action = $(this).attr('data-action');
			var id = $(this).attr('data-id');
			var elem = this;
			//var name = $(this).closest("tr").find('td:nth-child(3)').text();
			
			bootbox.setDefaults({
				locale: "hu"
			});
			bootbox.confirm(options.confirm_message, function(result) {
				if (result) {

                    $.ajax({
                        type: "POST",
                        data: {
                            id: id,
                            action: action
                        },
                        url: options.url,
                        dataType: "json",
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Feldolgozás...'
                            });
                        },
                        complete: function(){
                            App.unblockUI();
                        },
                        success: function (result) {
                            if(result.status == 'success') {
                            
                                if(action == 'make_inactive') {
                                    $(elem).html('<i class="fa fa-check"></i> Aktivál');
                                    $(elem).attr('data-action', 'make_active');
                                    $(elem).closest('td').prev().html('<span class="label label-sm label-danger">Inaktív</span>');
                                    
                                    App.alert({
                                        type: 'success',
                                        //icon: 'warning',
                                        message: result.message,
                                        container: ajax_message,
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
                                    
                                    App.alert({
                                        type: 'success',
                                        //icon: 'warning',
                                        message: result.message,
                                        container: ajax_message,
                                        place: 'append',
                                        close: true, // make alert closable
                                        //reset: true, // close all previouse alerts first
                                        //focus: true, // auto scroll to the alert after shown
                                        closeInSeconds: 3 // auto close after defined seconds
                                    });
                                }
                            
                            }
                            if(result.status == 'error') {
                                App.alert({
                                    type: 'danger',
                                    //icon: 'warning',
                                    message: result.message,
                                    container: ajax_message,
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
                    }); // ajax call end

				}
			}); 
		});	 		
	};

    /**
     * Táblázat nyomtatása
     */
    var _printTable = function (options) {
        $('#' + options.print_button_id).on('click', function (e) {
            e.preventDefault();

            var divToPrint = $('#' + options.table_id);

            newWin = window.open("");
            newWin.document.write('\
<html>\n\
<head>\n\
<title>' + options.title + '</title>\n\
<link href="public/admin_assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>\n\
<link href="public/admin_assets/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>\n\
<link href="public/admin_assets/css/print.css" rel="stylesheet" type="text/css"/>\n\
</head>\n\
<body>\n\
<div class="container">\n\
<div class="row">\n\
<h3>' + options.title + '</h3\n\
><table class="table table-striped table-bordered">');
            newWin.document.write(divToPrint.html());
            newWin.document.write('\
</table>\n\
</div>\n\
</div>\n\
</body></html>');

            newWin.print();
            newWin.close();
        });
    };    
 
	return {
 
		//main method to initiate page
		init: function () {           
			// call local function
		},
 
		// törlés
		deleteItems: function (options) 
		{
            // a paraméterban kapott opciók összeolvasztása a default opciókkal	
            options = $.extend(true, {
                table_id: "", // táblázat html elem id attribútuma
                url: "", // ajax hívás url-je
                datatable: true, // ha a táblázat nem használ datatable plug-int, akkor ennek az elemnek false értéket kell adni
                confirm_message: "Biztosan törölni akarja a rekordot?",
                confirm_message_group: "Biztosan törölni akarja a rekordokat?"
            }, options);

            _deleteOneConfirm(options);
            _deleteGroupConfirm(options);
		},
		
        // státusz módosítás
        changeStatus: function (options) 
        {
            options = $.extend(true, {
                url: "", //
                confirm_message: "Biztosan végre akarja hajtani a státusz megváltoztatását?"
            }, options);

            _change_status(options);
        },

        // oldal nyomtatása
		printTable: function (options) 
		{
            options = $.extend(true, {
                print_button_id: "print_table", // elem id-je, amivel elindítjuk a nyomtatást 
                table_id: "",
                title: ""
            }, options);

            _printTable(options);
		},

        hideAlert: function () {
            $('div.alert.alert-success, div.alert.alert-danger').delay( 3000 ).slideUp( 750 );                                
        },

        // ckeditor bekapcsolása (options: { textarea_name_attr: "config_name" })
        ckeditorInit: function (options)
        {
            // ha nem létezik ckeditor objektum
            if (typeof CKEDITOR === 'undefined') {
                console.log('Nem talalhato a ckeditor objektum!');
                return;
            }

            $.each(options, function(index, val) {
                if (val != "") {
                    CKEDITOR.replace( index, {customConfig: val + '.js'});   
                } else {
                    CKEDITOR.replace( index );   
                }
            });    
        }
	};

}();