var userProfile = function () {

	/**
	 *	Hibajelző objektum a form validáláshoz (a hibákat tartalamazó tab "színének" vezérlésében segít)
	 *	Ha valamelyik tab-on hiba van, akkor ebből az objektumból kiolvasható
	 *	ha valamelyik mező hibás az érték false-ra változik
	 */	
	var tab_highlight = {
			
		tab_1_1: {
			name : true,
			email: true
		},
		tab_3_3: {
			password: true,
			password_again: true
		}
	};

	/**
	 *	Form validátor
	 */
    var handleValidation = function() {
		console.log('start validation');

		$('#edit_user').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: true, // do not focus the last invalid input
			ignore: "input[name='img']",
			rules: {
				name: {
					minlength: 2,
					required: true
				},
                first_name: {
					required: true
				},
                last_name: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				password: {
					minlength: 6					
				},
				password_again: {
					equalTo: "#password"
				}
			},
			// az invalidHandler akkor aktiválódik, ha elküldjük a formot és hiba van
			invalidHandler: function (event, validator) { //display error alert on form submit              

				var errors = validator.numberOfInvalids();

				App.alert({
				    container: $('#ajax_message'), // $('#elem'); - alerts parent container(by default placed after the page breadcrumbs)
				    place: "append", // "append" or "prepend" in container 
				    type: 'danger', // alert's type (success, danger, warning, info)
				    message: errors + " mezőt nem megfelelően töltött ki!", // alert's message
				    close: true, // make alert closable
				    reset: true, // close all previouse alerts first
				    focus: true, // auto scroll to the alert after shown
				    closeInSeconds: 5, // auto close after defined seconds
				    icon: "warning" // put icon before the message
				});

			},

			highlight: function (element) { // hightlight error inputs
				//console.log('highlight');	
			
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group                   
				var $input_name = $(element).attr('name');	
				var $tab_id = $(element).closest('.tab-pane').attr('id');                  
				var $current_tab = $(".ver-inline-menu li a[href='#" + $tab_id + "']");

				//ha az objektum tulajdonágainak nevei változókban vannak ez a helyes szintaxis
 				// átállítjuk false-ra a tab_highlight objektum tulajdonságának értékét
				tab_highlight[$tab_id][$input_name] = false;
				//$current_tab.addClass('tab_error');			
				$current_tab.css('color', '#a94442');			
			},

			unhighlight: function (element) { // revert the change done by hightlight
				//console.log('unhighlight');	
			// a form-group-ról leveszi a hibát jelző class-t
				$(element).closest('.form-group').removeClass('has-error'); // set error class to the control group                   
				
			// a tab címének színét állítja	
				var $input_name = $(element).attr('name');	
				var $tab_id = $(element).closest('.tab-pane').attr('id');                  
				var $current_tab = $(".ver-inline-menu li a[href='#" + $tab_id + "']");
				
				//ha az objektum tulajdonágainak nevei változókban vannak ez a helyes szintaxis
 				tab_highlight[$tab_id][$input_name] = true;
				// hiba jelző (default nincs hiba)
				var $error = false;
				// ha valamelyik érték false a tabon belül, akkor beállítjuk az $error váltózót true-ra
				for (var key in tab_highlight[$tab_id]){        
					if(tab_highlight[$tab_id][key] === false){ 
						$error = true;
						//console.log('van hiba!');
					}
				}
				//ha nincs hiba
				if($error === false){
					//console.log('nincs hiba');
					//$current_tab.removeClass('tab_error');
					$current_tab.css('color', '');
				}
			},

			success: function (label) {
				//console.log('success');
				//label.closest('.form-group').removeClass('has-error').addClass("has-success"); // set success class to the control group
				label.closest('.form-group').removeClass('has-error'); // set success class to the control group
			},

			submitHandler: function (form) {
				//console.log('submitHandler');
				//adatok elküldése "normál" küldéssel
				form.submit();
			}
		});
    }	  
	
	/**
	 *	Croppic képvágó és feltöltő plug-in
	 */
	var cropUserPhoto = function () {
		var userPhoto = $('#user_image');
		userPhoto.css("width", '202px').css("height", '202px');
		
		var cropperOptions = {
				//kérés a user_img_upload metódusnak "upload" paraméterrel
				uploadUrl:'admin/user/user_img_upload/upload',
				//kérés a user_img_upload metódusnak "crop" paraméterrel
				cropUrl:'admin/user/user_img_upload/crop',
				outputUrlId:'OutputId',
				modal:false,
				doubleZoomControls:false,
				rotateControls: false,
				loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		}
		var cropperHeader = new Croppic('user_image', cropperOptions);
	}
	
	/**
	 *	User placeholder kép megjelenítés 
	 */
	var handle_oldImg = function () {
		var oldImg = $('#old_img').val();
		$('#user_image').css('background-image', 'url(' + oldImg + ')');	
	}
	
    var hideAlert = function () {
		$('div.alert').delay( 3000 ).slideUp( 750 );						 		
	}  	

    return {
        //main function to initiate the module
        init: function () {

			cropUserPhoto();
			handle_oldImg();
            handleValidation();
			hideAlert();
        }
    };

}();

jQuery(document).ready(function() {    
	userProfile.init();
});