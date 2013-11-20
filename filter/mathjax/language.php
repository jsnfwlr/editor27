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
 * On-the-fly conversion of Moodle lang strings to MathJax expected JS format.
 *
 * @package    filter_mathjax
 * @copyright  2013 Damyon Wiese (damyon@moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_MOODLE_COOKIES', true);
define('NO_UPGRADE_CHECK', true);

require('../../config.php');
require_once("$CFG->dirroot/lib/jslib.php");
require_once("$CFG->dirroot/lib/configonlylib.php");

$lang  = optional_param('lang', 'en', PARAM_RAW);
$lang  = preg_replace('/\/.*/', '', $lang);
$lang  = clean_param($lang, PARAM_SAFEDIR);
$rev   = optional_param('rev', -1, PARAM_INT);

$requestedlang = $lang;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/filter/mathjax/language.php');

if (!get_string_manager()->translation_exists($lang, false)) {
    $lang = 'en';
    $rev = -1; // Do not cache missing langs.
}

$candidate = "$CFG->localcachedir/filter_mathjax/$rev/$lang.js";
$etag = sha1("$lang/$rev");

if ($rev > -1 and file_exists($candidate)) {
    if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) || !empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        // we do not actually need to verify the etag value because our files
        // never change in cache because we increment the rev parameter
        js_send_unmodified(filemtime($candidate), $etag);
    }
    js_send_cached($candidate, $etag, 'language.php');
}

$string = get_string_manager()->load_component_strings('filter_mathjax', $lang);

// Process the $strings to match expected lang array structure.
$output = '';
$output .= 'MathJax.Localization.directory = "' . $CFG->wwwroot . '/filter/mathjax/language.php?lang="' . "\n";
$config = array('menuTitle' => get_string_manager()->get_string('thislanguage', 'langconfig', null, $lang));
$result = array();

foreach ($string as $key=>$value) {
    $parts = explode(':', $key);
    if (count($parts) != 2) {
        // Ignore non-imported Mathjax strings.
        continue;
    }
    $domain = $parts[0];
    $string = $parts[1];

    if (!isset($result[$domain])) {
        $result[$domain] = array('isLoaded' => true, 'strings' => array());
    }
    $result[$domain]['strings'][$string] = $value;
}
$config['domains'] = $result;
$output .= 'MathJax.Localization.addTranslation("' . $requestedlang . '", null, ' . json_encode($config) . ');';

$output .= 'MathJax.Ajax.loadComplete("[MathJax]/config/../../language.php?.js");';
if ($rev > -1) {
    js_write_cache_file_content($candidate, $output);
    // verify nothing failed in cache file creation
    clearstatcache();
    if (file_exists($candidate)) {
        js_send_cached($candidate, $etag, 'language.php');
    }
}

js_send_uncached($output, 'language.php');
