<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpostwithcols.php';
require_once 'testobjs/class-wcpost.php';

class WCBasePostMananagePostColumnsTest extends WP_UnitTestCase {

	public function test_manage_post_columns_none() {
		$defaults = array(
			'a' => 'A',
			'b' => 'B',
		);
		$base     = WCPost::create_post( 'post_title' );
		$result   = $base->manage_post_columns( $defaults );
		$this->assertEquals( $result, $defaults );
	}

	public function test_manage_post_columns() {
		$defaults        = array(
			'a' => 'A',
			'b' => 'B',
		);
		$expectedresults = array(
			'a'                                       => 'A',
			'b'                                       => 'B',
			'WCPostWithCols_meta_attrib_1'            => '1-meta_attrib',
			'WCPostWithCols_meta_attrib_2'            => '2-meta_attrib',
			'WCPostWithCols_meta_attrib_nocontent'    => 'nocontent-meta_attrib',
			'WCPostWithCols_meta_attrib_relatedpost'  => 'meta_attrib_relatedpost',
			'WCPostWithCols_meta_attrib_relatedpost2' => 'meta_attrib_relatedpost2',
			'WCPostWithCols_meta_attrib_relatedtax'   => 'meta_attrib_relatedtax',
		);
		$base            = WCPostWithCols::create_post( 'post_title' );
		$result          = $base->manage_post_columns( $defaults );
		$this->assertEquals( $result, $expectedresults );
	}

}
