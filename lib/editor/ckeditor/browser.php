<script language="Javascript">
var parts = window.location.search.split('&'),
    i = 0,
    editorid,
    lang,
    funcnum;

for (i = 0; i < parts.length; i++) {
    namevalue = parts[i].split('=');
    if (namevalue[0] == '?CKEditor') {
        editorid = namevalue[1];
    } else if (namevalue[0] == 'CKEditorFuncNum') {
        funcnum = namevalue[1];
    } else if (namevalue[0] == 'langCode') {
        lang = namevalue[1];
    }
}


window.opener.M.editor_ckeditor.open_file_picker(editorid, funcnum, lang);
window.close();
</script>
