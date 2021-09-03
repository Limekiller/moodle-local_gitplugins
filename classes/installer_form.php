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

namespace local_gitplugins;

/**
 * Form class for the installer
 *
 * @package   local_gitplugins
 * @copyright 2021 Bryce Yoder
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/formslib.php");
use moodleform;

class installer_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('html', '<br />');
        $mform->addElement('html', '<h4>Search GitHub for Moodle plugins</h4>');
        $mform->addElement('text', 'query', get_string('searchquery', 'local_gitplugins'));
        $mform->setType('query', PARAM_NOTAGS);
        $this->add_action_buttons(false, 'Search');
        // $mform->addRule('URL', get_string('urlrequired', 'local_gitplugins'), 'required');

        $mform->addElement('html', '<hr />');

        $mform->addElement('html', '<h4>Install directly from URL</h4>');
        $mform->addElement('text', 'URL', get_string('repourl', 'local_gitplugins'));
        $mform->setType('URL', PARAM_NOTAGS);
        // $mform->addRule('URL', get_string('urlrequired', 'local_gitplugins'), 'required');
        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }
}