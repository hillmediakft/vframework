var Portfolio = function () {

	var mixGrid = function () {
		 // $('.mix-grid').mixitup();						 		
		 $('#mixitup_container').mixitup();						 		
	};
	
	var deletePhotoConfirm = function () {
		$('#mixitup_container').on('click', '.mix-delete', function(e){
            e.preventDefault();

			var id = $(this).attr('data-id');
            var deleted_photo = $('#photo_' + id); // a törlendo fotó div eleme
			
			bootbox.setDefaults({
				locale: "hu"
			});
			
			bootbox.confirm("Biztosan törölni akarja a fotót?", function(result) {
				if (result) {
					delete_photo(id, deleted_photo);
				}
            }); 
        });			
	};


	/**
	 * Photo törlése ajax-al
	 *
	 * @param array id
	 * @param objektum deleted_photo 	HTML elem, amit törölni kell a dom-ból
	 */
	var delete_photo = function (id, deleted_photo) {

        $.ajax({
            url: "admin/photo-gallery/delete_photo",
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

                    deleted_photo.remove(); // HTML elem törlése a DOM-ból

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

	
    return {
        //main function to initiate the module
        init: function () {
            mixGrid();
			deletePhotoConfirm();
			
			vframework.hideAlert();
        }

    };

}();

jQuery(document).ready(function() {    
	Portfolio.init();
});