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
 * Controller class for the plugin install
 *
 * @package   local_gitplugins
 * @copyright 2021 Bryce Yoder
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

class plugin_installer {

    public static function install_from_url($url) {
        global $CFG;

        $plugin_dirs = [
            'mod' => 'mod',
            'antivirus' => 'lib/antivirus',
            'assignsubmission' => 'mod/assign/submission',
            'assignfeedback' => 'mod/assign/feedback',
            'booktool' => 'mod/book/tool',
            'datafield' => 'mod/data/field',
            'datapreset' => 'mod/data/preset',
            'ltisource' => 'mod/lti/source',
            'fileconverter' => 'files/converter',
            'ltiservice' => 'mod/lti/service',
            'quiz' => 'mod/quiz/report',
            'quizaccess' => 'mod/quiz/accessrule',
            'scormreport' => 'mod/scorm/report',
            'workshopform' => 'mod/workshop/form',
            'workshopallocation' => 'mod/workshop/allocation',
            'workshopeval' => 'mod/workshop/eval',
            'block' => 'blocks',
            'qtype' => 'question/type',
            'qbehaviour' => 'question/behaviour',
            'qformat' => 'question/format',
            'filter' => 'filter',
            'editor' => 'lib/editor',
            'atto' => 'lib/editor/atto/plugins',
            'tinymce' => 'lib/editor/tinymce/plugins',
            'enrol' => 'enrol',
            'auth' => 'auth',
            'tool' => 'admin/tool',
            'logstore' => 'admin/tool/log/store',
            'availability' => 'availability/condition',
            'calendartype' => 'calendar/type',
            'message' => 'message/output',
            'format' => 'course/format',
            'dataformat' => 'dataformat',
            'profilefield' => 'user/profile/field',
            'report' => 'report',
            'coursereport' => 'course/report',
            'gradeexport' => 'grade/export',
            'gradeimport' => 'grade/import',
            'gradereport' => 'grade/report',
            'gradingform' => 'grade/grading/form',
            'mnetservice' => 'mnet/service',
            'webservice' => 'webservice',
            'repository' => 'repository',
            'portfolio' => 'portfolio',
            'search' => 'search/engine',
            'media' => 'media/player',
            'plagiarism' => 'plagiarism',
            'cachestore' => 'cache/stores',
            'cachelock' => 'cache/locks',
            'theme' => 'theme',
            'local' => 'local',
            'assignment' => 'mod/assignment/type'
        ];

        $folder = end(explode('/', $url));
        chdir($CFG->tempdir);
        exec("git clone $url $folder");

        // read the whole version.php file into a string. We need to parse out the plugin name
        $file_contents = file_get_contents("$CFG->tempdir/$folder/version.php");
        // get everything between 'plugin->component' and ';'
        $pluginname = explode(';', explode('plugin->component', $file_contents)[1])[0];
        // get everything between the quotes surrounding the plugin name
        $pluginname = explode(substr($pluginname, -1), $pluginname)[1];

        $plugintype = explode('_', $pluginname)[0];
        $plugin_shortname = explode('_', $pluginname)[1];
        $plugin_location = $plugin_dirs[$plugintype];
        $install_path = "$CFG->dirroot/$plugin_location/$plugin_shortname";

        if (!$plugin_location) {
            self::remove_directory("$CFG->tempdir/$folder");
            throw new \coding_exception(
                "Unsupported Plugintype",
                "Couldn't figure out where this plugin goes. Ask the developer to support plugins of type $plugintype!"
            );
        }

        // If the plugin already exists on disk, let's remove it before moving the new version
        if (is_dir($install_path)) {
            self::remove_directory($install_path);
        }

        if (!rename("$CFG->tempdir/$folder", $install_path)) {
            self::remove_directory("$CFG->tempdir/$folder");
            throw new \file_exception(
                "Installation Problem",
                "Couldn't move the plugin folder to the installation directory! Does the web server user have permission?"
            );
        }

        // Redirect to admin page for installation
        redirect('/admin/index.php');
    }

    /**
     * Recursively remove directory with PHP
     * @param string $path The path to the folder to recursively delete
     */
    private static function remove_directory($path) {
        // Get everything, including stuff that starts with '.' (but not '.' or '..')
        $files = glob($path . '/{,.}*[!.]*', GLOB_MARK | GLOB_BRACE);
        foreach ($files as $file) {
            is_dir($file) ? self::remove_directory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }
}