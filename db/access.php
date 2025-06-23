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
 * Main file to view greetings
 *
 * @package     local_greetings
 * @copyright   2025 Jeremy jerevelasco8@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$capabilities = [
    // Capacidad para postear mensajes.
    'local/greetings:postmessages' => [
    'riskbitmask' => RISK_SPAM,
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'user' => CAP_ALLOW,
    ],
    ],
    // Capacidad para ver mensajes.
    'local/greetings:viewmessages' => [
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
       'user' => CAP_ALLOW,
    ],
    ],
    // Capacidad para eliminar mensajes.
    'local/greetings:deletemessages' => [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
          'manager' => CAP_ALLOW,
          'admin' => CAP_ALLOW,
    ],
    ],
];
