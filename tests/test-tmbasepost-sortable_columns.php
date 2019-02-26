<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpostwithcols.php';
require_once 'testobjs/class-tmpost.php';

class TMBasePostSortableColumnsTest extends WP_UnitTestCase {

	public function test_add_sortable_columns() {
		$dummyinput     = array(
			'dummykey' => 'dummyvalue',
		);
		$expectedresult = array(
			'dummykey'                     => 'dummyvalue',
			'TMPostWithCols_meta_attrib_1' => 'TMPostWithCols_meta_attrib_1',
		);
		$result         = TMPostWithCols::sortable_columns( $dummyinput );
		$this->assertSame( $result, $expectedresult );
	}

	public function test_no_sortable_columns() {
		$dummyinput     = array(
			'dummykey' => 'dummyvalue',
		);
		$expectedresult = array(
			'dummykey' => 'dummyvalue',
		);
		$result         = TMPost::sortable_columns( $dummyinput );
		$this->assertSame( $result, $expectedresult );
	}


}
