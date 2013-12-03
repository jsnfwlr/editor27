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
 * This script imports TinyMCE lang strings into Moodle English lang pack.
 *
 * @package    editor_tinymcefour
 * @copyright  2009 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__.'/../../../../config.php');
require_once("$CFG->libdir/clilib.php");

if (!$CFG->debugdeveloper) {
    die('Only for developers!!!!!');
}

// Now get cli options.
list($options, $unrecognized) = cli_get_params(array('langfile'=>'', 'help'=>false), array('l'=>'langfile', 'h'=>'help'));

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help'] || empty($options['langfile'])) {
    $help =
        "Update tinymcefour lang string files.

Options:
-l, --langfile        Up to date tinyMCE language file (any language). Download from http://www.tinymce.com/i18n/
-h, --help            Print out this help

Example:
\$ /usr/bin/php lib/editor/tinymcefour/cli/update_lang_strings.php --langfile en_GB.js
";

    echo $help;
    die;
}

if (!file_exists($options['langfile'])) {
    die('Language file not specified or does not exist: ' . $options['langfile']);
}
$langfile = $options['langfile'];

$oldmappings = editor_tinymcefour_load_mappings();
$newmappings = editor_tinymcefour_generate_mappings($langfile);
ksort($oldmappings);
ksort($newmappings);

$newstrings = array_diff_key($newmappings, $oldmappings);
$removedstrings = array_diff_key($oldmappings, $newmappings);

if ($newstrings) {
    echo "Found new lang strings:\n";
    echo var_export($newstrings);
}
if ($removedstrings) {
    echo "Found removed lang strings:\n";
    echo var_export($removedstrings);
}
if (!$newstrings && !$removedstrings) {
    die("No changes required.\n");
}

if (!$handle = fopen("$CFG->dirroot/lib/editor/tinymcefour/langstrings.json", 'w')) {
     echo "Cannot write to $filename !!";
     exit(1);
}

fwrite($handle, json_encode($newmappings));
fclose($handle);

$langstrings = editor_tinymcefour_get_all_strings();

// Remove strings that were removed from upstream.
foreach ($removedstrings as $key => $value) {
    unset($langstrings[$key]);
}
// Add new strings that were added upstream.
foreach ($newstrings as $key => $value) {
    $langstrings[$key] = $value;
}

if (!$handle = fopen("$CFG->dirroot/lib/editor/tinymcefour/lang/en/editor_tinymcefour.php", 'w')) {
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
 * Strings for component 'editor_tinymcefour', language 'en'.
 *
 * Note: use editor/tinymcefour/cli/update_lang_files.php script to import strings from upstream JS lang files.
 *
 * @package    editor_tinymce
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

EOT;

fwrite($handle, $header);

foreach ($langstrings as $key=>$value) {
    fwrite($handle, editor_tinymcefour_encode_stringline($key, $value));
}

get_string_manager()->reset_caches();
die("\nFinished update of EN lang pack (other langs have to be imported via AMOS)\n\n");

/// ============ Utility functions ========================

function editor_tinymcefour_get_all_strings() {
    $sm = get_string_manager();
    return $sm->load_component_strings('editor_tinymcefour', 'en', true, true);
}

function editor_tinymcefour_encode_stringline($key, $value, $commentedout=false) {
    $return = "\$string['$key'] = ".var_export($value, true).";";
    if ($commentedout) {
        $return = "/* $return */";
    }
    return $return."\n";
}

function editor_tinymcefour_load_mappings() {
    global $CFG;

    $basedir = "$CFG->libdir/editor/tinymcefour";

    // Core upstream pack.
    $mappingfile = "$basedir/langstrings.json";
    if (!file_exists($mappingfile)) {
        return array();
    }
    $content = file_get_contents($mappingfile);
    $strings = (array) json_decode($content);

    return $strings;
}

function editor_tinymcefour_generate_mappings($langfile) {
    $js = file_get_contents($langfile);

    $js = preg_replace("/.*{/", "", $js);
    $js = preg_replace("/}.*/", "", $js);
    $js = preg_replace("/:.*/m", "", $js);
    $js = str_replace("\"", "", $js);

    $strings = explode("\n", $js);
    $mappings = array();

    foreach ($strings as $langstring) {
        $index = 1;
        $key = preg_replace("/[^a-z]+/", "", shorten_text(strtolower($langstring), 30, false, ''));
        $uniqkey = $key;
        while (!empty($mappings[$uniqkey])) {
            $uniqkey = $key . '_' . $index++;
        }
        if (!empty($uniqkey)) {
            $mappings[$uniqkey] = $langstring;
        }
    }

    return $mappings;
}
