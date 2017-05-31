/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    config.language = 'hu';
    //config.uiColor = '#AADC6E';

    // szélesség és magasság beállítása
    config.height = 500;
    //config.width = 500;

    //config.templates_files = [ 'public/admin_assets/plugins/ckeditor/my_templates/mytemplates.js' ];
    config.templates_files = ['public/admin_assets/plugins/ckeditor/plugins/templates/my_templates/mytemplates.js'];

    //csak a 'kicseréli a jelenlegi tartalmat' checkbox állapotát állítja be
    config.templates_replaceContent = false;

    // toolbar

    config.toolbar = [
        {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
        {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
        {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
        '/',
        {name: 'insert', items: ['Image', 'Youtube', 'Table', 'HorizontalRule', 'SpecialChar']},
        {name: 'styles', items: ['Styles', 'Format', 'FontSize']},
        {name: 'colors', items: ['TextColor', 'BGColor']},
        {name: 'document', items: ['Source']},
        {name: 'tools', items: ['Templates']},
        {name: 'preview', items: ['Preview']}
    ];

    // Gombok eltávolítása az eszköztárból
    //config.removeButtons = 'Underline,Subscript,Superscript,Templates';
    //config.removeButtons = 'Underline,Subscript,Superscript';

    // Youtube plug-in beépítése
    config.extraPlugins = 'youtube';
    // minden html elem engedélyezése (nem távolít el semmit)
    config.allowedContent = true;
    // Az ékezetes és speciális karaktereket nem alakítja át html entity-vé
    config.htmlEncodeOutput = false;
    config.entities = false;
    config.basicEntities = false;
    // szövegeket, img tageket nem csomagol automatikusan <p> tagekbe
    config.autoParagraph = false;
    // engedélyezi az üres tageket
    config.fillEmptyBlocks = false;
    // nem távolítja el a <i ... </i> közé zárt tartalmat - font ikon megjelenítés
    config.protectedSource.push(/<i[^>]*><\/i>/g);
    // nem jeleníti meg egyáltalán a {} közötti tartalmat pl.: {$slider}
    config.protectedSource.push(/{[\s\S]*?}/g);
    // nem távolítja el az üres span tag-eket
    CKEDITOR.dtd.$removeEmpty['span'] = false;

    config.contentsCss = ['/public/site_assets/css/bootstrap.min.css', '/public/site_assets/css/main-red.css', '/public/admin_assets/plugins/ckeditor/no-background.css'];

    //kcfinder		
    config.filebrowserBrowseUrl = 'public/admin_assets/plugins/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = 'public/admin_assets/plugins/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = 'public/admin_assets/plugins/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl = 'public/admin_assets/plugins/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = 'public/admin_assets/plugins/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = 'public/admin_assets/plugins/kcfinder/upload.php?type=flash';

};