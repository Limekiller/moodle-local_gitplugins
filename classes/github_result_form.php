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
 * Form class for the github results
 *
 * @package   local_gitplugins
 * @copyright 2021 Bryce Yoder
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/formslib.php");
use moodleform;

class github_result_form extends moodleform {

    public function definition() {
        $github_data = $this->get_github_results($this->_customdata['query']);

        $mform = $this->_form;
        $mform->addElement('html', '<br />');
        foreach ($github_data->items as $index => $item) {
            $mform->addElement('html', "<h3><a target='_blank' href='$item->html_url'>$item->name</a></h3>");
            $mform->addElement('html', '<h5>by ' . $item->owner->login . '</h5>');
            $mform->addElement('button', $item->html_url, get_string('installplugin', 'local_gitplugins'));
            $mform->addElement('html', '<hr />');
        }

        $mform->addElement('hidden', 'URL', '');
        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        return array();
    }

    private function get_github_results($query) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.github.com/search/repositories?q=$query",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/vnd.github.v4+json',
            'User-Agent: request'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}