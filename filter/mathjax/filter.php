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
 * This filter provides automatic support for MathJax
 *
 * @package    filter_mathjax
 * @copyright  2013 Damyon Wiese (damyon@moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Mathjax filtering
 */
class filter_mathjax extends moodle_text_filter {

    public function setup($page, $context) {
        global $CFG;
        // This only requires execution once per request.
        static $jsinitialised = false;

        if (empty($jsinitialised)) {
            $page->requires->js(
                    '/filter/mathjax/mathjax/MathJax.js?config=../../moodle,../../language.php?');
            $jsinitialised = true;
        }
    }

    public function filter($text, array $options = array()) {
        return $text;
    }
}
