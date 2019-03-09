<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpostwithcols.php';
require_once 'testobjs/class-wcpost.php';

class WCBasePostMananagePostCustomColumnTest extends WP_UnitTestCase {

	public function test_meta_attrib() {
		$base                = WCPostWithCols::create_post( 'post_title' );
		$base->meta_attrib_1 = 'ATTRIBVALUE';
		$this->expectOutputString( 'ATTRIBVALUE' );
		WCPostWithCols::manage_posts_custom_column( 'WCPostWithCols_meta_attrib_1', $base->ID );
	}

	public function test_meta_attrib_contentfalse() {
		$base                        = WCPostWithCols::create_post( 'post_title' );
		$base->meta_attrib_nocontent = 'ATTRIBVALUE';
		$this->expectOutputString( '' );
		WCPostWithCols::manage_posts_custom_column( 'WCPostWithCols_meta_attrib_nocontent', $base->ID );
	}


	public function test_meta_attrib_relatedpost() {
		// TODO.
	}

	public function test_meta_attrib_relatedtax() {
		// TODO.
	}

}
