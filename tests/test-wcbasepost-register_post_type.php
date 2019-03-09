<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpost.php';
require_once 'testobjs/class-wcpostwithoverrides.php';
/**
 * Sample test case.
 */
class WCBasePostRegisterPostTypeTest extends WP_UnitTestCase {

	public function test_register_posttype() {
		global $wp_post_types;
		if ( post_type_exists( 'testpost' ) ) {
			unregister_post_type( 'testpost' );
		}
		$this->assertarrayNotHasKey( 'testpost', $wp_post_types );
		WCPost::register_post_type();
		$this->assertarrayHasKey( 'testpost', $wp_post_types );
		$pt = $wp_post_types['testpost'];
		$this->assertInstanceOf( WP_Post_Type::class, $pt );
		$this->assertSame( $pt->name, 'testpost' );
		$this->assertSame( $pt->label, 'TestPosts' );
		$this->assertSame( $pt->description, 'TestPosts details' );
		$this->assertTrue( $pt->public );
		$lb = $pt->labels;
		$this->assertSame( $lb->name, 'TestPosts' );
		$this->assertSame( $lb->singular_name, 'TestPost' );
		$this->assertSame( $lb->add_new_item, 'Add New TestPost' );
		$this->assertSame( $lb->edit_item, 'Edit TestPost' );
		$this->assertSame( $lb->view_item, 'View TestPost' );
		$this->assertSame( $lb->search_items, 'Search TestPost' );
		$this->assertSame( $lb->all_items, 'All TestPosts' );
		$this->assertSame( $lb->archives, 'All TestPosts' );
		$this->assertSame( $lb->menu_name, 'TestPosts' );
		$this->assertSame( $lb->update_item, 'Update TestPost' );
		$rw = $pt->rewrite;
		$this->assertSame( $rw['slug'], 'testpost' );
	}

	public function test_register_with_overrides() {
		global $wp_post_types;
		if ( post_type_exists( 'testpost_over' ) ) {
			unregister_post_type( 'testpost_over' );
		}
		$this->assertarrayNotHasKey( 'testpost_over', $wp_post_types );
		WCPostWithOverrides::register_post_type();
		$this->assertarrayHasKey( 'testpost_over', $wp_post_types );
		$pt = $wp_post_types['testpost_over'];
		$this->assertInstanceOf( WP_Post_Type::class, $pt );

		$this->assertSame( $pt->name, 'testpost_over' );
		$this->assertSame( $pt->label, 'TestPostWithOverridess' );
		$this->assertSame( $pt->description, 'TestPostWithOverridess details' );
		$this->assertFalse( $pt->public );
		$lb = $pt->labels;
		$this->assertSame( $lb->name, 'TestPostWithOverridess' );
		$this->assertSame( $lb->singular_name, 'TestPostWithOverrides' );
		$this->assertSame( $lb->add_new_item, 'add_new_item' );
		$this->assertSame( $lb->edit_item, 'edit_item' );
		$this->assertSame( $lb->view_item, 'view_item' );
		$this->assertSame( $lb->search_items, 'search_items' );
		$this->assertSame( $lb->all_items, 'all_items' );
		$this->assertSame( $lb->archives, 'all_items' );
		$this->assertSame( $lb->menu_name, 'menu_name' );
		$this->assertSame( $lb->update_item, 'update_item' );
		$rw = $pt->rewrite;
		$this->assertSame( $rw['slug'], 'testpostwithoverrides' );
	}

	public function test_register_posttype_twice() {
		global $wp_post_types;
		if ( post_type_exists( 'testpost' ) ) {
			unregister_post_type( 'testpost' );
		}
		$this->assertarrayNotHasKey( 'testpost', $wp_post_types );

		WCPost::register_post_type();
		$this->assertarrayHasKey( 'testpost', $wp_post_types );

		WCPost::register_post_type();
		$this->assertarrayHasKey( 'testpost', $wp_post_types );
	}
}
