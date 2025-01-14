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
 * TODO describe file delete
 *
 * @package    auth_coupsign
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$url = new moodle_url('/auth/coupsign/delete.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading($SITE->fullname);

echo $OUTPUT->header();

// Get id for urlbar and  delete id related data from database.
if(isset($_POST['del_id'])){

    $couponid=$_POST['del_id'];
    $data = new stdClass();
    $data->id =  $couponid;
    $data->delete_code = 1; // Set delete_code to 1
    $data->delete_date = time(); // Set delete_date to current time
    $delrecord=$DB->update_record('auth_coupon', $data);

    // Update the record in the auth_coupon table
    if($delrecord) {
        echo 1;
    } else {
        echo 0;
    }
}
echo $OUTPUT->footer();
