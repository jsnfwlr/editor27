// File picker hacks.
M.editor_ckeditor = M.editor_ckeditor || {};
M.editor_ckeditor.lastfuncnum = -1;

M.editor_ckeditor.open_file_picker = M.editor_ckeditor.open_file_picker || function(editorid, funcnum, lang) {
    Y.use('core_filepicker', function (Y) {
        var mode;

        if (Y.one('.cke_dialog_title').get('text') == 'Image Properties') {
            mode = 'image';
        } else {
            mode = 'link';
        }
        var options = CKEDITOR.instances[editorid].config.params.filepickeroptions[mode];

        options.formcallback = M.editor_ckeditor.file_picker_callback;
        options.editor_target = Y.one(editorid);
        options.zIndex = 20000;

        M.editor_ckeditor.lastfuncnum = funcnum;
        M.core_filepicker.show(Y, options);
    });
};

M.editor_ckeditor.file_picker_callback = M.editor_ckeditor.file_picker_callback || function(params) {
    if (params.url !== '') {
        CKEDITOR.tools.callFunction( M.editor_ckeditor.lastfuncnum, params.url);
        console.log(params);
    }
};
