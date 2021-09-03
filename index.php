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
 * Allows the user to specify a git repository to install from
 *
 * @package   local_gitplugins
 * @copyright 2021 Bryce Yoder
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

use local_gitplugins\installer_form;
use local_gitplugins\plugin_installer;
use local_gitplugins\github_result_form;

require_login();
$context = context_system::instance();
require_capability('local/gitplugins:installplugins', $context);

$PAGE->set_context($context);
$PAGE->set_url('/local/gitplugins/index.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_gitplugins'));

echo $OUTPUT->header();
echo html_writer::tag('h2', get_string('pluginname', 'local_gitplugins'));

$form = new installer_form();
if ($form->is_cancelled()) {
    redirect('/admin/plugins.php');
} else if ($data = $form->get_data()) {

    if ($data->query) {
        $github_form = new github_result_form(null, array('query' => $data->query));
        $github_form->display();
        $PAGE->requires->js_init_call('M.local_gitplugins.helper.init');
    } elseif ($data->URL) {
        plugin_installer::install_from_url($data->URL);
    } else {
        $form->display();
    }

} else {
    $github_form = new github_result_form();
    $github_data = $github_form->get_data();
    if ($github_data->URL) {
        plugin_installer::install_from_url($github_data->URL);
    }

    $form->display();
}

echo $OUTPUT->footer();