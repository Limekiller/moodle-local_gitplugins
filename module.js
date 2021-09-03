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
 * JavaScript library for the @@newmodule@@ module.
 *
 * @package    mod
 * @subpackage @@newmodule@@
 * @copyright  COPYRIGHTNOTICE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

M.local_gitplugins = M.local_gitplugins || {};

M.local_gitplugins.helper = {
	gY: null,

	 /**
     * @param Y the YUI object
     * @param opts an array of options
     */
    init: function(Y) {
    	M.local_gitplugins.helper.gY = Y;

    	document.querySelector('#id_submitbutton').style.display = 'none';
        document.querySelector('.mform button').addEventListener('click', (e) => {
            document.querySelector('input[name="URL"]').value = e.target.name;
            document.querySelector('#id_submitbutton').click();
        });
    }
};