<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpostwithcols.php';
require_once 'testobjs/class-tmpost.php';

class TMBasePostMananagePostColumnsTest extends WP_UnitTestCase {

	public function test_manage_post_columns_none() {
		$defaults = array(
			'a' => 'A',
			'b' => 'B',
		);
		$base     = TMPost::create_post( 'post_title' );
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
			'TMPostWithCols_meta_attrib_1'            => '1-meta_attrib',
			'TMPostWithCols_meta_attrib_2'            => '2-meta_attrib',
			'TMPostWithCols_meta_attrib_nocontent'    => 'nocontent-meta_attrib',
			'TMPostWithCols_meta_attrib_relatedpost'  => 'meta_attrib_relatedpost',
			'TMPostWithCols_meta_attrib_relatedpost2' => 'meta_attrib_relatedpost2',
			'TMPostWithCols_meta_attrib_relatedtax'   => 'meta_attrib_relatedtax',
		);
		$base            = TMPostWithCols::create_post( 'post_title' );
		$result          = $base->manage_post_columns( $defaults );
		$this->assertEquals( $result, $expectedresults );
	}

}
