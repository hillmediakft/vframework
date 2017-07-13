var NewGyik = function () {

    /**
     *	Form validátor
     */
    var handleValidation = function () {
        console.log('start handleValidation');
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        // nyelvek
        var langs = $('html').attr('data-langs');
        var langs_array = langs.split(",");
        var first_item = langs_array.shift();
        var title_key = "title_" + first_item;
        var rules = {};
        rules[title_key] = {required: true};
        rules.gyik_category = {required: true};
//console.log(rules);

        var form1 = $('#new_gyik');
        var error1 = $('.alert-danger', form1);
        var error1_span = $('.alert-danger > span', form1);
        var success1 = $('.alert-success', form1);
        //var success1_span = $('.alert-success > span', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "input[name='img']",
            rules: rules,

            // az invalidHandler akkor aktiválódik, ha elküldjük a formot és hiba van
            invalidHandler: function (event, validator) { //display error alert on form submit              

                //success1.hide();
                var errors = validator.numberOfInvalids();
                error1_span.html(errors + ' mezőt nem megfelelően töltött ki!');
                error1.show();
                error1.delay(3000).slideUp(750);
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group 

                //menü cím színének megvátoztatása
                var tab_id = $(element).closest('.tab-pane').attr('id');
                $(".nav-tabs li a[href='#" + tab_id + "']").css('color', '#a94442');
                //$(".nav-tabs li a[href='#" + tab_id + "']").addClass('has-error');

            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group                   
            
                var tab_id = $(element).closest('.tab-pane').attr('id');
                $(".nav-tabs li a[href='#" + tab_id + "']").css('color', '#337ab7');
            },
            success: function (label) {
                //console.log('success');
                //label.closest('.form-group').removeClass('has-error').addClass("has-success"); // set success class to the control group
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                //console.log('submitHandler');
                error1.hide();
                //success1.show();
                App.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
                //adatok elküldése "normál" küldéssel
                window.setTimeout(function () {
                    form.submit();
                }, 500);
            }
        });
    }



    var hideAlert = function () {
        $('div.alert').delay(2500).slideUp(750);
    }

    var ckeditorInit = function () {

        var langs = $('html').attr('data-langs');
        var langs_array = langs.split(",");

        // bejárjuk a checkboxokat tartalmazó objektumot
        $.each(langs_array, function(index, val) {
            CKEDITOR.replace('description_' + val, {customConfig: 'config_custom3.js'});
        });

    };
    
    var multipleSelect = function () {

        // Set the "bootstrap" theme as the default theme for all Select2
        // widgets.
        //
        // @see https://github.com/select2/select2/issues/2927


        $(".select2-multiple").select2({
            theme: "classic"
        });
    }    

    return {
        //main function to initiate the module
        init: function () {
            handleValidation();
            hideAlert();
            ckeditorInit();
            multipleSelect();
        }
    };


}();


jQuery(document).ready(function () {
    NewGyik.init();
});