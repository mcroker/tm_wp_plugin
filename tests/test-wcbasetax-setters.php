<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wctax.php';
/**
 * Sample test case.
 */
class WCBaseTaxSettersTest extends WP_UnitTestCase {

	public static function setUpBeforeClass() {
		WCTax::register_taxonomy();
	}

	public function test_constructor() {
		$base = WCTax::insert_term( 'tax_term_test_constructor' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseGeneric' ) );
	}

	// TODO SPECIAL Getters - e.g. ID.
	public function test_attrib_number_fail() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_number_fail' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$this->expectException( Exception::Class );
		$base->meta_attrib_number = 'STRING';
	}

	public function test_attrib_date() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_date' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_date = '1976-06-05';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_date, '1976-06-05' );
	}

	public function test_attrib_time() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_time' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$datetime = new DateTime( '10:30' );
		$base->meta_attrib_text = $datetime;
		$base->clear_cache();
		$this->assertEquals( $base->meta_attrib_text, $datetime );
	}

	public function test_attrib_text() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_text' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_text = 'TEXTVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_text, 'TEXTVALUE' );
	}

	public function test_attrib_code() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_code' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_code = 'CODEVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_code, 'CODEVALUE' );
	}

	public function test_attrib_string() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_string' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_string = 'STRINGVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_string, 'STRINGVALUE' );
	}

	public function test_attrib_check() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_check' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_check = true;
		$base->clear_cache();
		$this->assertTrue( $base->meta_attrib_check );
		$base->clear_cache();
		$base->meta_attrib_check = false;
		$this->assertFalse( $base->meta_attrib_check );
	}

	public function test_attrib_logo() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_logo' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_logo = 'LOGOVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_logo, 'LOGOVALUE' );
	}

	public function test_attrib_select() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_select' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$base->meta_attrib_select = 'SELECTVALUE';
		$base->clear_cache();
		$this->assertSame( $base->meta_attrib_select, 'SELECTVALUE' );
	}

	public function test_attrib_object() {
		$base = WCTax::insert_term( 'tax_term_test_attrib_object' );
		$this->assertThat( $base, $this->isInstanceOf( 'WCBaseTax' ) );
		$testobj = new DateTime( '10:30' );
		$base->meta_attrib_object = $testobj;
		$base->clear_cache();
		$this->assertEquals( $base->meta_attrib_object, $testobj );
	}
}
