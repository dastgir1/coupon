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
 * TODO describe file expirecode
 *
 * @package    auth_coupsign
 * @copyright  2024 ghulam.dastgir@paktaleem.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// admin_externalpage_setup('coupmanage');

$url = new moodle_url('/auth/coupsign/expirecode.php');
$PAGE->set_url($url);
$PAGE->set_context(\core\context\system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('pluginname', 'auth_coupsign'));

echo $OUTPUT->header();

// SQL query to fetch coupon codes where the expiry date is less than the current time
$sql = "SELECT * FROM {auth_coupon} WHERE expiry_date < ?";

// Execute the query
$expiredCoupons = $DB->get_records_sql($sql, [time()]);

// Process the results
if (!empty($expiredCoupons)) {
    foreach ($expiredCoupons as $coupon) {

        $coupon->consumed = $DB->count_records_select('auth_coupon_usages', "couponid = $coupon->id");
        $company=$DB->get_record_sql("SELECT * FROM {company} WHERE id = $coupon->companyid");
        $coupon->companyname=$company->name;
        $expirydate = date('m/d/y', $coupon->expiry_date);
        $coupon->expiry_date = $expirydate;
        $startdate = date('m/d/y', $coupon->start_date);
        $coupon->start_date = $startdate;
        $creationdate = date('m/d/y', $coupon->creation_date);
        $coupon->creation_date = $creationdate;

    }
} else {
    echo "No expired coupon codes found.";
}

echo $OUTPUT->render_from_template('auth_coupsign/expirecode', [
    'expcoupon'=>array_values($expiredCoupons)
]);
echo $OUTPUT->footer();
