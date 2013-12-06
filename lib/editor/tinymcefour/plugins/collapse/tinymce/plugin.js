(function() {
    console.log('init collapse');

    tinymce.create('tinymce.plugins.CollapsePlugin', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            console.log('add command');
            ed.addCommand('mceCollapseToolbars', function() {
                Y.one(ed.editorContainer).toggleClass('collapse');
            });

            ed.addButton('collapse', {
                title : 'Collapse toolbars', // TODO _ Load this lang string
                cmd : 'mceCollapseToolbars',
                image : url + '/img/toolbars.png'
            });

            tinymce.DOM.loadCSS(url + '/css/styles.css');

            ed.on('init', function(args) {
                Y.one(ed.editorContainer).toggleClass('collapse');
            });
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Moodle toolbar collapse plugin',
                author : 'Damyon Wiese',
                version : "1.0"
            };
        }
    });

    // Register plugin.
    tinymce.PluginManager.add('collapse', tinymce.plugins.CollapsePlugin);
})();
