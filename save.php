<?php
require('../../config.php');

$url = new moodle_url('/auth/coupsign/save.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading($SITE->fullname);
echo $OUTPUT->header();
$couponid = required_param('id', PARAM_INT);
if(isset($_POST['submit'])){
    $obj=new stdClass();
    $obj->id=$couponid;
    $obj->notes= $_POST['notes'];
    $notes= $_POST['notes'];
    // Get id for urlbar and  delete id related data from database.
    $DB->update_record('auth_coupon', $obj);
    redirect($CFG->wwwroot.'/auth/coupsign/coupmanage.php');

}
echo $OUTPUT->footer();
?>