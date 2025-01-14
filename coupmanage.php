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
 * Manage coupons.
 *
 * @package    auth_coupsign
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('coupmanage');

// PARAMS.
$page       = optional_param('page', 0, PARAM_INT);
$perpage    = optional_param('perpage', 10, PARAM_INT);

// PAGE.
$returnurl = new moodle_url('/auth/coupsign/coupmanage.php');
$PAGE->set_url($returnurl);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'auth_coupsign'));
$PAGE->set_pagelayout('standard');

$totalcount = $DB->count_records_select('auth_coupon', "id");

$start = $page * $perpage;
if ($start > $totalcount) {
    $page = 0;
    $start = 0;
}

$result = $DB->get_records_sql(
    "SELECT ac.*, co.name
       FROM {auth_coupon} ac
       JOIN {company} co ON ac.companyid = co.id
      WHERE ac.delete_code <> 1
   ORDER BY ac.creation_date DESC
      LIMIT $start, $perpage
");

if(!isset($_POST['submit'])){
    foreach ($result as $re) {
        $user = $DB->get_record('user', array('id' => $re->creatorid));

        $expirydate         = date('m/d/y', $re->expiry_date);
        $startdate          = date('m/d/y', $re->start_date);
        $creationdate       = date('m/d/y', $re->creation_date);

        $re->consumed       = $DB->count_records_select('auth_coupon_usages', "couponid = $re->id");
        $re->creator        = $user->firstname . ' ' . $user->lastname;
        $re->expiry_date    = $expirydate;
        $re->start_date     = $startdate;
        $re->creation_date  = $creationdate;

    }
} else if (isset($_POST['submit']) && isset($_POST['couponcode']) && isset($_POST['companyname'])) {
    $couponcode = required_param('couponcode', PARAM_RAW);
    $companyid  = required_param('companyname', PARAM_INT);

    // Fetch data based on the submitted form values.
    $wherearray = [];

    if ($couponcode !== '') { $wherearray[] = "ac.code LIKE '%:couponcode%'";}
    if ($companyid > 0) { $wherearray[] = "co.id = :companyid";}

    if (!empty($wherearray)) { $where = ' WHERE ' . implode( " AND ", $wherearray) . ' '; } else { $where = ' '; }

    $result = $DB->get_records_sql(
        "SELECT ac.*, co.name
        FROM {auth_coupon} ac
        JOIN {company} co ON ac.companyid = co.id
        $where
     ORDER BY ac.creation_date DESC
        LIMIT $start, $perpage
    ", ['companyid' => $companyid, 'couponcode' => $couponcode]);

    foreach ($result as $re) {
        $re->consumed = $DB->count_records_select('auth_coupon_usages', "couponid = $re->id");

        $user = $DB->get_record('user', array('id' => $re->creatorid));
        $re->creator = $user->firstname . ' ' . $user->lastname;
        $expirydate = date('m/d/y', $re->expiry_date);
        $re->expiry_date = $expirydate;
        $startdate = date('m/d/y', $re->start_date);
        $re->start_date = $startdate;
        $creationdate = date('m/d/y', $re->creation_date);
        $re->creation_date = $creationdate;
    }
}

$strings =  [
    'couponcode'    => get_string('couponcode', 'auth_coupsign'),
    'notes'         => get_string('notes', 'auth_coupsign'),
    'company'       => get_string('company', 'auth_coupsign'),
    'available'     => get_string('available', 'auth_coupsign'),
    'consumed'      => get_string('consumed', 'auth_coupsign'),
    'expiry'        => get_string('expiry', 'auth_coupsign'),
    'startdate'     => get_string('startdate', 'auth_coupsign'),
    'created'       => get_string('created', 'auth_coupsign'),
    'creator'       => get_string('creator', 'auth_coupsign'),
    'addcoupon'     => get_string('addcoupon', 'auth_coupsign'),
    'actions'       => get_string('actions', 'auth_coupsign'),
    'selected'      => get_string('selected', 'auth_coupsign'),
    'expirecoupon'  => get_string('expirecoupon', 'auth_coupsign'),
    'deletecoupon'  => get_string('deletecoupon', 'auth_coupsign'),
];

/* If we want to show  company name those have in current page we use $re->name, and $re->id
and not use 2nd index of array companies
*/
$companiesrecords = $DB->get_records('company', [], '', 'id, name');
$companies = [];
foreach ($companiesrecords as $company) {
    $companies[] = [
        'id'    => $company->id,
        'name'  => $company->name,
    ];
}

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('auth_coupsign/coupmanage', [
    'coupons'   => array_values($result),
    'companies' => array_values($companies),
    'strings'   => $strings,
]);

echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $returnurl);

echo $OUTPUT->footer();
