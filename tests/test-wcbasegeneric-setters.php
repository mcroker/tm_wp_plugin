<?php
/**
 * Class WCBaseGenericSettersTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcbase.php';

class WCBaseGenericSettersTest extends WP_UnitTestCase {

	public function test_constructor() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
	}

	public function test_attrib_date() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_date = '1976-06-05';
		$this->assertSame( $base->meta_attrib_date, '1976-06-05' );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_date, '1976-06-05' );
	}

	public function test_attrib_time() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$datetime = new DateTime( '10:30' );
		$base->meta_attrib_time = $datetime;
		$this->assertEquals( $base->meta_attrib_time, $datetime );
		$base->clear_cache();
		$this->assertEquals( $base->meta_attrib_time, $datetime );
	}

	public function test_attrib_text() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_text = 'TEXTVALUE';
		$this->assertSame( $base->meta_attrib_text, 'TEXTVALUE' );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_text, 'TEXTVALUE' );
	}

	public function test_attrib_code() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_code = 'CODEVALUE';
		$this->assertSame( $base->meta_attrib_code, 'CODEVALUE' );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_code, 'CODEVALUE' );
	}

	public function test_attrib_string() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_string = 'STRINGVALUE';
		$this->assertSame( $base->meta_attrib_string, 'STRINGVALUE' );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_string, 'STRINGVALUE' );
	}

	public function test_attrib_number() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_number = 304;
		$this->assertSame( $base->meta_attrib_number, 304 );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_number, 304 );
	}

	public function test_attrib_number_fail() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::Class );
		$base->meta_attrib_number = 'STRING';
	}

	public function test_attrib_check() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_check = true;
		$this->assertTrue( $base->meta_attrib_check );
		$base->clear_cache();
		$base->meta_attrib_check = false;
		$this->assertFalse( $base->meta_attrib_check );
		$base->clear_cache();
		$this->assertFalse( $base->meta_attrib_check );
	}

	public function test_attrib_logo() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_logo = 'LOGOVALUE';
		$this->assertSame( $base->meta_attrib_logo, 'LOGOVALUE' );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_logo, 'LOGOVALUE' );
	}

	public function test_attrib_select() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$base->meta_attrib_select = 'SELECTVALUE';
		$this->assertSame( $base->meta_attrib_select, 'SELECTVALUE' );
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_select, 'SELECTVALUE' );
	}

	public function test_attrib_object() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$testobj = new DateTime( '10:30' );
		$base->meta_attrib_object = $testobj;
		$this->assertSame( $base->meta_attrib_object, $testobj );
		$base->clear_cache();
		$this->assertEquals( $base->meta_attrib_object, $testobj );
	}

	public function test_related_post_get() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$dummy = $base->related_post = 'EXPECTEXCEPTION';
	}

	public function test_related_post_set() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$base->related_post = 'EXPECTEXCEPTION';
	}

	public function test_related_posts_get() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$dummy = $base->related_posts;
	}

	public function test_related_posts_set() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$base->related_posts = 'EXPECTEXCEPTION';
	}

	public function test_related_tax_get() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$dummy = $base->related_tax;
	}

	public function test_related_tax_set() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$base->related_tax = 'EXPECTEXCEPTION';
	}

	public function test_unknown_key_get() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$dummy = $base->non_existent_key;
	}

	public function test_unknown_key_set() {
		$base = new WCBase( 0 );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
		$this->expectException( Exception::class );
		$base->non_existent_key = 'EXPECTEXCEPTION';
	}

}
