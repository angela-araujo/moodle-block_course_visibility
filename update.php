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
 * This file contains functions used by the course visibility Block
 *
 * @package   block_course_visibility
 * @copyright 2016 CCEAD PUC-Rio <angela@ccead.puc-rio.br>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$courseid      = required_param('courseid', PARAM_INT);
$newvisibility = required_param('newvalue', PARAM_INT);
$change        = required_param('change', PARAM_TEXT);

if ($change) {

    require_once($CFG->dirroot . '/course/lib.php');

    course_change_visibility($courseid, $newvisibility);

    // Trigger a course updated event.
    $event = \block_course_visibility\event\course_visibility_updated::create(array(
        'courseid' => $courseid,
        'objectid' => $courseid,
        'context' => context_course::instance($courseid),
        'other' => array('newvisibility' => $newvisibility)
    ));

    $event->trigger();

    rebuild_course_cache($courseid, true);

    redirect("$CFG->wwwroot/course/view.php?id=$courseid");

}
