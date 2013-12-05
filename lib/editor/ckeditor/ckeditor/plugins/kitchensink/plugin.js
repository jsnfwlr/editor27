/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/**
 * @fileOverview The "sourcearea" plugin. It registers the "source" editing
 *		mode, which displays the raw data being edited in the editor.
 */

(function() {
	CKEDITOR.plugins.add( 'kitchensink', {
		lang: 'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en,en-au,en-ca,en-gb,eo,es,et,eu,fa,fi,fo,fr,fr-ca,gl,gu,he,hi,hr,hu,id,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,th,tr,ug,uk,vi,zh,zh-cn', // %REMOVE_LINE_CORE%
		icons: 'kitchensink', // %REMOVE_LINE_CORE%
		hidpi: true, // %REMOVE_LINE_CORE%
		init: function( editor ) {
			var kitchensink = CKEDITOR.plugins.kitchensink;

			editor.addCommand( 'toggle', kitchensink.commands.toggle );

			if ( editor.ui.addButton ) {
				editor.ui.addButton( 'ToolbarToggle', {
					label: editor.lang.kitchensink.center,
					command: 'toggle',
					toolbar: 'align,20'
				});
			}

			editor.on( 'mode', function() {
				editor.getCommand( 'toggle' ).setState( editor.mode == 'small' ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF );
				//editor.element.$.nextSibling.
				editor.container.addClass('hidebuttons');
			});
		}
	});
})();

CKEDITOR.plugins.kitchensink = {
	commands: {
		toggle: {
			modes: { big:1,small:1 },
			editorFocus: false,
			readOnly: 1,
			exec: function( editor ) {
				var classes = editor.container.getAttribute('class').split(" ");
				if (classes.indexOf('hidebuttons') !== -1) {
					editor.container.removeClass('hidebuttons');
				} else {
					editor.container.addClass('hidebuttons');
				}
			},

			canUndo: false
		}
	}
};
