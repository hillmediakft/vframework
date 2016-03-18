/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'hu';
	 //config.uiColor = '#AADC6E';
	
	// szélesség és magasság beállítása
	config.height = 200;
	//config.width = 500;

	//config.templates_files = [ 'public/admin_assets/plugins/ckeditor/my_templates/mytemplates.js' ];
	config.templates_files = [ 'public/admin_assets/plugins/ckeditor/plugins/templates/my_templates/mytemplates.js' ];
        config.templates_replaceContent = false;

		//csak a 'kicseréli a jelenlegi tartalmat' checkbox állapotát állítja be
		//config.templates_replaceContent = false;

	
	
config.toolbar = [
	{ name: 'alap_formazas', items: [ 'Bold', 'Italic' ] },
	{ name: 'igazitas', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
	{ name: 'listak', items: [ 'BulletedList', 'NumberedList' ] },
	{ name: 'visszavonas', items: [ 'Undo', 'Redo' ] },
	{ name: 'linkek', items: [ 'Link', 'Unlink' ] },
	{ name: 'tools', items: [ 'HorizontalRule', 'SpecialChar' ] },
	{ name: 'formazas', items: [ 'Format' ] }
	];	
	

	// Gombok eltávolítása az eszköztárból
	config.removeButtons = 'Underline,Subscript,Superscript';
	// minden html elem engedélyezése (nem távolít el semmit)
	config.allowedContent = true;

	//enterre <br> taget rak be nem <p> taget
	config.enterMode = CKEDITOR.ENTER_BR;

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

	
};