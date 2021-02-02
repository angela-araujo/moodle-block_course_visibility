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
 * Course Visibility Block
 * This block allows you to change the course visibility quickly without having to enter the settings
 *  
 * @package   block_course_visibility
 * @copyright 2016 CCEAD PUC-Rio <angela@ccead.puc-rio.br>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



class block_course_visibility extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_course_visibility');        
    }

    // Only one instance of this block is required
    function instance_allow_multiple() {
        return false;
    }

    // Label and button values can be set in admin
    function has_config() {
        return false;
    }
	
	// block will not display a title 
	function hide_header() {
		return true;
	}
    
    function get_content() {
        
        global $COURSE, $OUTPUT, $CFG;
        
        if ($this->content !== NULL) {
            return $this->content;
        }
        
        $coursevisible = $COURSE->visible == 1;
        
        $content = '';

        $context = context_course::instance($COURSE->id);
        
        if (has_capability('moodle/course:update', $context) && ($COURSE->id <> 1)) {            

            if ( $coursevisible ) {
                $iconvisibility = $OUTPUT->pix_icon('blue_exclamation', '', 'block_course_visibility');
                $visibility = get_string('courseshow', 'block_course_visibility');
                $textvisibility = get_string('msgcourseshow','block_course_visibility');
                $icontooltip = $OUTPUT->help_icon('msgcourseshow', 'block_course_visibility');
                $iconbutton = $OUTPUT->pix_icon('t/hide', get_string('show'));
                $textbutton = get_string('tohide','block_course_visibility');
                $newvalue = 0;
                $classe = 'courseshow';
                $classelink = ''; //linktohide';
            }
            else {
                $iconvisibility = $OUTPUT->pix_icon('gray_exclamation', '', 'block_course_visibility');
                $visibility = get_string('coursehide', 'block_course_visibility');
                $textvisibility = get_string('msgcoursehide','block_course_visibility');
                $icontooltip = $OUTPUT->help_icon('msgcoursehide', 'block_course_visibility');
                $iconbutton = $OUTPUT->pix_icon('t/show', get_string('hide'));
                $textbutton = get_string('toshow','block_course_visibility');
                $newvalue = 1;            
                $classe = 'coursehide';
                $classelink = '';
            }

            require_once($CFG->libdir.'/formslib.php'); 
            
            $link    = "$CFG->wwwroot/blocks/course_visibility/update.php?courseid=$COURSE->id&newvalue=$newvalue&change=s";
            $content = '<div class="visibility">'
                . $iconvisibility
                . '<h4>' . get_string('coursetextvisibility','block_course_visibility')
                . ' <br><span class="'.$classe.'">' . $visibility . '</span>  '. $icontooltip
                . '</h4>' 
                . '<a title="'. $textvisibility . '" href="'.$link.'" class="'.$classelink.'">' .$iconbutton . ' <span>' . $textbutton . '</span></a>'
                . '</div>' ;
        }
        
        $this->content = new stdClass;        
        $this->content->text = $content;
        $this->content->footer = '';        
        
        return $this->content;
    }
 
}
