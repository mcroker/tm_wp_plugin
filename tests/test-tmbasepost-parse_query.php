<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpost.php';
require_once 'testobjs/class-tmpostwithcols.php';
/**
 * Sample test case.
 */
class TMBasePostParseQueryTest extends WP_UnitTestCase {

	public function test_no_cols() {
		global $_GET, $wp_the_query, $pagenow;
		$backup_GET        = $_GET;
		$backup_query      = $wp_the_query;
		$backup_pagename   = $pagenow;
		$_GET['post_type'] = 'tm_testpost';
		$pagenow           = 'edit.php';
		TMPost::parse_query( $wp_the_query );
		$_GET         = $backup_GET;
		$wp_the_query = $backup_query;
		$pagenow      = $backup_pagename;
		$this->assertArrayNotHasKey( 'meta_query', $wp_the_query->query_vars );
		$this->assertArrayNotHasKey( 'tax_query', $wp_the_query->query_vars );
	}

	public function test_no_filters() {
		global $_GET, $wp_the_query, $pagenow;
		$backup_GET                                 = $_GET;
		$backup_query                               = $wp_the_query;
		$backup_pagename                            = $pagenow;
		$_GET['post_type']                          = 'tm_testpost_withcols';
		$pagenow                                    = 'edit.php';

		TMPostWithCols::parse_query( $wp_the_query );
		$_GET         = $backup_GET;
		$wp_the_query = $backup_query;
		$pagenow      = $backup_pagename;
		$this->assertArrayNotHasKey( 'meta_query', $wp_the_query->query_vars );
		$this->assertArrayNotHasKey( 'tax_query', $wp_the_query->query_vars );
	}

	public function test_meta_filter() {
		global $_GET, $wp_the_query, $pagenow;
		$backup_GET                                     = $_GET;
		$backup_query                                   = $wp_the_query;
		$backup_pagename                                = $pagenow;
		$_GET['post_type']                              = 'tm_testpost_withcols';
		$_GET['TMPostWithCols_meta_attrib_relatedpost'] = 'TESTMETA';

		$author1 = $this->factory->user->create_and_get( array( 'user_login' => 'jdoe', 'user_pass' => NULL, 'role' => 'author' ));
		$this->assertTrue( 0 !== $author1->ID );
 		wp_set_current_user( $author1->ID );
		$_GET['TMPostWithCols_default_field_nonce']     = wp_create_nonce( 'TMPostWithCols_default_nonce' );

		$pagenow                                        = 'edit.php';
		TMPostWithCols::parse_query( $wp_the_query );
		$_GET                = $backup_GET;
		$pagenow             = $backup_pagename;
		$expected_meta_query = array(
			0 => array(
				'key'     => 'meta_attrib_relatedpost',
				'value'   => 'TESTMETA',
				'compare' => '=',
			),
		);
		$this->assertArrayHasKey( 'meta_query', $wp_the_query->query_vars );
		$this->assertArrayNotHasKey( 'tax_query', $wp_the_query->query_vars );
		$this->assertEquals( $expected_meta_query, $wp_the_query->query_vars['meta_query'] );
		$wp_the_query = $backup_query;
	}

	public function test_meta_multiple_filters() {
		global $_GET, $wp_the_query, $pagenow;
		$backup_GET                                      = $_GET;
		$backup_query                                    = $wp_the_query;
		$backup_pagename                                 = $pagenow;
		$_GET['post_type']                               = 'tm_testpost_withcols';
		$_GET['TMPostWithCols_meta_attrib_relatedpost']  = 'TESTMETA';
		$_GET['TMPostWithCols_meta_attrib_relatedpost2'] = 'TESTMETA2';
		$pagenow = 'edit.php';

		$author1 = $this->factory->user->create_and_get( array( 'user_login' => 'jdoe', 'user_pass' => NULL, 'role' => 'author' ));
		$this->assertTrue( 0 !== $author1->ID );
 		wp_set_current_user( $author1->ID );
		$_GET['TMPostWithCols_default_field_nonce']     = wp_create_nonce( 'TMPostWithCols_default_nonce' );
		
		TMPostWithCols::parse_query( $wp_the_query );
		$_GET                = $backup_GET;
		$pagenow             = $backup_pagename;
		$expected_meta_query = array(
			0          => array(
				'key'     => 'meta_attrib_relatedpost',
				'value'   => 'TESTMETA',
				'compare' => '=',
			),
			1          => array(
				'key'     => 'meta_attrib_relatedpost2',
				'value'   => 'TESTMETA2',
				'compare' => '=',
			),
			'relation' => 'AND',
		);
		$this->assertArrayHasKey( 'meta_query', $wp_the_query->query_vars );
		$this->assertArrayNotHasKey( 'tax_query', $wp_the_query->query_vars );
		$this->assertEquals( $expected_meta_query, $wp_the_query->query_vars['meta_query'] );
		$wp_the_query = $backup_query;
	}

	public function test_tax_filter() {
		// TODO.
	}

	public function test_tax_multiple_filters() {
		// TODO.
	}

	public function test_tax_and_meta_filters() {
		// TODO.
	}

}
