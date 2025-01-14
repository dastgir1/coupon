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
 * TODO describe deletecoupon
 *
 * @package    auth_coupsign
 * @copyright  2024 ghulam.dastgir@paktaleem.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// admin_externalpage_setup('coupmanage');

$url = new moodle_url('/auth/coupsign/deletecoupon.php', []);
$PAGE->set_url($url);
$PAGE->set_context(\core\context\system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('pluginname', 'auth_coupsign'));

echo $OUTPUT->header();

$deleteCoupon = $DB->get_record('auth_coupon',['delete_code' => 1]);

// Process the results
if (!empty($deleteCoupon)) {
    $deleteCoupon->consumed = $DB->count_records_select('auth_coupon_usages', "couponid = $deleteCoupon->id");

    $dcompany=$DB->get_record_sql("SELECT * FROM {company} WHERE id = $deleteCoupon->companyid");
    $deleteCoupon->companyname=$dcompany->name;
    $expirydate = date('m/d/y', $deleteCoupon->expiry_date);
    $deleteCoupon->expiry_date = $expirydate;
    $startdate = date('m/d/y', $deleteCoupon->start_date);
    $deleteCoupon->start_date = $startdate;
    $creationdate = date('m/d/y', $deleteCoupon->creation_date);
    $deleteCoupon->creation_date = $creationdate;
    $deletedate = date('m/d/y', $deleteCoupon->delete_date);
    $deleteCoupon->delete_date = $deletedate;

    $delrecord[]=$deleteCoupon;
} else {
    echo "No delete coupon codes found.";
}

$delrecord = isset($delrecord) && is_array($delrecord) ? $delrecord : [];

echo $OUTPUT->render_from_template('auth_coupsign/deletecoupon', ['deletecoupon'=>array_values($delrecord)]);
echo $OUTPUT->footer();
