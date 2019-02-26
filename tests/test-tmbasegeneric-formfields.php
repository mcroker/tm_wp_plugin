<?php
/**
 * Class TMBaseGenericFormFieldsTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmbase.php';

class TMBaseGenericFormFieldsTest extends WP_UnitTestCase {

	public function test_static_formfield_string() {
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="STATIC_meta_attrib_string"\s+value="TESTVALUE"\s+id="STATIC_meta_attrib_string"\s+/><br>!m' );
		TMBase::formfield_string( 'STATIC_meta_attrib_string', 'TESTVALUE', 'TESTLABEL' );
	}

	public function test_formfield_string() {
		$base                     = new TMBase( 0 );
		$base->meta_attrib_string = 'TESTVALUE';
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="TMBase_meta_attrib_string"\s+value="TESTVALUE"\s+id="TMBase_meta_attrib_string"\s+/><br>!m' );
		$base->formfield( 'meta_attrib_string' );
	}

	public function test_static_formfield_time() {
		$this->expectOutputRegex( '!<input\s+class=""\s+type="datetime-local"\s+name="STATIC_meta_attrib_time"\s+id="STATIC_meta_attrib_time"\s+value="UTC01:23"\s+/>!m' );
		TMBase::formfield_time( 'STATIC_meta_attrib_time', new DateTime( '01:23' ), 'TESTLABEL' );
	}

	public function test_formfield_time() {
		$base                   = new TMBase( 0 );
		$base->meta_attrib_time = new DateTime( '01:23' );
		$this->expectOutputRegex( '!<input\s+class=""\s+type="datetime-local"\s+name="TMBase_meta_attrib_time"\s+id="TMBase_meta_attrib_time"\s+value="UTC01:23"\s+/>!m' );
		$base->formfield( 'meta_attrib_time' );
	}

	public function test_static_formfield_time_fail() {
		$this->expectException( Exception::class );
		TMBase::formfield_time( 'meta_attrib_time', '01:23', 'TESTLABEL' );
	}

	public function test_static_formfield_date() {
		$this->expectOutputRegex( '!<input class=""\s+type="datetime-local"\s+name="STATIC_meta_attrib_date"\s+id="STATIC_meta_attrib_date"\s+value="1976-05-06T00:00"\s+/>!m' );
		TMBase::formfield_date( 'STATIC_meta_attrib_date', '1976-05-06', 'TESTLABEL' );
	}

	public function test_formfield_date() {
		$base                   = new TMBase( 0 );
		$base->meta_attrib_date = '1976-05-06';
		$this->expectOutputRegex( '!<input class=""\s+type="datetime-local"\s+name="TMBase_meta_attrib_date"\s+id="TMBase_meta_attrib_date"\s+value="1976-05-06T00:00"\s+/>!m' );
		$base->formfield( 'meta_attrib_date' );
	}

	public function test_static_formfield_check_false() {
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="STATIC_meta_attrib_check"\s+id="STATIC_meta_attrib_check"\s+/>!' );
		TMBase::formfield_check( 'STATIC_meta_attrib_check', false, 'TESTLABEL' );
	}

	public function test_formfield_check_false() {
		$base                    = new TMBase( 0 );
		$base->meta_attrib_check = false;
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="TMBase_meta_attrib_check"\s+id="TMBase_meta_attrib_check"\s+/>!' );
		$base->formfield( 'meta_attrib_check' );
	}

	public function test_static_formfield_check_true() {
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="STATIC_meta_attrib_check"\s+id="STATIC_meta_attrib_check"\s+checked=\'checked\'\s+/>!' );
		TMBase::formfield_check( 'STATIC_meta_attrib_check', true, 'TESTLABEL' );
	}

	public function test_formfield_check_true() {
		$base                    = new TMBase( 0 );
		$base->meta_attrib_check = true;
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="TMBase_meta_attrib_check"\s+id="TMBase_meta_attrib_check"\s+checked=\'checked\'\s+/>!' );
		$base->formfield( 'meta_attrib_check' );
	}

	public function test_static_formfield_number() {
		$this->expectOutputRegex( '!<input class=""\s+type="number"\s+name="STATIC_meta_attrib_check"\s+id="STATIC_meta_attrib_check"\s+value="123"\s+/>!m' );
		TMBase::formfield_number( 'STATIC_meta_attrib_check', 123, 'TESTLABEL' );
	}

	public function test_static_formfield_text() {
		$this->expectOutputRegex( '!<textarea class="wp-editor-area!m' );
		TMBase::formfield_text( 'STATIC_meta_attrib_test', 'DUMMYTEST', 'TESTLABEL' );
	}

	public function test_static_formfield_code() {
		$this->expectOutputRegex( '!<textarea class=""\s+style="width:100%"\s+rows=15\s+name="STATIC_meta_attrib_code"\s+id="STATIC_meta_attrib_code">DUMMYCODE</textarea>!m' );
		TMBase::formfield_code( 'STATIC_meta_attrib_code', 'DUMMYCODE', 'TESTLABEL' );
	}

	public function test_static_formfield_select() {
		// TODO.
	}

	public function test_static_formlabel() {
		$this->expectOutputRegex( '?\<label class="" for="meta_attrib"\>TESTLABEL\</label\>?' );
		TMBase::formfield_label( 'meta_attrib', 'TESTVALUE', 'TESTLABEL' );
	}

	public function test_static_formfield_logo() {
		// TODO.
	}

	public function test_static_formfield_object() {
		$this->expectException( Exception::class );
		TMBase::formfield_object( 'EXPECTECEPTION' );
	}

	public function test_static_formfield_button() {
		// TODO.
	}

	public function test_static_formfield_nonce() {
		// TODO.
	}

}
