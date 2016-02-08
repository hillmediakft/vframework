var editBlog = function () {

	/**
	 *	Form adatok elküldése
	 */
	var send_form = function(){

		$("#update_blog_form").submit(function (e){
			e.preventDefault();

			Metronic.blockUI({
	            boxed: true,
	            message: 'Feldolgozás...'
	        });	

			currentForm = this;

			setTimeout(function(){
				currentForm.submit();
			}, 300);

		});
	}

	var handleDatePickers = function () {

		if (jQuery().datepicker) {
			$('.date-picker').datepicker({
				rtl: Metronic.isRTL(),
				orientation: "left",
				autoclose: true,
				format:"yyyy-mm-dd",
				language: "hu-HU",
                                startDate: '0d',
                                endDate: '+2m'
			});
			//$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
		}
	}

	var hideAlert = function () {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}

    var ckeditorInit = function () {
        CKEDITOR.replace( 'blog_body', {customConfig: 'config_custom3.js'});
    }

    return {
        //main function to initiate the module
        init: function () {
			send_form();
			handleDatePickers();
			hideAlert();
			ckeditorInit();
        }
    };

}();

jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features	
	editBlog.init();
});