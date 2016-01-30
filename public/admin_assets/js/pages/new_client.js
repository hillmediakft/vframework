var newClient = function () {

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
        }
        var cropperHeader = new Croppic('client_image', cropperOptions);
    }

    var submitClient = function () {
        $('#new_client').submit(function (e) {
            e.preventDefault();
            currentForm = this;
            // a submit() nem küldi el a gomb name értékét, ezért be kell rakni egy hidden elemet
            $('#new_client').append($("<input>").attr("type", "hidden").attr("name", "submit_new_client").val("submit_new_client"));
            Metronic.blockUI({
                boxed: true,
                message: 'Feldolgozás...'
            });
            currentForm.submit();
        });
    }

    var hideAlert = function () {
        $('div.alert.alert-success, div.alert.alert-danger').delay(3000).slideUp(750);
    }
    return {
        init: function () {
            cropClientPhoto();
            submitClient();
            hideAlert();
        },
    };
}();
$(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features 
    newClient.init(); // init users page
});