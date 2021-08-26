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

require_login();
$context = context_system::instance();
require_capability('local/gitplugins:installplugins', $context);

$PAGE->set_context($context);
$PAGE->set_url('/local/gitplugins/index.php');
$PAGE->set_title('Git Plugin Installer');
$PAGE->set_heading('Install a plugin from a Git repository');
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();

$form = new installer_form();
if ($form->is_cancelled()) {
    redirect('/admin/plugins.php');
} else if ($data = $form->get_data()) {
    plugin_installer::install_from_url($data->URL);
} else {
    $form->display();
}

echo $OUTPUT->footer();