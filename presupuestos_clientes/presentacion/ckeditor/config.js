/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
config.toolbar = 
[
	{ name: 'clipboard', items : [ 'Cut','Copy','PasteText','-','Undo','Redo', 
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
	'/',
	{ name: 'styles', items : [ 'Format','Font','FontSize' ] },
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv', 'Table' ] }
];


// Toolbar groups configuration.
config.toolbarGroups = [
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
	{ name: 'forms' },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
	{ name: 'links' },
	{ name: 'insert' },
	'/',
	{ name: 'styles' },
	{ name: 'colors' },
	{ name: 'tools' },
	{ name: 'others' },
	{ name: 'about' }
];

//config.skin = 'kama';
//config.disableNativeSpellChecker = false;
config.width = 750;
config.height = 350;
config.toolbarCanCollapse = false;
//config.scayt_autoStartup = false;
//config.scayt_sLang = 'es_ES';
config.defaultLanguage = 'es';
//config.docType = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">';

//config.removePlugins = 'resize, elementspath, magicline, autosave, liststyle,tabletools,scayt,menubutton,contextmenu';

config.ProcessHTMLEntities   = true ;
config.IncludeLatinEntities   = true ;
config.IncludeGreekEntities   = true ;

config.pasteFromWordRemoveFontStyles = true;
config.tabIndex = 1;


	config.colorButton_enableMore = true,
  	config.bodyId = 'content',
	config.entities = false,
	config.forceSimpleAmpersand = false,
	config.fontSize_defaultLabel = '12px',
	config.font_defaultLabel = 'Verdana',
	config.emailProtection = 'encode',
	config.contentsLangDirection = 'ltr',
	config.language = 'es',
	config.contentsLanguage = 'es',
	config.toolbarLocation = 'top',
	config.browserContextMenuOnCtrl = false,


//config.autosaveTargetUrl = 'AutoSave.php';
//config.autosave_NotOlderThan = 2;
	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	//config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	//config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	//config.removeDialogTabs = 'image:advanced;link:advanced';
	

 // Define changes to default configuration here:

config.enterMode = CKEDITOR.ENTER_BR;
config.shiftEnterMode = CKEDITOR.ENTER_P;

config.contentsCss = 'fonts.css';
//the next line add the new font to the combobox in CKEditor
//config.font_names = '<Cutsom Font Name>/<YourFontName>;' + config.font_names;
config.font_names = 'GillSans/GillSans;' + config.font_names;	
	
};
