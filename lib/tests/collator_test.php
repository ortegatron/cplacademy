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
 * Collator unit tests.
 *
 * @package    core
 * @category   phpunit
 * @copyright  2011 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for our utf-8 aware collator.
 *
 * Used for sorting.
 *
 * @package    core
 * @category   phpunit
 * @copyright  2011 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_collator_testcase extends basic_testcase {

    /**
     * @var string The initial lang, stored because we change it during testing
     */
    protected $initiallang = null;

    /**
     * @var string The last error that has occured
     */
    protected $error = null;

    /**
     * Prepares things for this test case
     * @return void
     */
    protected function setUp() {
        global $SESSION;
        if (isset($SESSION->lang)) {
            $this->initiallang = $SESSION->lang;
        }
        $SESSION->lang = 'en'; // make sure we test en language to get consistent results, hopefully all systems have this locale
        if (extension_loaded('intl')) {
            $this->error = 'Collation aware sorting not supported';
        } else {
            $this->error = 'Collation aware sorting not supported, PHP extension "intl" is not available.';
        }
        parent::setUp();
    }

    /**
     * Cleans things up after this test case has run
     * @return void
     */
    protected function tearDown() {
        global $SESSION;
        parent::tearDown();
        if ($this->initiallang !== null) {
            $SESSION->lang = $this->initiallang;
            $this->initiallang = null;
        } else {
            unset($SESSION->lang);
        }
    }

    /**
     * Tests the static asort method
     * @return void
     */
    public function test_asort() {
        $arr = array('b' => 'ab', 1 => 'aa', 0 => 'cc');
        $result = core_collator::asort($arr);
        $this->assertSame(array_values($arr), array('aa', 'ab', 'cc'));
        $this->assertSame(array_keys($arr), array(1, 'b', 0));
        $this->assertTrue($result);

        $arr = array('b' => 'ab', 1 => 'aa', 0 => 'cc');
        $result = core_collator::asort($arr, core_collator::SORT_STRING);
        $this->assertSame(array_values($arr), array('aa', 'ab', 'cc'));
        $this->assertSame(array_keys($arr), array(1, 'b', 0));
        $this->assertTrue($result);

        $arr = array('b' => 'aac', 1 => 'Aac', 0 => 'cc');
        $result = core_collator::asort($arr, (core_collator::SORT_STRING | core_collator::CASE_SENSITIVE));
        $this->assertSame(array_values($arr), array('Aac', 'aac', 'cc'));
        $this->assertSame(array_keys($arr), array(1, 'b', 0));
        $this->assertTrue($result);

        $arr = array('b' => 'a1', 1 => 'a10', 0 => 'a3b');
        $result = core_collator::asort($arr);
        $this->assertSame(array_values($arr), array('a1', 'a10', 'a3b'));
        $this->assertSame(array_keys($arr), array('b', 1, 0));
        $this->assertTrue($result);

        $arr = array('b' => 'a1', 1 => 'a10', 0 => 'a3b');
        $result = core_collator::asort($arr, core_collator::SORT_NATURAL);
        $this->assertSame(array_values($arr), array('a1', 'a3b', 'a10'));
        $this->assertSame(array_keys($arr), array('b', 0, 1));
        $this->assertTrue($result);

        $arr = array('b' => '1.1.1', 1 => '1.2', 0 => '1.20.2');
        $result = core_collator::asort($arr, core_collator::SORT_NATURAL);
        $this->assertSame(array_values($arr), array('1.1.1', '1.2', '1.20.2'));
        $this->assertSame(array_keys($arr), array('b', 1, 0));
        $this->assertTrue($result);

        $arr = array('b' => '-1', 1 => 1000, 0 => -1.2, 3 => 1, 4 => false);
        $result = core_collator::asort($arr, core_collator::SORT_NUMERIC);
        $this->assertSame(array_values($arr), array(-1.2, '-1', false, 1, 1000));
        $this->assertSame(array_keys($arr), array(0, 'b', 4, 3, 1));
        $this->assertTrue($result);

        $arr = array('b' => array(1), 1 => array(2, 3), 0 => 1);
        $result = core_collator::asort($arr, core_collator::SORT_REGULAR);
        $this->assertSame(array_values($arr), array(1, array(1), array(2, 3)));
        $this->assertSame(array_keys($arr), array(0, 'b', 1));
        $this->assertTrue($result);

        // test sorting of array of arrays - first element should be used for actual comparison
        $arr = array(0=>array('bb', 'z'), 1=>array('ab', 'a'), 2=>array('zz', 'x'));
        $result = core_collator::asort($arr, core_collator::SORT_REGULAR);
        $this->assertSame(array_keys($arr), array(1, 0, 2));
        $this->assertTrue($result);

        $arr = array('a' => 'áb', 'b' => 'ab', 1 => 'aa', 0=>'cc', 'x' => 'Áb',);
        $result = core_collator::asort($arr);
        $this->assertSame(array_values($arr), array('aa', 'ab', 'áb', 'Áb', 'cc'), $this->error);
        $this->assertSame(array_keys($arr), array(1, 'b', 'a', 'x', 0), $this->error);
        $this->assertTrue($result);

        $a = array(2=>'b', 1=>'c');
        $c =& $a;
        $b =& $a;
        core_collator::asort($b);
        $this->assertSame($a, $b);
        $this->assertSame($c, $b);
    }

    /**
     * Tests the static asort_objects_by_method method
     * @return void
     */
    public function test_asort_objects_by_method() {
        $objects = array(
            'b' => new string_test_class('ab'),
            1 => new string_test_class('aa'),
            0 => new string_test_class('cc')
        );
        $result = core_collator::asort_objects_by_method($objects, 'get_protected_name');
        $this->assertSame(array_keys($objects), array(1, 'b', 0));
        $this->assertSame($this->get_ordered_names($objects, 'get_protected_name'), array('aa', 'ab', 'cc'));
        $this->assertTrue($result);

        $objects = array(
            'b' => new string_test_class('a20'),
            1 => new string_test_class('a1'),
            0 => new string_test_class('a100')
        );
        $result = core_collator::asort_objects_by_method($objects, 'get_protected_name', core_collator::SORT_NATURAL);
        $this->assertSame(array_keys($objects), array(1, 'b', 0));
        $this->assertSame($this->get_ordered_names($objects, 'get_protected_name'), array('a1', 'a20', 'a100'));
        $this->assertTrue($result);
    }

    /**
     * Tests the static asort_objects_by_method method
     * @return void
     */
    public function test_asort_objects_by_property() {
        $objects = array(
            'b' => new string_test_class('ab'),
            1 => new string_test_class('aa'),
            0 => new string_test_class('cc')
        );
        $result = core_collator::asort_objects_by_property($objects, 'publicname');
        $this->assertSame(array_keys($objects), array(1, 'b', 0));
        $this->assertSame($this->get_ordered_names($objects, 'publicname'), array('aa', 'ab', 'cc'));
        $this->assertTrue($result);

        $objects = array(
            'b' => new string_test_class('a20'),
            1 => new string_test_class('a1'),
            0 => new string_test_class('a100')
        );
        $result = core_collator::asort_objects_by_property($objects, 'publicname', core_collator::SORT_NATURAL);
        $this->assertSame(array_keys($objects), array(1, 'b', 0));
        $this->assertSame($this->get_ordered_names($objects, 'publicname'), array('a1', 'a20', 'a100'));
        $this->assertTrue($result);
    }

    /**
     * Returns an array of sorted names
     * @param array $objects
     * @param string $methodproperty
     * @return type
     */
    protected function get_ordered_names($objects, $methodproperty = 'get_protected_name') {
        $return = array();
        foreach ($objects as $object) {
            if ($methodproperty == 'publicname') {
                $return[] = $object->publicname;
            } else {
                $return[] = $object->$methodproperty();
            }
        }
        return $return;
    }

    /**
     * Tests the static ksort method
     * @return void
     */
    public function test_ksort() {
        $arr = array('b' => 'ab', 1 => 'aa', 0 => 'cc');
        $result = core_collator::ksort($arr);
        $this->assertSame(array_keys($arr), array(0, 1, 'b'));
        $this->assertSame(array_values($arr), array('cc', 'aa', 'ab'));
        $this->assertTrue($result);

        $obj = new stdClass();
        $arr = array('1.1.1'=>array(), '1.2'=>$obj, '1.20.2'=>null);
        $result = core_collator::ksort($arr, core_collator::SORT_NATURAL);
        $this->assertSame(array_keys($arr), array('1.1.1', '1.2', '1.20.2'));
        $this->assertSame(array_values($arr), array(array(), $obj, null));
        $this->assertTrue($result);

        $a = array(2=>'b', 1=>'c');
        $c =& $a;
        $b =& $a;
        core_collator::ksort($b);
        $this->assertSame($a, $b);
        $this->assertSame($c, $b);
    }

    public function test_legacy_collatorlib() {
        $arr = array('b' => 'ab', 1 => 'aa', 0 => 'cc');
        $result = collatorlib::asort($arr);
        $this->assertSame(array_values($arr), array('aa', 'ab', 'cc'));
        $this->assertSame(array_keys($arr), array(1, 'b', 0));
        $this->assertTrue($result);
    }
}


/**
 * Simple class used to work with the unit test.
 *
 * @package    core
 * @category   phpunit
 * @copyright  2011 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class string_test_class extends stdClass {
    /**
     * @var string A public property
     */
    public $publicname;
    /**
     * @var string A protected property
     */
    protected $protectedname;
    /**
     * @var string A private property
     */
    private $privatename;
    /**
     * Constructs the test instance
     * @param string $name
     */
    public function __construct($name) {
        $this->publicname = $name;
        $this->protectedname = $name;
        $this->privatename = $name;
    }
    /**
     * Returns the protected property
     * @return string
     */
    public function get_protected_name() {
        return $this->protectedname;
    }
    /**
     * Returns the protected property
     * @return string
     */
    public function get_private_name() {
        return $this->publicname;
    }
}