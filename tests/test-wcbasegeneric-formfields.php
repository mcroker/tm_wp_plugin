<?php
/**
 * Class WCBaseGenericFormFieldsTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcbase.php';

class WCBaseGenericFormFieldsTest extends WP_UnitTestCase {

	public function test_static_formfield_string() {
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="STATIC_meta_attrib_string"\s+value="TESTVALUE"\s+id="STATIC_meta_attrib_string"\s+/><br>!m' );
		WCBase::formfield_string( 'STATIC_meta_attrib_string', 'TESTVALUE', 'TESTLABEL' );
	}

	public function test_formfield_string() {
		$base                     = new WCBase( 0 );
		$base->meta_attrib_string = 'TESTVALUE';
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="WCBase_meta_attrib_string"\s+value="TESTVALUE"\s+id="WCBase_meta_attrib_string"\s+/><br>!m' );
		$base->formfield( 'meta_attrib_string' );
	}

	public function test_static_formfield_time() {
		$this->expectOutputRegex( '!<input\s+class=""\s+type="datetime-local"\s+name="STATIC_meta_attrib_time"\s+id="STATIC_meta_attrib_time"\s+value="UTC01:23"\s+/>!m' );
		WCBase::formfield_time( 'STATIC_meta_attrib_time', new DateTime( '01:23' ), 'TESTLABEL' );
	}

	public function test_formfield_time() {
		$base                   = new WCBase( 0 );
		$base->meta_attrib_time = new DateTime( '01:23' );
		$this->expectOutputRegex( '!<input\s+class=""\s+type="datetime-local"\s+name="WCBase_meta_attrib_time"\s+id="WCBase_meta_attrib_time"\s+value="UTC01:23"\s+/>!m' );
		$base->formfield( 'meta_attrib_time' );
	}

	public function test_static_formfield_time_fail() {
		$this->expectException( Exception::class );
		WCBase::formfield_time( 'meta_attrib_time', '01:23', 'TESTLABEL' );
	}

	public function test_static_formfield_date() {
		$this->expectOutputRegex( '!<input class=""\s+type="datetime-local"\s+name="STATIC_meta_attrib_date"\s+id="STATIC_meta_attrib_date"\s+value="1976-05-06T00:00"\s+/>!m' );
		WCBase::formfield_date( 'STATIC_meta_attrib_date', '1976-05-06', 'TESTLABEL' );
	}

	public function test_formfield_date() {
		$base                   = new WCBase( 0 );
		$base->meta_attrib_date = '1976-05-06';
		$this->expectOutputRegex( '!<input class=""\s+type="datetime-local"\s+name="WCBase_meta_attrib_date"\s+id="WCBase_meta_attrib_date"\s+value="1976-05-06T00:00"\s+/>!m' );
		$base->formfield( 'meta_attrib_date' );
	}

	public function test_static_formfield_check_false() {
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="STATIC_meta_attrib_check"\s+id="STATIC_meta_attrib_check"\s+/>!' );
		WCBase::formfield_check( 'STATIC_meta_attrib_check', false, 'TESTLABEL' );
	}

	public function test_formfield_check_false() {
		$base                    = new WCBase( 0 );
		$base->meta_attrib_check = false;
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="WCBase_meta_attrib_check"\s+id="WCBase_meta_attrib_check"\s+/>!' );
		$base->formfield( 'meta_attrib_check' );
	}

	public function test_static_formfield_check_true() {
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="STATIC_meta_attrib_check"\s+id="STATIC_meta_attrib_check"\s+checked=\'checked\'\s+/>!' );
		WCBase::formfield_check( 'STATIC_meta_attrib_check', true, 'TESTLABEL' );
	}

	public function test_formfield_check_true() {
		$base                    = new WCBase( 0 );
		$base->meta_attrib_check = true;
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="WCBase_meta_attrib_check"\s+id="WCBase_meta_attrib_check"\s+checked=\'checked\'\s+/>!' );
		$base->formfield( 'meta_attrib_check' );
	}

	public function test_static_formfield_number() {
		$this->expectOutputRegex( '!<input class=""\s+type="number"\s+name="STATIC_meta_attrib_check"\s+id="STATIC_meta_attrib_check"\s+value="123"\s+/>!m' );
		WCBase::formfield_number( 'STATIC_meta_attrib_check', 123, 'TESTLABEL' );
	}

	public function test_static_formfield_text() {
		$this->expectOutputRegex( '!<textarea class="wp-editor-area!m' );
		WCBase::formfield_text( 'STATIC_meta_attrib_test', 'DUMMYTEST', 'TESTLABEL' );
	}

	public function test_static_formfield_code() {
		$this->expectOutputRegex( '!<textarea class=""\s+style="width:100%"\s+rows=15\s+name="STATIC_meta_attrib_code"\s+id="STATIC_meta_attrib_code">DUMMYCODE</textarea>!m' );
		WCBase::formfield_code( 'STATIC_meta_attrib_code', 'DUMMYCODE', 'TESTLABEL' );
	}

	public function test_static_formfield_select() {
		// TODO.
	}

	public function test_static_formlabel() {
		$this->expectOutputRegex( '?\<label class="" for="meta_attrib"\>TESTLABEL\</label\>?' );
		WCBase::formfield_label( 'meta_attrib', 'TESTVALUE', 'TESTLABEL' );
	}

	public function test_static_formfield_logo() {
		// TODO.
	}

	public function test_static_formfield_object() {
		$this->expectException( Exception::class );
		WCBase::formfield_object( 'EXPECTECEPTION' );
	}

	public function test_static_formfield_button() {
		// TODO.
	}

	public function test_static_formfield_nonce() {
		// TODO.
	}

}
