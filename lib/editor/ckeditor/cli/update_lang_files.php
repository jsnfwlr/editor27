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
 * This script imports CKEditor lang strings into Moodle English lang pack.
 *
 * @package    editor_ckeditor
 * @copyright  2009 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__.'/../../../../config.php');

if (!$CFG->debugdeveloper) {
    die('Only for developers!!!!!');
}

$newstrings = editor_ckeditor_import_strings();
ksort($newstrings);
$oldstrings = editor_ckeditor_load_strings();

$newstrings = array_diff_key($newstrings, $oldstrings);

if ($newstrings) {
    echo "Found new lang strings:\n";
    echo var_export($newstrings);
}

// Keep the old non-imported strings.
foreach ($oldstrings as $key => $string) {
    if (strpos($key, ':') === false) {
        $langstrings[$key] = $string;
    }
}
// Replace all strings from upstream.
foreach ($newstrings as $key => $value) {
    $langstrings[$key] = $value;
}

if (!$handle = fopen("$CFG->dirroot/lib/editor/ckeditor/lang/en/editor_ckeditor.php", 'w')) {
     echo "Cannot write to $filename !!";
     exit(1);
}

$header = <<<EOT
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
 * Strings for component 'editor_ckeditor', language 'en'.
 *
 * Note: use editor/ckeditor/cli/update_lang_files.php script to import strings from upstream JS lang files.
 *
 * @package    editor_ckeditor
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

EOT;

fwrite($handle, $header);

foreach ($langstrings as $key=>$value) {
    fwrite($handle, editor_ckeditor_encode_stringline($key, $value));
}

get_string_manager()->reset_caches();
die("\nFinished update of EN lang pack (other langs have to be imported via AMOS)\n\n");

/// ============ Utility functions ========================

function editor_ckeditor_load_strings() {
    $sm = get_string_manager();
    return $sm->load_component_strings('editor_ckeditor', 'en', true, true);
}

function editor_ckeditor_encode_stringline($key, $value, $commentedout=false) {
    $return = "\$string['$key'] = ".var_export($value, true).";";
    if ($commentedout) {
        $return = "/* $return */";
    }
    return $return."\n";
}


function editor_ckeditor_import_strings() {
    global $CFG;

    $mappings = array();
    $langfiles = array('ckeditor'=>'lang/en.js');

    /*
    foreach (scandir($CFG->dirroot . '/lib/editor/ckeditor/ckeditor/plugins') as $plugin) {
        if ($plugin != '..') {
            $langfile = $CFG->dirroot . '/lib/editor/ckeditor/ckeditor/plugins/' . $plugin . '/lang/en.js';
            if (file_exists($langfile)) {
                $langfiles[$plugin] = 'plugins/' . $plugin . '/lang/en.js';
            }
        }
    }*/

    foreach ($langfiles as $plugin => $langfile) {
        $js = file_get_contents($CFG->dirroot . '/lib/editor/ckeditor/ckeditor/' . $langfile);

        $js = substr($js, strpos($js, '{'));
        $js = substr($js, 0, strrpos($js, '}') + 1);
        $js = str_replace(array("\n","\r"),"",$js);
        $js = str_replace(": ",":",$js);
        $js = str_replace("\\\'","AAAA",$js);
        $js = preg_replace("/(\\w)'(\\w)/","\\1AAAA\\2",$js);
        $js = str_replace("'","\"",$js);
        $js = str_replace("AAAA","\\'",$js);
        $js = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$js);

        $js = str_replace("\\'", "&apos;", $js);

        $strings = (array)json_decode($js, false, 100);
        if (!$strings) {
            die('Could not parse json: ' . $js);
        }
        $flat = editor_ckeditor_flatten_strings($strings, $plugin);
        $mappings = array_merge($mappings, $flat);
    }
    return $mappings;
}

function editor_ckeditor_flatten_strings($arrayorstdclass, $prefix) {
    $a = (array) $arrayorstdclass;
    $result = array();
    foreach ($a as $b => $c) {
        if (is_string($c)) {
            $result[$prefix . ':' . $b] = $c;
        } else {
            $d = editor_ckeditor_flatten_strings($c, $prefix . ':' . $b);
            $result = array_merge($result, $d);
        }
    }
    return $result;
}
