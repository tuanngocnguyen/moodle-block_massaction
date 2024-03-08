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
 * block_massaction phpunit test class.
 *
 * @package    block_massaction
 * @copyright  2021 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_massaction;

use advanced_testcase;

class massactionutils_test extends advanced_testcase {

    /**
     * Private test set up method.
     *
     * @return array course id and format.
     */
    private function test_setup(): array {
        $this->resetAfterTest();

        // Create a course.
        $course = $this->getDataGenerator()->create_course();

        // Get course format.
        $coursemodinfo = get_fast_modinfo($course);
        $format = course_get_format($coursemodinfo->get_course())->get_format();

        return [$course->id, $format];
    }

    /**
     * Check default values of the get_restricted_sections method.
     * @covers \block_massaction\massactionutils::get_restricted_sections
     *
     */
    public function test_get_restricted_sections() {
        $this->resetAfterTest();

        // Target course id and format.
        [$courseid, $format] = $this->test_setup();

        // Get restricted sections.
        $restrictedsections = massactionutils::get_restricted_sections($courseid, $format);

        // Check if the restricted sections are empty.
        $this->assertIsArray($restrictedsections);
        $this->assertEmpty($restrictedsections);
    }

    /**
     * Check default values of the can_add_section method.
     * @covers \block_massaction\massactionutils::can_add_section
     *
     */
    public function test_can_add_section() {
        $this->resetAfterTest();

        // Create a user.
        $user = $this->getDataGenerator()->create_user();
        // Set the user as the current user.
        $this->setUser($user);

        // Target course id and format.
        [$courseid, $format] = $this->test_setup();

        // Check if a section can be added.
        $this->assertFalse(massactionutils::can_add_section($courseid, $format));

        // Enrol the user as a editing teacher.
        $this->getDataGenerator()->enrol_user($user->id, $courseid, 'editingteacher');

        // Check if a section can be added.
        $this->assertTrue(massactionutils::can_add_section($courseid, $format));
    }

    /**
     * Check default values of the can_keep_original_section_number method.
     * @covers \block_massaction\massactionutils::can_keep_original_section_number
     *
     */
    public function test_can_keep_original_section_number() {
        $this->resetAfterTest();

        // Target course id and format.
        [$courseid, $format] = $this->test_setup();

        // Check if the original section number can be kept.
        $this->assertTrue(massactionutils::can_keep_original_section_number($courseid, $format));
    }
}
