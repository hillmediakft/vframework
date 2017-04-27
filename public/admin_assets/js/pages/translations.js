var Translations = function () {

    var handleEdit = function () {
        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.inputclass = 'form-control';
        $('.xedit').editable({
            url: 'admin/translations/save',
            ajaxOptions: {dataType: 'json'},
            success: function (response, newValue) {
                if (response.success === false) {
                    return response.msg;
                }
            }
        });

        $('[id*=pencil]').click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).closest('td').find('div').editable('toggle');
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleEdit();
        }
    };
}();

jQuery(document).ready(function () {
    Translations.init();
});
