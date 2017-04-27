var Document_insert = function () {

    // üzenetkonténer
    var ajax_message = $("#ajax_message");

    var ckeditorInit = function () {
        CKEDITOR.replace('description', {customConfig: 'config_minimal1.js'});
    };

    /**
     *	Frissíti a CKeditor-t
     *	(enélkül nem küldi el a ckeditorba írt adatokat)
     */
    var CKupdate = function () {
        for (instance in CKEDITOR.instances)
            CKEDITOR.instances[instance].updateElement();
    };

    /**
     *	Form validátor
     *	(ha minden rendben indítja a send_data() metódust ami ajax-al küldi az adatokat)
     */
    var handleValidation = function () {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#upload_document_form');
        var error1 = $('.alert-danger', form1);
        var error1_span = $('.alert-danger > span', form1);
        //var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: ":disabled", // validate all fields including form hidden input
            rules: {
                title: {
                    required: true
                }
            },
            // az invalidHandler akkor aktiválódik, ha elküldjük a formot és hiba van
            invalidHandler: function (event, validator) { //display error alert on form submit              
                //success1.hide();
                var errors = validator.numberOfInvalids();
                error1_span.html(errors + ' mezőt nem megfelelően töltött ki!');
                error1.show();
                error1.delay(4000).fadeOut('slow');

                //console.log(event);	
                //console.log(validator);	
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group                   
                /*	
                 //menü cím színének megvátoztatása
                 var tab_id = $(element).closest('.tab-pane').attr('id');                  
                 $(".nav-tabs li a[href='#" + tab_id + "']").css('color', '#a94442');
                 //$(".nav-tabs li a[href='#" + tab_id + "']").addClass('has-error');
                 */
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group                   
                /*
                 //menü cím színének megvátoztatása
                 var tab_id = $(element).closest('.tab-pane').attr('id');                  
                 $(".nav-tabs li a[href='#" + tab_id + "']").css('color', '');			
                 */


            },
            success: function (label) {
                //label.closest('.form-group').removeClass('has-error').addClass("has-success"); // set success class to the control group
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                error1.hide();

                //success1.show();
                //success1.delay(4000).fadeOut('slow');

                //adatok elküldése "normál" küldéssel
                //form.submit();

                //form adatok elküldése ajax-al
                // ha a gomb nem disabled
                if ($('#data_upload_ajax').prop('disabled') == false) {
                    insert_data();
                }
                // ha a gomb nem disabled
                if ($('#data_update_ajax').prop('disabled') == false) {
                    update_data();
                }

            }
        });
    };

    /**
     *	Feltöltött dokumentumok törlése gomb
     */
    var delete_doc_trigger = function () {

        $("#dokumentumok li button").on("click", function () {
            var li_element = $(this).closest('li');
            var html_id = li_element.attr('id');
            // kivesszük az id elől az elem_ stringet
            $sort_id = html_id.replace(/doc_/, '');
            //console.log(html_id);	
            console.log('törlendő elem száma: ' + $sort_id);
            $.ajax({
                url: "admin/documents/file_delete",
                type: 'POST',
                dataType: "json",
                data: {
                    id: $('#data_update_ajax').attr('data-id'),
                    sort_id: $sort_id,
                    //type: "docs" //a file_delete php metódusnak mondja meg, hogy képet, vagy doc-ot kell törölni
                },
                beforeSend: function () {
                    App.blockUI({
                        boxed: true,
                        message: 'Feldolgozás...'
                    });
                },
                complete: function () {
                    console.log('complete');
                    App.unblockUI();
                },
                success: function (result) {
                    if (result.status == 'success') {
                        //töröljük a dom-ból ezt a lista elemet
                        li_element.remove();

                        //újraindexeljük a listaelemek id-it, hogy a php egyszerűen feldolgozhassa a változtatást
                        $("#dokumentumok li").each(function (index) {
                            index += 1;
                            $(this).attr('id', 'doc_' + index);
                        });
                    } else {
                        console.log('Dokumentum törlési hiba a szerveren');
                    }
                },
                error: function (result, status, e) {
                    console.log(e);
                }
            });

        });
    };

    /**
     *	File input 5 alapbeállítása
     *	(kartik-bootstrap-fileinput)
     */
    var handleDocUpload_start = function () {
        //console.log('handleDocUpload_start');
        $("#input-5").fileinput({
            uploadUrl: "admin/documents/doc_upload_ajax", // server upload action
            uploadAsync: false,
            //uploadExtraData: {id: $('#data_update_ajax').attr('data-id')},
            showCaption: false,
            showUpload: true,
            language: "hu",
            maxFileCount: 10,
            maxFileSize: 3000,
            allowedFileExtensions: ["jpg", "txt", "pdf", "xps", "csv", "doc", "docx", "xls", "xlsx", "ppt", "pps", "rtf", "ods", "odt", "odp"],
            allowedPreviewTypes: ['image'],
            dropZoneEnabled: false
                    //showPreview: false,
                    //dropZoneTitle: '',
                    //showUploadedThumbs: true
                    //allowedFileTypes: ["image", "video"],
        });

        // alapállapotban ki van kapcsolva ez a file input elem
        $("#input-5").fileinput('disable');
    };

    /**
     *	Dokumentumok feltöltése
     *	(kartik-bootstrap-fileinput)
     */
    var handleDocUpload = function () {
        //console.log('handleDocUpload');
        //frissítjük az input objektumot az uploadExtraData tulajdonsággal
        $("#input-5").fileinput('refresh', {
            uploadExtraData: {id: $('#data_update_ajax').attr('data-id')}
        });

        // input elem aktivizálása
        $("#input-5").fileinput('enable');

        $("#input-5").on('fileloaded', function (event, file, previewId, index, reader) {
            $('.kv-file-upload').hide();
        });

        $("#input-5").on('filebatchuploadsuccess', function (event, data, previewId, index) {

            if (data.response.status == 'success') {
                App.alert({
                    type: 'success',
                    //icon: 'warning',
                    message: data.response.message,
                    container: ajax_message,
                    place: 'append',
                    close: true, // make alert closable
                    reset: false, // close all previouse alerts first
                    //focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 3 // auto close after defined seconds
                });

                // dokumentumok lekérdezése a listás megjelenítéshez
                $.ajax({
                    url: "admin/documents/show_file_list",
                    type: 'POST',
                    //dataType: "json",
                    data: {
                        id: $('#data_update_ajax').attr('data-id'),
                        type: 'docs'
                    },
                    success: function (result) {
                        // html képek lista (a php-tól)
                        $("#dokumentumok").html(result);
                        // törlés gomb aktiválása
                        delete_doc_trigger();
                    }
                });

            } else if (data.response.status == 'error') {
                App.alert({
                    type: 'danger',
                    //icon: 'warning',
                    message: data.response.message,
                    container: ajax_message,
                    place: 'append',
                    close: true, // make alert closable
                    reset: false, // close all previouse alerts first
                    //focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 3 // auto close after defined seconds
                });

            } else {
                App.alert({
                    type: 'danger',
                    //icon: 'warning',
                    message: data.response[0],
                    container: ajax_message,
                    place: 'append',
                    close: true, // make alert closable
                    reset: false, // close all previouse alerts first
                    //focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 3 // auto close after defined seconds
                });
            }
        });

        $("#input-5").on('filebatchuploadcomplete', function (event, files, extra) {
            //törli a file inputot
            $("#input-5").fileinput('clear');
        });
    };


    /**
     *	Berakja az adatbázisba a feltöltött dokumentum adatokat
     *	Visszaadja a last insert id-t
     *	Eltünteti az "insert" gombot, és megjeleníti az "update" gombot
     *	Engedélyezi és elérhetővé teszi a file feltöltést
     */
    var insert_data = function () {

        CKupdate(); //CKeditor tartalmának frissítése

        var $data = $('#upload_document_form').serialize();

        $.ajax({
            url: "admin/documents/insert_update_data_ajax",
            data: $data,
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                App.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
            },
            complete: function () {
                App.unblockUI();
            },
            success: function (result) {

                if (result.status == 'success') {

                    // mégsem gomb eltávolítása
                    $('#button_megsem').remove();

                    // insert gomb inaktív állapotba helyezése
                    $('#data_upload_ajax').prop('disabled', true);
                    $('#data_upload_ajax').hide();

                    //last insert id elhelyezése az update gomb data-id attribútumába
                    $('#data_update_ajax').attr('data-id', result.last_insert_id);

                    // update gomb aktiválása
                    $('#data_update_ajax').prop('disabled', false);
                    $('#data_update_ajax').show();

                    App.alert({
                        type: 'success',
                        //icon: 'warning',
                        message: result.message,
                        container: ajax_message,
                        place: 'append',
                        close: true, // make alert closable
                        reset: false, // close all previouse alerts first
                        //focus: true, // auto scroll to the alert after shown
                        closeInSeconds: 3 // auto close after defined seconds
                    });

                    // file input objektumok kiegészítése és frissítése az ID adattal
                    handleDocUpload();

                    // file feltöltő tabok megjelentése
                    //$('form#upload_files_form .tab-content').show();


                } else {
                    // üzenet doboz megjelenítése
                    var form1 = $('#upload_data_form');
                    var error = $('.alert-danger', form1);
                    var error_span = $('.alert-danger > span', form1);
                    error_span.html('');

                    // result tömb (hibaüzenetek) bejárása
                    $.each(result.error_messages, function (index, value) {
                        //console.log(index + ' : ' + value);
                        error_span.append(value + "<br />");
                    });

                    error.show();
                    error.delay(7000).fadeOut('slow');
                    console.log('Hiba az adatok adatbáziba írásakor!');
                }

            },
            error: function (result, status, e) {
                alert(e);
            }
        });
    };

    /**
     *	UPDATE-eli az adatbázisban az ingatlan adatokat
     *	Eltünteti az "insert" gombot, és megjeleníti az "update" gombot
     *	Engedélyezi és elérhetővé teszi a file feltöltést
     */
    var update_data = function () {

        CKupdate(); //CKeditor tartalmának frissítése

        // last insert id a #data_update_ajax button data-id attribútumában van
        var $id = $('#data_update_ajax').attr('data-id');
        //console.log('ez az utolsó id: ' + $id);
        var $data = $('#upload_document_form').serialize();
        // hozzáfűzzük a form adataihoz a last insert id-t (az update_id kulcsal)
        $data = $data + "&update_id=" + $id;

        $.ajax({
            url: "admin/documents/insert_update_data_ajax",
            data: $data,
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                App.blockUI({
                    boxed: true,
                    message: 'Feldolgozás...'
                });
            },
            complete: function () {
                App.unblockUI();
            },
            success: function (result) {

                if (result.status == 'success') {
                    console.log('adatok módosítva az adatbázisban');

                    var $host = window.location.host;
                    window.location.href = "http://" + $host + "/admin/documents";

                    // üzenet doboz megjelenítése
                    /*
                     var form = $('#upload_data_form');
                     var success = $('.alert-success', form);
                     var success_text = $('.alert-success > span', form);
                     success_text.html('Az adatok módosultak az adatbázisban.');
                     success.show();
                     success.delay(3000).fadeOut('slow');
                     */

                } else {

                    // üzenet doboz megjelenítése
                    var form1 = $('#upload_data_form');
                    var error = $('.alert-danger', form1);
                    var error_span = $('.alert-danger > span', form1);
                    error_span.html('');

                    // result tömb (hibaüzenetek) bejárása
                    $.each(result.error_messages, function (index, value) {
                        //console.log(index + ' : ' + value);
                        error_span.append(value + "<br />");
                    });

                    error.show();
                    error.delay(7000).fadeOut('slow');
                    console.log('Hiba az adatok adatbáziba írásakor!');
                }

            },
            error: function (result, status, e) {
                console.log(e);
            }
        });
    };

    /**
     *	Form adatok INSERT elküldése gomb
     */
    var send_form_trigger = function () {
        $("#data_upload_ajax").on("click", function () {
            $('#upload_document_form').submit();
        });
    };

    /**
     *	Form adatok UPDATE elküldése gomb
     */
    var send_form_trigger_update = function () {
        $("#data_update_ajax").on("click", function () {
            console.log('submit update form');
            $('#upload_document_form').submit();
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            ckeditorInit();
            vframework.hideAlert();
            handleDocUpload_start();
            handleValidation();
            send_form_trigger();
            send_form_trigger_update();
        }
    };

}();

jQuery(document).ready(function () {
    Document_insert.init();
});