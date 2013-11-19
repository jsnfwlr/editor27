
console.log('init ckeditor');

M = M || {};
M.editor_ckeditor = M.editor_ckeditor || {};

M.editor_ckeditor.init = M.editor_ckeditor.init || function(Y, elementid, params) {
    // Handle ids with colons.
    elementid = elementid.replace(':', '\\:');
    element = Y.one('#' + elementid);

    CKEDITOR.replace(element.get('name'));
};

console.log('init ckeditor');
