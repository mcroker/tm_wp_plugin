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
class WCBasePostSettersTest extends WP_UnitTestCase {

	public function test_constructor() {
		$base = WCPost::create_post( 'post_title' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBasePost' ) );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
	}

	// TODO SPECIAL Getters - e.g. ID.
	public function test_attrib_number_fail() {
		$base = WCPost::create_post( 'post_title' );
		$this->expectException( Exception::Class );
		$base->meta_attrib_number = 'STRING';
	}

	public function test_attrib_date() {
		$base                   = WCPost::create_post( 'post_title' );
		$base->meta_attrib_date = '1976-06-05';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_date, '1976-06-05' );
	}

	public function test_attrib_time() {
		$base                   = WCPost::create_post( 'post_title' );
		$datetime               = new DateTime( '10:30' );
		$base->meta_attrib_text = $datetime;
		$base->clear_cache();
		$this->assertEquals( $base->meta_attrib_text, $datetime );
	}

	public function test_attrib_text() {
		$base                   = WCPost::create_post( 'post_title' );
		$base->meta_attrib_text = 'TEXTVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_text, 'TEXTVALUE' );
	}

	public function test_attrib_code() {
		$base                   = WCPost::create_post( 'post_title' );
		$base->meta_attrib_code = 'CODEVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_code, 'CODEVALUE' );
	}

	public function test_attrib_string() {
		$base                     = WCPost::create_post( 'post_title' );
		$base->meta_attrib_string = 'STRINGVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_string, 'STRINGVALUE' );
	}

	public function test_attrib_check() {
		$base                    = WCPost::create_post( 'post_title' );
		$base->meta_attrib_check = true;
		$base->clear_cache();
		$this->assertTrue( $base->meta_attrib_check );
		$base->clear_cache();
		$base->meta_attrib_check = false;
		$this->assertFalse( $base->meta_attrib_check );
	}

	public function test_attrib_logo() {
		$base                   = WCPost::create_post( 'post_title' );
		$base->meta_attrib_logo = 'LOGOVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_logo, 'LOGOVALUE' );
	}

	public function test_attrib_select() {
		$base                     = WCPost::create_post( 'post_title' );
		$base->meta_attrib_select = 'SELECTVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_select, 'SELECTVALUE' );
	}

	public function test_attrib_object() {
		$base                     = WCPost::create_post( 'post_title' );
		$testobj                  = new DateTime( '10:30' );
		$base->meta_attrib_object = $testobj;
		$base->clear_cache();
		$this->assertEquals( $base->meta_attrib_object, $testobj );
	}
}
