/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	
	config.templates_files = [ 'ckeditor/my_templates/mytemplates.js' ];
        config.templates_replaceContent = false;
	//csak a 'kicseréli a jelenlegi tartalmat' checkbox állapotát állítja be
	//config.templates_replaceContent = false;
//	config.templates_files = [ 'default.js' ];


config.toolbar = [
	{ name: 'alap_formazas', items: [ 'Bold', 'Italic', 'Underline', 'RemoveFormat' ] },
	{ name: 'igazitas', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
	{ name: 'listak', items: [ 'BulletedList', 'NumberedList' ] },
	{ name: 'paragraph',  items: [ 'Outdent', 'Indent', 'Blockquote', 'CreateDiv' ] },
	{ name: 'clipboard', items: [ 'PasteText', 'PasteFromWord' ] },
	{ name: 'visszavonas', items: [ 'Undo', 'Redo' ] },
	{ name: 'linkek', items: [ 'Link', 'Unlink' ] },
	{ name: 'tools', items: [ 'Image', 'Table', 'HorizontalRule' ] },
	{ name: 'formazas', items: [ 'SpecialChar', 'Styles', 'Format' ] },
	{ name: 'document', items: [ 'Source' ] }
	
	];

	
// Nem rak automatikusan <p> tag-et!
	config.autoParagraph = false;


	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;h4;pre';

	// Make dialogs simpler.
	//config.removeDialogTabs = 'image:advanced;link:advanced';
	
	//az editor színét lehet beállítani:
	config.uiColor = '#999999';

	// szélesség és magasság beállítása
	config.height = 300;
	//config.width = 500;

	//config.removePlugins = 'elementspath,save,font';
		
		// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';
  // Load the Hungarian interface.
  config.language = 'hu';
  CKEDITOR.config.allowedContent = true;
//   config.contentsCss = 'public/site_assets/css/style.css';
	config.htmlEncodeOutput = false;
	config.entities = false;
	config.basicEntities = false;
  
  config.baseHref = 'http://localhost/jatszobusz/';
  // protect curly brackets from editing
  CKEDITOR.config.protectedSource.push(/{[\s\S]*?}/g); // protect content in { }
  config.protectedSource.push( /<i[\s\S]*?\>/g ); //allows beginning <i> tag
  config.protectedSource.push( /<\/i[\s\S]*?\>/g ); //allows ending </i> tag
  config.fillEmptyBlocks = false; //allow empty tags
  CKEDITOR.dtd.$removeEmpty['span'] = false;
 
	
	//kcfinder		
//	config.filebrowserBrowseUrl = 'ckfinder/ckfinder.html';
	config.filebrowserBrowseUrl = 'kcfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl = 'kcfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = 'kcfinder/browse.php?type=flash';
	config.filebrowserUploadUrl = 'kcfinder/upload.php?type=files';
	config.filebrowserImageUploadUrl = 'kcfinder/upload.php?type=images';
	config.filebrowserFlashUploadUrl = 'kcfinder/upload.php?type=flash';

	CKEDITOR.config.extraPlugins = 'youtube';
};
