<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpost.php';
/**
 * Sample test case.
 */
class WCBasePostCreateSettingsMenuTest extends WP_UnitTestCase {

	public function test_create_settings_menu() {
		global $submenu;

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		$this->assertFalse( isset( $submenu['edit.php?post_type=testpost'] ) );

		WCPost::create_settings_menu();

		$this->assertTrue( isset( $submenu['edit.php?post_type=testpost'] ) );
		$settings = $submenu['edit.php?post_type=testpost'];
		$this->assertSame( 'TestPosts Settings', $settings[0][0] );
		$this->assertSame( 'administrator', $settings[0][1] );
		$this->assertSame( 'WCPost::settings_page', $settings[0][2] );
		$this->assertSame( 'TestPosts Settings', $settings[0][3] );
	}

}
