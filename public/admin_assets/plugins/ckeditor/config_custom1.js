/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	

config.toolbar = [['Bold', 'Italic', '-', 'RemoveFormat', '-', 'BulletedList', 'NumberedList', '-', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo','-','Link','Unlink', 'SpecialChar', 'Styles', 'Format', '-', 'Source' ]];

/*
config.toolbar = [
	{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline' ] },
	{ name: 'clipboard', items: [ 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'document', items: [ 'Source' ] },
	{ name: 'styles', items: [ '' ] }
	];
*/
	
	// Nem rak automatikusan <p> tag-et!
	config.autoParagraph = false;





	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
//config.removeButtons = 'Underline,Subscript,Superscript' ;

	// Se the most common block elements.
//config.format_tags = 'p;h1;h2;h3;h4;pre';

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
	//az editor színét lehet beállítani:
//config.uiColor = '#01379B';

	// szélesség és magasság beállítása
	config.height = 200;
	config.width = 700;

	//config.removePlugins = 'elementspath,save,font';
};
