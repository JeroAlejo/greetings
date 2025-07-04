<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_greetings
 * @category    string
 * @copyright   2025 Jeremy jerevelasco8@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function local_gretings_get_greeting($user) {
    if ($user == null) {
        return get_string('greetinguser', 'local_greetings');
    }

    $country = $user->country;
    switch ($country) {
        case 'ES': // España.
            $langstr = 'greetinguseres';
            break;
        case 'EC': // Ecuador.
            $langstr = 'greetinguserec';
            break;
        case 'AU': // Australia.
            $langstr = 'greetinguserau';
            break;
        case 'FJ': // Fiji.
            $langstr = 'greetinguserfj';
            break;
        case 'NZ': // Nueva Zelanda.
            $langstr = 'greetingusernz';
            break;
        default:
            $langstr = 'greetinguser';
            break;
    }
    return get_string($langstr, 'local_greetings', fullname($user));
}

 /*
 *  Insert a link to index.php on the site front page navigation menu.
 *  @param navigation_node $frontpage Node representing the front page in the navigation tree.
  */

function local_greetings_extend_navigation_frontpage(navigation_node $frontpage) {
    if (isloggedin() && !isguestuser()) {
        $frontpage->add(
            get_string('pluginname', 'local_greetings'),
            new moodle_url('/local/greetings/index.php'),
            navigation_node::TYPE_CUSTOM
        );
    }
}
