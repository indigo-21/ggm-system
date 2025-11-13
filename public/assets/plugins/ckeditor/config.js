/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	config.scayt_autoStartup = true;

	// config.extraPlugins = 'font';



    config.font_names= 'Arial;Arial Black;Arial Narrow;Centaur;Century Gothic;Comic Sans MS;Courier New;Garamond;Georgia;GREENREGULAR;Shalom Old Style;Impact;Tahoma;Tempus Sans ITC;Trebuchet MS;Times New Roman;Verdana;RmzFrank;SL Star of David';
    //Wingdings;

    config.height = 500;
   
    config.width = 752;

    config.fontSize_sizes = '8/8pt;9/9pt;10/10pt;11/11pt;12/12pt;14/14pt;16/16pt;18/18pt;20/20pt;22/22pt;24/24pt;26/26pt;28/28pt;36/36pt;48/48pt;72/72pt';
    //config.fontSize_sizes = 'Small/10pt;Medium/12pt;Large/14pt';
  
    config.font_defaultLabel = 'Times New Roman';    

    config.coreStyles_italic = { element : 'i', overrides : 'em' };

    config.enterMode = CKEDITOR.ENTER_BR;
  
    config.font_style =
        {
            element     : 'font',
            styles      : { 'font-family' : '#(family)' },
            overrides   : [ { element : 'font', attributes : { 'face' : null } } ]
        };

    config.fontSize_style =
        {
            element     : 'font',
            styles      : { 'font-size' : '#(size)' },
            overrides   : [ { element : 'font', attributes : { 'size' : null } } ]
        };


	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};

CKEDITOR.on("instanceReady", function(event) {
    event.editor.on("beforeCommandExec", function(event) {
        // Show the paste dialog for the paste buttons and right-click paste
        if (event.data.name == "paste") {
            event.editor._.forcePasteDialog = true;
        }
        // Don't show the paste dialog for Ctrl+Shift+V
        if (event.data.name == "pastetext" && event.data.commandData.from == "keystrokeHandler") {
            event.cancel();
        }
    })
});
