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
 * This script imports MathJax lang strings into Moodle English lang pack.
 * Heavily based on the TinyMCE equivalent script.
 *
 * @package    filter_mathjax
 * @copyright  2013 Damyon Wiese (damyon@moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require __DIR__ . '/../../../config.php';

if (!$CFG->debugdeveloper) {
    die('Only for developers!!!!!');
}

// Upstream strings.
$parsed = filter_mathjax_parse_js_files();

$filename = "$CFG->dirroot/filter/mathjax/lang/en/filter_mathjax.php";
if (!$handle = fopen($filename, 'w')) {
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
 * Strings for component 'filter_mathjax', language 'en'.
 *
 * Note: use filter/mathjax/cli/update_lang_files.php script to import strings from upstream JS lang files.
 *
 * @package    filter_mathjax
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

EOT;

fwrite($handle, $header);

fwrite($handle, "\n\n//== Custom Moodle strings that are not part of upstream MathJax ==\n");
fwrite($handle, filter_mathjax_encode_stringline('filtername', 'MathJax'));

fwrite($handle, "\n\n// == MathJax upstream lang strings from all standard upstream domains ==\n");
fwrite($handle, $parsed);

fclose($handle);

get_string_manager()->reset_caches();
die("\nFinished update of EN lang pack (other langs have to be imported via AMOS)\n\n");



/// ============ Utility functions ========================

function filter_mathjax_encode_stringline($key, $value) {
    $return = "\$string['$key'] = ".var_export($value, true).";";
    return $return."\n";
}

function filter_mathjax_parse_js_files() {
    global $CFG;

    $basedir = "$CFG->dirroot/filter/mathjax/mathjax/localization/en";

    $files = array();
    $strings = '';

    $items = new DirectoryIterator("$basedir");
    foreach ($items as $item) {
        if ($item->isDot() or $item->isDir()) {
            continue;
        }
        $jsfile = $item->getFilename();
        $domain = basename($jsfile, '.js');
        $content = file_get_contents("$basedir/$jsfile");

        // Remove comments.
        $first = strpos($content, 'strings:{') + strlen('strings:{');
        $last = strpos($content, '}', $first);
        $content = substr($content, $first, $last - $first);
        // Quote the array keys (valid JSON).
        $content = str_replace('",', "\";\n", $content) . ';' . "\n";
        $content = preg_replace('/^/m', '$strings[\'' . $domain . ':', $content);
        $content = str_replace(':"', '\'] = "', $content);
        $strings .= $content;
    }

    return $strings;
}
