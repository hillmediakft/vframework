/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'hu';
	// config.uiColor = '#AADC6E';
	
	// szélesség és magasság beállítása
	config.height = 400;
	//config.width = 500;
	
	// Gombok eltávolítása az eszköztárból
	config.removeButtons = 'Underline,Subscript,Superscript,Templates';
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
};