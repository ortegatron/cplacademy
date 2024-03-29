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
 * Adds new instance of enrol_elediacohortgreeting to specified course.
 *
 * @package    enrol
 * @subpackage elediacohortgreeting
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2013 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once("$CFG->dirroot/enrol/elediacohortgreeting/edit_form.php");
require_once("$CFG->dirroot/enrol/elediacohortgreeting/locallib.php");
require_once("$CFG->dirroot/group/lib.php");

$courseid = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT);

$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('moodle/course:enrolconfig', $context);
require_capability('enrol/elediacohortgreeting:config', $context);

$PAGE->set_url('/enrol/elediacohortgreeting/edit.php', array('courseid'=>$course->id, 'id'=>$instanceid));
$PAGE->set_pagelayout('admin');

$returnurl = new moodle_url('/enrol/instances.php', array('id'=>$course->id));
if (!enrol_is_enabled('elediacohortgreeting')) {
    redirect($returnurl);
}

$enrol = enrol_get_plugin('elediacohortgreeting');

if ($instanceid) {
    $instance = $DB->get_record('enrol', array('courseid'=>$course->id, 'enrol'=>'elediacohortgreeting', 'id'=>$instanceid), '*', MUST_EXIST);

} else {
    // No instance yet, we have to add new instance.
    if (!$enrol->get_newinstance_link($course->id)) {
        redirect($returnurl);
    }
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));
    $instance = new stdClass();
    $instance->id         = null;
    $instance->courseid   = $course->id;
    $instance->enrol      = 'elediacohortgreeting';
    $instance->customint1 = ''; // Cohort id.
    $instance->customint2 = 0;  // Optional group id.
    $instance->customint3 = 0;   //X send cohort course greeting checkbox
    $instance->customtext1 = ''; //X greetingtext.
}

// Try and make the manage instances node on the navigation active.
$courseadmin = $PAGE->settingsnav->get('courseadmin');
if ($courseadmin && $courseadmin->get('users') && $courseadmin->get('users')->get('manageinstances')) {
    $courseadmin->get('users')->get('manageinstances')->make_active();
}


$mform = new enrol_elediacohortgreeting_edit_form(null, array($instance, $enrol, $course));

if ($mform->is_cancelled()) {
    redirect($returnurl);

} else if ($data = $mform->get_data()) {
    if ($data->id) {
        // NOTE: no cohort changes here!!!
        if ($data->roleid != $instance->roleid) {
            // The sync script can only add roles, for perf reasons it does not modify them.
            role_unassign_all(array('contextid'=>$context->id, 'roleid'=>$instance->roleid, 'component'=>'enrol_elediacohortgreeting', 'itemid'=>$instance->id));
        }
        $instance->name         = $data->name;
        $instance->status       = $data->status;
        $instance->roleid       = $data->roleid;
        $instance->customint2   = $data->customint2;
        $instance->customint3   = $data->customint3;
        $instance->customtext1  = $data->customtext1;
        $instance->timemodified = time();
        $DB->update_record('enrol', $instance);
    }  else {
                $enrol->add_instance($course,
                array('name' => $data->name,
                    'status' => $data->status,
                    'customint1' => $data->customint1,
                    'roleid' => $data->roleid,
                    'customint2'=>$data->customint2,
                    'customint3' => $data->customint3,
                    'customtext1' => $data->customtext1));
    }
    enrol_elediacohortgreeting_sync($course->id);
    redirect($returnurl);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_elediacohortgreeting'));

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
