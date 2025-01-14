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
 * Admin settings and defaults.
 *
 * @package auth_coupsign
 * @copyright  2017 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
// require($CFG->dirroot . '/local/iomad/lib/basicsettings.php');

// if ($ADMIN->fulltree) {
//     // Introductory explanation.
//     $settings->add(new admin_setting_heading('auth_coupsign/pluginname', '',
//         new lang_string('auth_coupsigndescription', 'auth_coupsign')));

//     $options = array(
//         new lang_string('no'),
//         new lang_string('yes'),
//     );

//     $settings->add(new admin_setting_configselect('auth_coupsign/recaptcha',
//     new lang_string('auth_coupsignrecaptcha_key', 'auth_coupsign'),
//     new lang_string('auth_coupsignrecaptcha', 'auth_coupsign'), 0, $options));

//     // Display locking / mapping of profile fields.
//     $authplugin = get_auth_plugin('coupsign');
//     display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
//     get_string('auth_fieldlocks_help', 'auth'), false, false);
   
// }

$context =context_user::instance($USER->id);

if (has_capability('auth/coupsign:coupmanage', $context)) {
    $ADMIN->add('root', new admin_externalpage(
        'coupmanage',
        get_string('coupmanage', 'auth_coupsign'),
        new moodle_url('/auth/coupsign/coupmanage.php'),
    ));
}



