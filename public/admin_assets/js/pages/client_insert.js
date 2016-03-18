var Client_insert = function () {

    var cropClientPhoto = function () {
        var userPhoto = $('#client_image');
        userPhoto.css("width", '152px').css("height", '102px');
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
        };
        var cropperHeader = new Croppic('client_image', cropperOptions);
    };

    var submitClient = function () {
        $('#client_insert_form').submit(function (e) {
            e.preventDefault();
            
            var currentForm = this;

            App.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });
            
            setTimeout(function(){
                currentForm.submit();
            }, 300);
        });
    };

    return {
        init: function () {
            cropClientPhoto();
            submitClient();
            vframework.hideAlert();
        }
    };
}();

$(document).ready(function () {
    Client_insert.init(); // init users page
});