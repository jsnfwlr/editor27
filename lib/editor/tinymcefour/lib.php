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
 * YUI text editor integration.
 *
 * @package    editor_atto
 * @copyright  2013 Damyon Wiese  <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This is the texteditor implementation.
 * @copyright  2013 Damyon Wiese  <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tinymcefour_texteditor extends texteditor {

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

    public function head_setup() {
        global $PAGE, $CFG;
        $rev = -1;
        if (!empty($CFG->cachejs) && !$CFG->debugdeveloper) {
            $pm = get_plugin_manager();
            $plugininfo = $pm->get_plugin_info('editor_tinymcefour');
            // If an upgrade is pending - do not cache any files.
            if ($plugininfo->diskversion != $plugininfo->dbversion) {
                $rev = -1;
            } else {
                $rev = $plugininfo->diskversion;
            }
        }
        $loader = '/lib/editor/tinymcefour/loader.php/' . $rev . '/';
        if ($CFG->debugdeveloper) {
            $PAGE->requires->js(new moodle_url($loader . 'tinymce.dev.js'));
        } else {
            $PAGE->requires->js(new moodle_url($loader . 'tinymce.js'));
        }
    }

    public function escape_selector($id) {
        return str_replace(':', '\\:', $id);
    }

    /**
     * Use this editor for give element.
     *
     * @param string $elementid
     * @param array $options
     * @param null $fpoptions
     */
    public function use_editor($elementid, array $options=null, $fpoptions=null) {
        global $CFG, $PAGE;

        $langrev = -1;
        if (!empty($CFG->cachejs)) {
            $langrev = get_string_manager()->get_revision();
        }
        $language = current_language();
        if ($language == 'en') {
            $language = 'en_hack';
        }
        $config = get_config('editor_tinymcefour');
        if (!isset($config->disabledsubplugins)) {
            $config->disabledsubplugins = '';
        }

        /** Config from MCE 3
        wrap,formatselect,wrap,bold,italic,wrap,bullist,numlist,wrap,link,unlink,wrap,image

undo,redo,wrap,underline,strikethrough,sub,sup,wrap,justifyleft,justifycenter,justifyright,wrap,outdent,indent,wrap,forecolor,backcolor,wrap,ltr,rtl

fontselect,fontsizeselect,wrap,code,search,replace,wrap,nonbreaking,charmap,table,wrap,cleanup,removeformat,pastetext,pasteword,wrap,fullscreen


        Available plugins for MCE 4
        formatselect | bold italic | bullist numlist | link unlink | image media | undo redo | underline strikethrough subscript superscript | alignleft aligncenter alignright | outdent indent | forecolor backcolor | ltr rtl | fontselect fontsizeselect | code | searchreplace | nonbreaking charmap table | removeformat paste pastetext | fullscreen

        link image charmap paste searchreplace code fullscreen media nonbreaking table directionality textcolor
        **/
        $params = array('selector'=>'#' . $this->escape_selector($elementid),
                         'moodle_config' => $config,
                         'language'=>$language,
                         'plugins'=>'compat3x dragmath link image charmap paste searchreplace code fullscreen media nonbreaking table directionality textcolor',
                         'minWidth' => 0,
                         'menubar' => false,
                         'browser_spellcheck' => true,
                         'moodle_plugin_base' => "$CFG->httpswwwroot/lib/editor/tinymcefour/plugins/",

                         'toolbar' => 'formatselect | bold italic | bullist numlist | link unlink | image media | undo redo | underline strikethrough subscript superscript | alignleft aligncenter alignright | outdent indent | forecolor backcolor | ltr rtl | fontselect fontsizeselect | code | searchreplace | nonbreaking charmap table | removeformat paste pastetext | fullscreen'
                        );
        $context = empty($options['context']) ? context_system::instance() : $options['context'];
        editor_tinymcefour_plugin::all_update_init_params($params, $context, $options);

        $PAGE->requires->js_init_call('M.editor_tinymcefour.init_editor', array($elementid, $params), true);
        if ($fpoptions) {
            $PAGE->requires->js_init_call('M.editor_tinymcefour.init_filepicker', array($elementid, $fpoptions), true);
        }
        // Defer loading hack.
        $PAGE->requires->js_init_code('var p = ' . json_encode($params) . ';
                                       p.file_browser_callback = function(target_id, url, type, win) {
                                           return M.editor_tinymcefour.filepicker(target_id, url, type, win);
                                       };
                                       tinymce.init(p);');
    }

}
