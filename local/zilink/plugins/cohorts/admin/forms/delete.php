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
 * Defines the capabilities for the ZiLink block
 *
 * @package     local_zilink
 * @author      Ian Tasker <ian.tasker@schoolsict.net>
 * @copyright   2010 onwards SchoolsICT Limited, UK (http://schoolsict.net)
 * @copyright   Includes sub plugins that are based on and/or adapted from other plugins please see sub plugins for credits and notices. 
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

    defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');


class zilink_cohorts_delete_form extends moodleform {

    public function definition() {
        global $CFG, $DB;  

        $mform = & $this->_form;
        
        $mform->addElement('header', 'moodle', get_string('cohorts_cohort_delete', 'local_zilink'));
        
        
        $mform->addElement('html', '<table class="generaltable boxaligncenter" width="80%"><tbody><tr>');
                            
        $count = 0;
        foreach ($this->_customdata['cohorts'] as $cohort)
        {
            if(!($count % 4)) {
                $mform->addElement('html','</tr><tr>');
            }
            
            $mform->addElement('html','<td class="cell c1 " >');
            $mform->addElement('advcheckbox', 'cohort_delete['.$cohort->id.']', null, $cohort->name, array('group' => 1));
            $mform->addElement('html', '</td>');
            $count++;
        }

        $mform->addElement('html', '</tr>
                            </tbody>    
                            </table>'); 
        
        $this->add_action_buttons(false, get_string('delete'));
    }

    function display()
    {
        return $this->_form->toHtml();
    }

}
