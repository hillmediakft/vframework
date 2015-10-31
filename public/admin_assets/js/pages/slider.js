/**
Slider oldal
**/
var Slider = function () {

// sortable list ajax hívás után is működni fog

	var slideOrder = function() {
	
		$('tbody#slider_list').sortable({
			distance: 10,
			cursor: "move",
			axis: "y",
			revert: true,
			opacity: 0.7,
			tolerance: "pointer",
			containment: "parent",
			update: function(event, ui){
				$('#loadingDiv').show();
			
				$.ajax({
					url: "admin/slider/order",
					type: 'POST',
					data: {
						// a slider_id elemekből képez egy tömböt
						order: $( this ).sortable( "serialize"),
						action: 'update_slider_order'
					},
					success: function(msg) {
						//console.log(msg);
						$('#loadingDiv').hide();
						$('#ajax_message .alert-success').html(msg).show();
						hideAlert();
					}
				});
						   
			} 
					
		});
	}	
	
	// üzenet doboz eltüntetése
	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}
	
	var deleteSlideConfirm = function () {
		$('[id*=delete]').on('click', function(e){
               e.preventDefault();
			var deleteLink = $(this).attr('href');
			bootbox.setDefaults({
				locale: "hu", 
			});
			bootbox.confirm("Biztosan törölni akarja a slide-ot?", function(result) {
				if (result) {
					window.location.href = deleteLink; 	
				}
            }); 
        });			
	}	
	
	var printPage = function () {
			$('[id*=print]').on('click', function(e){
                e.preventDefault();
				window.print();
            });			
	}
	
	return {
        //main function to initiate the module
        init: function () {
            
            slideOrder();
			//hideAlert();
			printPage();
			deleteSlideConfirm();
        }
    };

}();

$(document).ready(function() {  
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features	
	Slider.init();
});

