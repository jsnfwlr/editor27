<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * CKEditor Moodle integration
 *
 * @package    editor_ckeditor
 * @copyright  2013 Damyon Wiese  <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This is the texteditor implementation.
 * @copyright  2013 Damyon Wiese  <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ckeditor_texteditor extends texteditor {

    /**
     * Is the current browser supported by this editor?
     *
     * Of course!
     * @return bool
     */
    public function supported_by_browser() {
        return true;
    }

    /**
     * Returns array of supported text formats.
     * @return array
     */
    public function get_supported_formats() {
        // FORMAT_MOODLE is not supported here, sorry.
        return array(FORMAT_HTML => FORMAT_HTML);
    }

    /**
     * Returns text format preferred by this editor.
     * @return int
     */
    public function get_preferred_format() {
        return FORMAT_HTML;
    }

    /**
     * Does this editor support picking from repositories?
     * @return bool
     */
    public function supports_repositories() {
        return true;
    }

    /**
     * Sets up head code if necessary.
     */
    public function include_js() {
        global $PAGE, $CFG;
        static $included = false;

        if ($included) {
            return;
        }
        $included = true;

        $rev = -1;
        if (!empty($CFG->cachejs) && !$CFG->debugdeveloper) {
            $pm = core_plugin_manager::instance();
            $plugininfo = $pm->get_plugin_info('editor_tinymcefour');
            // If an upgrade is pending - do not cache any files.
            if ($plugininfo->versiondisk != $plugininfo->versiondb) {
                $rev = -1;
            } else {
                $rev = $plugininfo->versiondisk;
            }
        }

        $loader = '/lib/editor/ckeditor/loader.php/' . $rev . '/';
        $jsinit = 'CKEDITOR_BASEPATH = M.cfg.wwwroot + "' . $loader . '"';
        $PAGE->requires->js_init_code($jsinit);
        $PAGE->requires->js(new moodle_url($loader . 'moodle.js'));
        $PAGE->requires->js(new moodle_url($loader . 'ckeditor.js'));
    }

    /**
     * Return an array of ckeditor config options.
     *
     * @return array - The array of options.
     */
    public function get_ckeditor_config($params) {
        global $CFG;
        $mathjaxurl = $CFG->wwwroot . '/filter/mathjax/mathjax/MathJax.js?config=../../moodle,Safe,../../language.php?';

        $toolbarconfig = array(array('name'=>'toggle', 'items'=>array('ToolbarToggle')),
                               array('name'=>'styles', 'items'=>array('Styles')),
                               array('name'=>'basicformat', 'items'=>array('Bold', 'Italic')),
                               array('name'=>'lists', 'items'=>array('BulletedList', 'NumberedList')),
                               array('name'=>'links', 'items'=>array('Link', 'Unlink')),
                               array('name'=>'media', 'items'=>array('Image')),
                               array('name'=>'edits', 'items'=>array('Undo', 'Redo')),
                               array('name'=>'extraformat', 'items'=>array('Underline', 'Strike', 'Subscript', 'Superscript')),
                               array('name'=>'alignment', 'items'=>array('JustifyLeft', 'JustifyCenter', 'JustifyRight')),
                               array('name'=>'indent', 'items'=>array('Indent', 'Outdent')),
                               array('name'=>'color', 'items'=>array('TextColor', 'BGColor')),
                               array('name'=>'input', 'items'=>array('BidiLtr', 'BidiRtl')),
                               array('name'=>'fonts', 'items'=>array('Font', 'FontSize')),
                               array('name'=>'document', 'items'=>array('Source', 'Find', 'Replace')),
                               array('name'=>'extras', 'items'=>array('SpecialChar', 'Mathjax', 'Table')),
                               array('name'=>'clipboard', 'items'=>array('PasteText', 'PasteFromWord')),
                              );

        $options = array('customConfig'=>'', // Prevent an extra js file load.
                     'language' => $params['language'], // Pass the current language.
                     'contentsLanguage' => $params['language'], // Pass the current language.
                     'plugins' => 'kitchensink,dialogui,dialog,about,a11yhelp,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,undo,wsc',
                     'extraPlugins' => 'mathjax',
                     'contentsCSS' => $params['content_css'], // Apply theme styles to the content.
                     'contentsLangDirection' => $params['directionality'], // Set content direction.
                     'pasteFromWordPromptCleanup' => true, // Warn before accepting gifts from word.
                     'removePlugins' => 'about', // Hide about button.
                     'disableNativeSpellChecker' => false,
                     'mathJaxLib' => $mathjaxurl,
                     'skin' => 'moodle',
                     'toolbarCanCollapse' => false, // Ohh - nice, kinda.
                     'toolbar' => $toolbarconfig,
        );
        if (isset($params['filepickeroptions'])) {
            $options['filebrowserBrowseUrl'] = $CFG->wwwroot . '/lib/editor/ckeditor/browser.php';
        }
        return $options;
    }

    /**
     * Use this editor for give element.
     *
     * @param string $elementid
     * @param array $options
     * @param null $fpoptions
     */
    public function use_editor($elementid, array $options=null, $fpoptions=null) {
        global $PAGE;

        $this->include_js();

        $params = $this->get_init_params($elementid, $options, $fpoptions);
        $config = $this->get_ckeditor_config($params);
        $config['params'] = $params;
        $jscode = 'CKEDITOR.replace("' . $elementid . '", ' . json_encode($config) . ')';

        $PAGE->requires->js_init_code($jscode);
    }

    /**
     * Create a params array to init the editor.
     *
     * @param string $elementid
     * @param array $options
     * @param array $fpoptions
     */
    protected function get_init_params($elementid, array $options=null, array $fpoptions=null) {
        global $PAGE;

        $directionality = get_string('thisdirection', 'langconfig');
        $strtime        = get_string('strftimetime');
        $strdate        = get_string('strftimedaydate');
        $lang           = current_language();
        $contentcss     = $PAGE->theme->editor_css_url()->out(false);

        $params = array(
            'elementid' => $elementid,
            'content_css' => $contentcss,
            'language' => $lang,
            'directionality' => $directionality,
            'filepickeroptions' => array()
        );
        if ($fpoptions) {
            $params['filepickeroptions'] = $fpoptions;
        }
        return $params;
    }
}
