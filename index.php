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
 * Main file to view greetings
 *
 * @package     local_greetings
 * @copyright   2025 Jeremy jerevelasco8@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Configuracion de contexto, URL y diseño de página.
require_once('' . '../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');
// Titulo de la pagina - se muestra en la pestaña del navegador y en la pagina principal.
$PAGE ->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

// Generacion de Html basico.
echo $OUTPUT->header();
// Mensaje de bienvenida al usuario. personalizado con el nombre del usuario.
// Plantilla.
if (isloggedin()) {
   // C echo '<h2>Greetings, '.fullname($USER) .'</h2>';
   $usergreeting = 'Greetings, ' . fullname($USER) . '!';
} else {
   // C echo '<h2>Greetings, user</h2>';
    $usergreeting = 'Greetings, user!';
}

$templedata = ['usergreeting' => $usergreeting];
echo $OUTPUT->render_from_template('local_greetings/greeting_message', $templedata);
echo $OUTPUT -> footer();
