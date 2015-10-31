$(document).ready(function() {    

	var cropSlide = function () {
		var slideImage = $('#slide_image');
		slideImage.css("width", '755px').css("height", '372px');;

		
		var cropperOptions = {
				uploadUrl:'admin/slider/upload_slider_img',
				cropUrl:'admin/slider/crop_uploaded_img',
				outputUrlId:'OutputId',
				rotateControls: false,
				doubleZoomControls: false
				
				
			}
		var cropperHeader = new Croppic('slide_image', cropperOptions);
	}
	


	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	cropSlide();
	
});