<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpostwithcols.php';
require_once 'testobjs/class-tmpost.php';

class TMBasePostMananagePostCustomColumnTest extends WP_UnitTestCase {

	public function test_meta_attrib() {
		$base                = TMPostWithCols::create_post( 'post_title' );
		$base->meta_attrib_1 = 'ATTRIBVALUE';
		$this->expectOutputString( 'ATTRIBVALUE' );
		TMPostWithCols::manage_posts_custom_column( 'TMPostWithCols_meta_attrib_1', $base->ID );
	}

	public function test_meta_attrib_contentfalse() {
		$base                        = TMPostWithCols::create_post( 'post_title' );
		$base->meta_attrib_nocontent = 'ATTRIBVALUE';
		$this->expectOutputString( '' );
		TMPostWithCols::manage_posts_custom_column( 'TMPostWithCols_meta_attrib_nocontent', $base->ID );
	}


	public function test_meta_attrib_relatedpost() {
		// TODO.
	}

	public function test_meta_attrib_relatedtax() {
		// TODO.
	}

}
