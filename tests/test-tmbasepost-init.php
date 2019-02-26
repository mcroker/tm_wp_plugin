<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpost.php';
require_once 'testobjs/class-tmpostwithnometabox.php';
require_once 'testobjs/class-tmpostwithsettings.php';
/**
 * Sample test case.
 */
class TMBasePostInitTest extends WP_UnitTestCase {

	public function test_init() {
		$this->assertFalse( has_action( 'init', 'TMPost::register_post_type' ) );
		TMPost::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'TMPost::register_post_type' ) );
		$this->assertFalse( has_action( 'add_meta_boxes', 'TMPost::create_metadata_box' ) );
		$this->assertFalse( has_action( 'save_post', 'TMPost::save_post' ) );
		$this->assertFalse( has_action( 'admin_menu', 'TMPost::create_settings_menu' ) );
		$this->assertFalse( has_action( 'admin_init', 'TMPost::register_settings' ) );
		$this->assertFalse( has_action( 'admin_enqueue_scripts', 'TMPost::enqueue_admin_scripts' ) );
		$this->assertFalse( has_action( 'manage_tm_testpost_posts_columns', 'TMPost::manage_post_columns' ) );
		$this->assertFalse( has_action( 'manage_tm_testpost_posts_custom_column', 'TMPost::manage_posts_custom_column' ) );
		$this->assertFalse( has_action( 'manage_edit-tm_testpost_sortable_columns', 'TMPost::sortable_columns' ) );
		$this->assertFalse( has_action( 'restrict_manage_posts', 'TMPost::restrict_manage_posts' ) );
		$this->assertFalse( has_action( 'parse_query', 'TMPost::parse_query' ) );
		$this->assertFalse( has_action( 'admin_head', 'TMPost::admin_head_wrapper' ) );
		$this->assertFalse( has_action( 'admin_menu', 'TMPost::admin_menu' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'TMPost::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'TMPost::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'TMPost::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'TMPost::add_query_vars' ) );
	}

	public function test_init_admin() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		set_current_screen( 'edit-post' );
		$this->assertFalse( has_action( 'init', 'TMPost::register_post_type' ) );
		TMPost::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'TMPost::register_post_type' ) );
		$this->assertInternalType( 'int', has_action( 'add_meta_boxes', 'TMPost::create_metadata_box' ) );
		$this->assertInternalType( 'int', has_action( 'save_post', 'TMPost::save_post' ) );
		$this->assertFalse( has_action( 'admin_menu', 'TMPost::create_settings_menu' ) );
		$this->assertFalse( has_action( 'admin_init', 'TMPost::register_settings' ) );
		$this->assertInternalType( 'int', has_action( 'admin_enqueue_scripts', 'TMPost::enqueue_admin_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'manage_tm_testpost_posts_columns', 'TMPost::manage_post_columns' ) );
		$this->assertInternalType( 'int', has_action( 'manage_tm_testpost_posts_custom_column', 'TMPost::manage_posts_custom_column' ) );
		$this->assertInternalType( 'int', has_action( 'manage_edit-tm_testpost_sortable_columns', 'TMPost::sortable_columns' ) );
		$this->assertInternalType( 'int', has_action( 'restrict_manage_posts', 'TMPost::restrict_manage_posts' ) );
		$this->assertInternalType( 'int', has_action( 'parse_query', 'TMPost::parse_query' ) );
		$this->assertInternalType( 'int', has_action( 'admin_head', 'TMPost::admin_head_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'TMPost::admin_menu' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'TMPost::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'TMPost::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'TMPost::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'TMPost::add_query_vars' ) );
	}

	public function test_init_admin_no_metadatabox() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		set_current_screen( 'edit-post' );
		$this->assertFalse( has_action( 'init', 'TMPostWithNoMetaBox::register_post_type' ) );
		TMPostWithNoMetaBox::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'TMPostWithNoMetaBox::register_post_type' ) );
		$this->assertFalse( has_action( 'add_meta_boxes', 'TMPostWithNoMetaBox::create_metadata_box' ) );
		$this->assertInternalType( 'int', has_action( 'save_post', 'TMPostWithNoMetaBox::save_post' ) );
		$this->assertFalse( has_action( 'admin_menu', 'TMPostWithNoMetaBox::create_settings_menu' ) );
		$this->assertFalse( has_action( 'admin_init', 'TMPostWithNoMetaBox::register_settings' ) );
		$this->assertInternalType( 'int', has_action( 'admin_enqueue_scripts', 'TMPostWithNoMetaBox::enqueue_admin_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'manage_tm_testpost_nobox_posts_columns', 'TMPostWithNoMetaBox::manage_post_columns' ) );
		$this->assertInternalType( 'int', has_action( 'manage_tm_testpost_nobox_posts_custom_column', 'TMPostWithNoMetaBox::manage_posts_custom_column' ) );
		$this->assertInternalType( 'int', has_action( 'manage_edit-tm_testpost_nobox_sortable_columns', 'TMPostWithNoMetaBox::sortable_columns' ) );
		$this->assertInternalType( 'int', has_action( 'restrict_manage_posts', 'TMPostWithNoMetaBox::restrict_manage_posts' ) );
		$this->assertInternalType( 'int', has_action( 'parse_query', 'TMPostWithNoMetaBox::parse_query' ) );
		$this->assertInternalType( 'int', has_action( 'admin_head', 'TMPostWithNoMetaBox::admin_head_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'TMPostWithNoMetaBox::admin_menu' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'TMPostWithNoMetaBox::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'TMPostWithNoMetaBox::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'TMPostWithNoMetaBox::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'TMPostWithNoMetaBox::add_query_vars' ) );
	}

	public function test_init_admin_withsettings() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		set_current_screen( 'edit-post' );
		$this->assertFalse( has_action( 'init', 'TMPostWithSettings::register_post_type' ) );
		TMPostWithSettings::Init();
		$this->assertInternalType( 'int', has_action( 'init', 'TMPostWithSettings::register_post_type' ) );
		$this->assertInternalType( 'int', has_action( 'add_meta_boxes', 'TMPostWithSettings::create_metadata_box' ) );
		$this->assertInternalType( 'int', has_action( 'save_post', 'TMPostWithSettings::save_post' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'TMPostWithSettings::create_settings_menu' ) );
		$this->assertInternalType( 'int', has_action( 'admin_init', 'TMPostWithSettings::register_settings' ) );
		$this->assertInternalType( 'int', has_action( 'admin_enqueue_scripts', 'TMPostWithSettings::enqueue_admin_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'manage_tm_testpost_settings_posts_columns', 'TMPostWithSettings::manage_post_columns' ) );
		$this->assertInternalType( 'int', has_action( 'manage_tm_testpost_settings_posts_custom_column', 'TMPostWithSettings::manage_posts_custom_column' ) );
		$this->assertInternalType( 'int', has_action( 'manage_edit-tm_testpost_settings_sortable_columns', 'TMPostWithSettings::sortable_columns' ) );
		$this->assertInternalType( 'int', has_action( 'restrict_manage_posts', 'TMPostWithSettings::restrict_manage_posts' ) );
		$this->assertInternalType( 'int', has_action( 'parse_query', 'TMPostWithSettings::parse_query' ) );
		$this->assertInternalType( 'int', has_action( 'admin_head', 'TMPostWithSettings::admin_head_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'admin_menu', 'TMPostWithSettings::admin_menu' ) );
		$this->assertInternalType( 'int', has_action( 'wp_enqueue_scripts', 'TMPostWithSettings::enqueue_scripts' ) );
		$this->assertInternalType( 'int', has_action( 'init', 'TMPostWithSettings::add_rewrite_rule' ) );
		$this->assertInternalType( 'int', has_action( 'post_type_link', 'TMPostWithSettings::post_type_link_wrapper' ) );
		$this->assertInternalType( 'int', has_action( 'query_vars', 'TMPostWithSettings::add_query_vars' ) );
	}
}
