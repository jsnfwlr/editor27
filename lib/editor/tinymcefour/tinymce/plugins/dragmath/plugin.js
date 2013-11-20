/**
 * plugin.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint unused:false */
/*global tinymce:true */

/**
 * Example plugin that adds a toolbar button and menu item.
 */
tinymce.PluginManager.add('dragmath', function(editor, url) {
    // Add a button that opens a window
    editor.addButton('dragmath', {
        icon: 'code',
        onclick: function() {
            // Open window
            editor.windowManager.alert('Place holder only');
        }
    });


	// Adds a menu item to the tools menu
	editor.addMenuItem('dragmath', {
		text: 'DragMath',
		context: 'tools',
		onclick: function() {
			// Open window with a specific url
			editor.windowManager.alert('Place holder only');
		}
	});
});