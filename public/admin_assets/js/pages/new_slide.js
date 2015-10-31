$(document).ready(function() {    

	// üzenet doboz eltüntetése
	function hideAlert() {
		$('div.alert').delay( 2500 ).slideUp( 750 );						 		
	}
	

	hideAlert();
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	//cropSlide();
	
});