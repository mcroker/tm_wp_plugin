<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpost.php';
require_once 'testobjs/class-wcpostwithnometabox.php';
require_once 'testobjs/class-wcpostwithsettings.php';
/**
 * Sample test case.
 */
class WCBasePostInitTest extends WP_UnitTestCase {

	public function test_init() {
		$this->assertFalse( has_action( 'init', 'WCPost::register_post_type' ) );
		WCPost::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'WCPost::register_post_type' ) );
		$this->assertFalse( has_action( 'add_meta_boxes', 'WCPost::create_metadata_box' ) );
		$this->assertFalse( has_action( 'save_post', 'WCPost::save_post' ) );
		$this->assertFalse( has_action( 'admin_menu', 'WCPost::create_settings_menu' ) );
		$this->assertFalse( has_action( 'admin_init', 'WCPost::register_settings' ) );
		$this->assertFalse( has_action( 'admin_enqueue_scripts', 'WCPost::enqueue_admin_scripts' ) );
		$this->assertFalse( has_action( 'manage_testpost_posts_columns', 'WCPost::manage_post_columns' ) );
		$this->assertFalse( has_action( 'manage_testpost_posts_custom_column', 'WCPost::manage_posts_custom_column' ) );
		$this->assertFalse( has_action( 'manage_edit-testpost_sortable_columns', 'WCPost::sortable_columns' ) );
		$this->assertFalse( has_action( 'restrict_manage_posts', 'WCPost::restrict_manage_posts' ) );
		$this->assertFalse( has_action( 'parse_query', 'WCPost::parse_query' ) );
		$this->assertFalse( has_action( 'admin_head', 'WCPost::admin_head_wrapper' ) );
		$this->assertFalse( has_action( 'admin_menu', 'WCPost::admin_menu' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'WCPost::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'WCPost::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'WCPost::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'WCPost::add_query_vars' ) );
	}

	public function test_init_admin() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		set_current_screen( 'edit-post' );
		$this->assertFalse( has_action( 'init', 'WCPost::register_post_type' ) );
		WCPost::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'WCPost::register_post_type' ) );
		$this->assertInternalType( 'int', has_action( 'add_meta_boxes', 'WCPost::create_metadata_box' ) );
		$this->assertInternalType( 'int', has_action( 'save_post', 'WCPost::save_post' ) );
		$this->assertFalse( has_action( 'admin_menu', 'WCPost::create_settings_menu' ) );
		$this->assertFalse( has_action( 'admin_init', 'WCPost::register_settings' ) );
		$this->assertInternalType( 'int', has_action( 'admin_enqueue_scripts', 'WCPost::enqueue_admin_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'manage_testpost_posts_columns', 'WCPost::manage_post_columns' ) );
		$this->assertInternalType( 'int', has_action( 'manage_testpost_posts_custom_column', 'WCPost::manage_posts_custom_column' ) );
		$this->assertInternalType( 'int', has_action( 'manage_edit-testpost_sortable_columns', 'WCPost::sortable_columns' ) );
		$this->assertInternalType( 'int', has_action( 'restrict_manage_posts', 'WCPost::restrict_manage_posts' ) );
		$this->assertInternalType( 'int', has_action( 'parse_query', 'WCPost::parse_query' ) );
		$this->assertInternalType( 'int', has_action( 'admin_head', 'WCPost::admin_head_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'WCPost::admin_menu_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'WCPost::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'WCPost::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'WCPost::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'WCPost::add_query_vars' ) );
	}

	public function test_init_admin_no_metadatabox() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		set_current_screen( 'edit-post' );
		$this->assertFalse( has_action( 'init', 'WCPostWithNoMetaBox::register_post_type' ) );
		WCPostWithNoMetaBox::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'WCPostWithNoMetaBox::register_post_type' ) );
		$this->assertFalse( has_action( 'add_meta_boxes', 'WCPostWithNoMetaBox::create_metadata_box' ) );
		$this->assertInternalType( 'int', has_action( 'save_post', 'WCPostWithNoMetaBox::save_post' ) );
		$this->assertFalse( has_action( 'admin_menu', 'WCPostWithNoMetaBox::create_settings_menu' ) );
		$this->assertFalse( has_action( 'admin_init', 'WCPostWithNoMetaBox::register_settings' ) );
		$this->assertInternalType( 'int', has_action( 'admin_enqueue_scripts', 'WCPostWithNoMetaBox::enqueue_admin_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'manage_testpost_nobox_posts_columns', 'WCPostWithNoMetaBox::manage_post_columns' ) );
		$this->assertInternalType( 'int', has_action( 'manage_testpost_nobox_posts_custom_column', 'WCPostWithNoMetaBox::manage_posts_custom_column' ) );
		$this->assertInternalType( 'int', has_action( 'manage_edit-testpost_nobox_sortable_columns', 'WCPostWithNoMetaBox::sortable_columns' ) );
		$this->assertInternalType( 'int', has_action( 'restrict_manage_posts', 'WCPostWithNoMetaBox::restrict_manage_posts' ) );
		$this->assertInternalType( 'int', has_action( 'parse_query', 'WCPostWithNoMetaBox::parse_query' ) );
		$this->assertInternalType( 'int', has_action( 'admin_head', 'WCPostWithNoMetaBox::admin_head_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'WCPostWithNoMetaBox::admin_menu_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'WCPostWithNoMetaBox::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'WCPostWithNoMetaBox::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'WCPostWithNoMetaBox::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'WCPostWithNoMetaBox::add_query_vars' ) );
	}

	public function test_init_admin_withsettings() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		set_current_screen( 'edit-post' );
		$this->assertFalse( has_action( 'init', 'WCPostWithSettings::register_post_type' ) );
		WCPostWithSettings::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'WCPostWithSettings::register_post_type' ) );
		$this->assertInternalType( 'int', has_action( 'add_meta_boxes', 'WCPostWithSettings::create_metadata_box' ) );
		$this->assertInternalType( 'int', has_action( 'save_post', 'WCPostWithSettings::save_post' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'WCPostWithSettings::create_settings_menu' ) );
		$this->assertInternalType( 'int', has_action( 'admin_init', 'WCPostWithSettings::register_settings' ) );
		$this->assertInternalType( 'int', has_action( 'admin_enqueue_scripts', 'WCPostWithSettings::enqueue_admin_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'manage_testpost_settings_posts_columns', 'WCPostWithSettings::manage_post_columns' ) );
		$this->assertInternalType( 'int', has_action( 'manage_testpost_settings_posts_custom_column', 'WCPostWithSettings::manage_posts_custom_column' ) );
		$this->assertInternalType( 'int', has_action( 'manage_edit-testpost_settings_sortable_columns', 'WCPostWithSettings::sortable_columns' ) );
		$this->assertInternalType( 'int', has_action( 'restrict_manage_posts', 'WCPostWithSettings::restrict_manage_posts' ) );
		$this->assertInternalType( 'int', has_action( 'parse_query', 'WCPostWithSettings::parse_query' ) );
		$this->assertInternalType( 'int', has_action( 'admin_head', 'WCPostWithSettings::admin_head_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'WCPostWithSettings::admin_menu_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'WCPostWithSettings::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'WCPostWithSettings::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'WCPostWithSettings::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'WCPostWithSettings::add_query_vars' ) );
	}
}
