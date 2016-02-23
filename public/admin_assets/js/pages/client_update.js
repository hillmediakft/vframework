var Client_update = function () {

    var cropClientPhoto = function () {
        var userPhoto = $('#client_image');
        userPhoto.css("width", '154px').css("height", '104px');
        var cropperOptions = {
            //kérés a user_img_upload metódusnak "upload" paraméterrel
            uploadUrl: 'admin/clients/client_img_upload/upload',
            //kérés a user_img_upload metódusnak "crop" paraméterrel
            cropUrl: 'admin/clients/client_img_upload/crop',
            outputUrlId: 'OutputId',
            modal: false,
            doubleZoomControls: false,
            rotateControls: false,
            loaderHtml: '<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
        }
        var cropperHeader = new Croppic('client_image', cropperOptions);
    }

    var oldImg = $('#old_img').val();
    $('#client_image').css('background-image', 'url(' + oldImg + ')');

    var submitUpdateClient = function () {
        $('#update_client').submit(function (e) {
            e.preventDefault();
            currentForm = this;
            // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
            //$('#update_client').append($("<input>").attr("type", "hidden").attr("name", "submit_update_client").val("submit_update_client"));
            Metronic.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });
        //    currentForm.submit();
			setTimeout(function(){ currentForm.submit(); },300);
        });
    }

    return {
//main method to initiate page
        init: function () {
            cropClientPhoto();
            submitUpdateClient();
            vframework.hideAlert();
        },
    };
}();
$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    // Demo.init(); // init demo features 
    Client_update.init(); // init users page
});