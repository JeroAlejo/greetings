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
require_once($CFG->dirroot . '/local/greetings/lib.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');
// Titulo de la pagina - se muestra en la pestaña del navegador y en la pagina principal.
$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));
// Autenticacion del usuario.
require_login();
if (isguestuser()) {
    throw new moodle_exception('noguest');
}
// Tomamos Verificar si se tiene la capacidad de crear, eliminar, enviar y leer mensajes y Crear instacia del formulario de mensaje.
$allowpost = has_capability('local/greetings:postmessages', $context);
$allowview = has_capability('local/greetings:viewmessages', $context);
$deleteanypost = has_capability('local/greetings:deletemessages', $context);
$deletepost = has_capability('local/greetings:deleteownmessages', $context);

$messageform = new \local_greetings\form\message_form();
// Para eliminar mensajes, si el usuario tiene permiso para eliminar mensajes.
$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'del') {

    require_sesskey();
    $id = required_param('id', PARAM_INT);
    // Verificar si el usuario tiene permiso para eliminar mensajes o propios.
    if ($deleteanypost || $deleteanypost) {

        $params = ['id' => $id];

        // Para usuarios eliminar sus propios mensajes.
        if (!$deleteanypost) {
            $params += ['userid' => $USER->id];
        }
         $DB->delete_records('local_greetings_messages', $params);
        // Redireccionar a la pagina principal del plugin para no ver la sesskey.
        // Lo ideal es usar una solicitud POST para eliminar mensajes, pero en este caso se usa GET para simplificar.
        redirect($PAGE->url);
    }
}

// Generacion de Html basico.
echo $OUTPUT->header();
// Mensaje de bienvenida al usuario. personalizado con el nombre del usuario.
// Plantilla.
if (isloggedin()) {
    // C echo '<h2>Greetings, '.fullname($USER) .'</h2>';
    // $usergreeting = 'Greetings, ' . fullname($USER) . '!';
    // $usergreeting = get_string('greetingloggedinuser', 'local_greetings', fullname($USER));
    $usergreeting = local_gretings_get_greeting($USER);
} else {
    // C echo '<h2>Greetings, user</h2>';
    // $usergreeting = 'Greetings, user!';
    $usergreeting = get_string('greetinguser', 'local_greetings');
}

$templedata = ['usergreeting' => $usergreeting];
echo $OUTPUT->render_from_template('local_greetings/greeting_message', $templedata);
// Procesar el formulario de mensaje si el usuario tiene permiso para enviar mensajes.
if ($allowpost) {
    $messageform->display();
}
// Mostrar mensajes guardados en la base de datos.
// $messages = $DB->get_records('local_greetings_messages');
if ($allowview) {
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');

    $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
            FROM {local_greetings_messages} m
            LEFT JOIN {user} u ON u.id = m.userid
            ORDER BY m.timecreated DESC";

    $messages = $DB->get_records_sql($sql);

    // Agregamos un campo para saber si el usuario puede eliminar el mensaje.
    foreach ($messages as $m) {
        $m->candelete = ($deleteanypost || ($deletepost && $m->userid == $USER->id));
    }

    // Plantilla para mostrar los mensajes guardados.
    $templedata = [
        'messages' => array_values($messages),
        // 'candeleteany' => $deleteanypost,
    ];
    echo $OUTPUT->render_from_template('local_greetings/messages', $templedata);
}

if ($data = $messageform->get_data()) {
    // Si el formulario se ha enviado y el usuario tiene permiso para enviar mensajes.
    require_capability('local/greetings:postmessages', $context);
    // Que datos se han enviado.
    // var_dump($data);
    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass();
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;
        // Guardar el mensaje en la base de datos.
        $DB->insert_record('local_greetings_messages', $record);
    }
    // echo $OUTPUT->heading($message, 4);
}

echo $OUTPUT->footer();
