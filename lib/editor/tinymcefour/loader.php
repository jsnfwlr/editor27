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
 * Loader for resource files within TinyMCE plugins.
 *
 * This loader handles requests which have the plugin version number in. These
 * requests are set to never expire from cache, to improve performance. Only
 * files within the 'tinymcefour' folder of the plugin will be served.
 *
 * Note there are no access checks in this script - you do not have to be
 * logged in to retrieve the plugin resource files.
 *
 * @package editor_tinymcefour
 * @copyright 2012 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_MOODLE_COOKIES', true);
require_once('../../../config.php');
require_once($CFG->dirroot . '/lib/filelib.php');
require_once($CFG->dirroot . '/lib/jslib.php');


// Safely get slash params (cleaned using PARAM_PATH, without /../).
$path = get_file_argument();

// Param must be of the form [plugin]/[version]/[path] where path is a relative
// path inside the plugin tinymce folder.
$matches = array();
if (!preg_match('~^/((?:[0-9.]+)|-1)(/.*)$~', $path, $matches)) {
    print_error('filenotfound');
}
list($junk, $version, $innerpath) = $matches;

$tinymceplugin = 'none';
$tinymceskin = 'none';
$pluginpath = '';
$skinpath = '';
if (strpos($innerpath, '/plugins/') === 0) {
    list($ignore, $ignoremore, $tinymceplugin, $pluginpath) = explode('/', $innerpath, 4);
} else if (strpos($innerpath, '/skins/') === 0) {
    list($ignore, $ignoremore, $tinymceskin, $skinpath) = explode('/', $innerpath, 4);
} else if (strpos($innerpath, '/langs/') === 0) {
    $lang = basename($innerpath, '.js');

    $loadlang = $lang;
    if ($lang == 'en_hack') {
        $loadlang = 'en';
    }

    $rev = $version;
    if (!get_string_manager()->translation_exists($loadlang, false)) {
        $loadlang = 'en';
        $rev = -1; // Do not cache missing langs.
    }

    $candidate = "$CFG->localcachedir/editor_tinymcefour/$rev/$lang.js";
    $etag = sha1("$lang/$rev");

    if ($rev > -1 and file_exists($candidate)) {
        if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) || !empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            // We do not actually need to verify the etag value because our files
            // never change in cache because we increment the rev parameter.
            js_send_unmodified(filemtime($candidate), $etag);
        }
        js_send_cached($candidate, $etag, 'strings.php');
    }

    $strings = get_string_manager()->load_component_strings('editor_tinymcefour', $loadlang);
    // Add subplugin strings.
    foreach (core_component::get_plugin_list('tinymcefour') as $component => $ignored) {
        $componentstrings = get_string_manager()->load_component_strings(
                'tinymcefour_' . $component, $loadlang);
        foreach ($componentstrings as $key => $value) {
            if (!isset($strings[$key])) {
                $strings[$key] = $value;
            }
        }
    }
    $mappings = json_decode(file_get_contents(__DIR__ . '/langstrings.json'));

    // Process the $strings to match expected tinymce lang array structure.
    $result = array();

    foreach ($mappings as $key=>$value) {
        $result[$value] = $strings[$key];
        // Oh so nasty tinymce3 compat hack!
        $result[$lang . '.' . $value] = $strings[$key];
        unset($strings[$key]);
    }
    foreach ($strings as $key=>$value) {
        // Hack1.
        $key = str_replace(':', '.', $key);
        $result[$key] = $value;
        // Oh nasty tinymce3 compat hack!
        $result[$lang . '.' . $key] = $value;
    }

    $output = 'tinymce.EditorManager.addI18n(\''.$lang.'\', '.json_encode($result).');';

    if ($rev > -1) {
        js_write_cache_file_content($candidate, $output);
        // Verify nothing failed in cache file creation.
        clearstatcache();
        if (file_exists($candidate)) {
            js_send_cached($candidate, $etag, $lang . '.js');
        }
    }

    js_send_uncached($output, $lang . '.js');
    die();
}

// Note that version number is totally ignored, user can specify anything,
// except for the difference between '-1' and anything else.

// Check the file exists.
$pluginfolder = $CFG->dirroot . '/lib/editor/tinymcefour/plugins/' . $tinymceplugin;
$file = $pluginfolder . '/tinymce/' .$pluginpath;
if ($tinymceplugin == 'none' || !file_exists($file)) {
    $skinfolder = $CFG->dirroot . '/lib/editor/tinymcefour/skins/' . $tinymceskin;
    $file = $skinfolder . '/' .$skinpath;
    if ($tinymceskin == 'none' || !file_exists($file)) {
        $pluginfolder = $CFG->dirroot . '/lib/editor/tinymcefour/tinymce';
        $file = $pluginfolder . '/' . $innerpath;
        if (!file_exists($file)) {
            print_error('filenotfound');
        }
    }
}

// We don't actually care what the version number is but there is a special
// case for '-1' which means, set the files to not be cached.
$allowcache = ($version !== '-1');
if ($allowcache) {
    // Set it to expire a year later. Note that this means we should never get
    // If-Modified-Since requests so there is no need to handle them specially.
    header('Expires: ' . date('r', time() + 365 * 24 * 3600));
    header('Cache-Control: max-age=' . 365 * 24 * 3600);
    // Pragma is set to no-cache by default so must be overridden.
    header('Pragma:');
}

// Get the right MIME type.
$mimetype = mimeinfo('type', $file);

// For JS files, these can be minified and stored in cache.
if ($mimetype === 'application/x-javascript' && $allowcache) {
    // Flatten filename and include cache location.
    $cache = $CFG->localcachedir . '/editor_tinymcefour/pluginjs';
    $cachefile = $cache . '/' . $tinymceplugin . '/' . $version . '/' .
            str_replace('/', '_', $innerpath);

    // If it doesn't exist, minify it and save to that location.
    if (!file_exists($cachefile)) {
        $content = core_minify::js_files(array($file));
        js_write_cache_file_content($cachefile, $content);
    }

    $file = $cachefile;
} else if ($mimetype === 'text/html') {
    header('X-UA-Compatible: IE=edge');
}

// Serve file.
header('Content-Length: ' . filesize($file));
header('Content-Type: ' . $mimetype);
readfile($file);
