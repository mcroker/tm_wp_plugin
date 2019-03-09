<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpostwithcols.php';
require_once 'testobjs/class-wcpost.php';

class WCBasePostSortableColumnsTest extends WP_UnitTestCase {

	public function test_add_sortable_columns() {
		$dummyinput     = array(
			'dummykey' => 'dummyvalue',
		);
		$expectedresult = array(
			'dummykey'                     => 'dummyvalue',
			'WCPostWithCols_meta_attrib_1' => 'WCPostWithCols_meta_attrib_1',
		);
		$result         = WCPostWithCols::sortable_columns( $dummyinput );
		$this->assertSame( $result, $expectedresult );
	}

	public function test_no_sortable_columns() {
		$dummyinput     = array(
			'dummykey' => 'dummyvalue',
		);
		$expectedresult = array(
			'dummykey' => 'dummyvalue',
		);
		$result         = WCPost::sortable_columns( $dummyinput );
		$this->assertSame( $result, $expectedresult );
	}


}
