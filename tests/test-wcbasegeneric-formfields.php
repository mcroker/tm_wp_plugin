<?php
/**
 * Class WCBaseGenericFormFieldsTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcbase.php';

class WCBaseGenericFormFieldsTest extends WP_UnitTestCase {

	/*
	public function test_static_formfield_string() {
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="STATIC_meta_attrib_string"\s+value="TESTVALUE"\s+id="STATIC_meta_attrib_string"\s+/><br>!m' );
		WCBase::formfield_string( 'STATIC_meta_attrib_string', 'TESTVALUE', 'TESTLABEL' );
	}
	*/

	public function test_formfield_string() {
		$base                     = new WCBase( 0 );
		$base->meta_attrib_string = 'TESTVALUE';
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="WCBase_meta_attrib_string"\s+value="TESTVALUE"\s+id="WCBase_meta_attrib_string"\s+/><br>!m' );
		$base->echo_formfield( 'meta_attrib_string' );
	}

	public function test_formfield_time() {
		$base                   = new WCBase( 0 );
		$base->meta_attrib_time = new DateTime( '01:23' );
		$this->expectOutputRegex( '!<input\s+class=""\s+type="datetime-local"\s+name="WCBase_meta_attrib_time"\s+id="WCBase_meta_attrib_time"\s+value="UTC01:23"\s+/>!m' );
		$base->echo_formfield( 'meta_attrib_time' );
	}

	public function test_formfield_date() {
		$base                   = new WCBase( 0 );
		$base->meta_attrib_date = '1976-05-06';
		$this->expectOutputRegex( '!<input class=""\s+type="datetime-local"\s+name="WCBase_meta_attrib_date"\s+id="WCBase_meta_attrib_date"\s+value="1976-05-06T00:00"\s+/>!m' );
		$base->echo_formfield( 'meta_attrib_date' );
	}

	public function test_formfield_check_false() {
		$base                    = new WCBase( 0 );
		$base->meta_attrib_check = false;
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="WCBase_meta_attrib_check"\s+id="WCBase_meta_attrib_check"\s+/>!' );
		$base->echo_formfield( 'meta_attrib_check' );
	}

	public function test_formfield_check_true() {
		$base                    = new WCBase( 0 );
		$base->meta_attrib_check = true;
		$this->expectOutputRegex( '!<input class=""\s+type="checkbox"\s+name="WCBase_meta_attrib_check"\s+id="WCBase_meta_attrib_check"\s+checked=\'checked\'\s+/>!' );
		$base->echo_formfield( 'meta_attrib_check' );
	}

	public function test_formfield_number() {
		$base                     = new WCBase( 0 );
		$base->meta_attrib_number = 123;
		$this->expectOutputRegex( '!<input class=""\s+type="number"\s+name="WCBase_meta_attrib_number"\s+id="WCBase_meta_attrib_number"\s+value="123"\s+/>!m' );
		$base->echo_formfield( 'meta_attrib_number' );
	}

	public function test_formfield_text() {
		$base                   = new WCBase( 0 );
		$base->meta_attrib_text = "DUMMYTEXT";
		$this->expectOutputRegex( '!<textarea class="wp-editor-area!m' );
		$base->echo_formfield( 'meta_attrib_text' );
	}

	public function test_formfield_code() {
		$base                   = new WCBase( 0 );
		$base->meta_attrib_code = "DUMMYCODE";
		$this->expectOutputRegex( '!<textarea\s+class=""\s+style="width:100%"\s+rows=15\s+name="WCBase_meta_attrib_code"\s+id="WCBase_meta_attrib_code"\s*>DUMMYCODE</textarea>!m' );
		$base->echo_formfield( 'meta_attrib_code' );
	}

	public function test_static_formfield_select() {
		// TODO.
	}

	/*
	 public function test_static_formlabel() {
		$this->expectOutputRegex( '?\<label class="" for="meta_attrib"\>TESTLABEL\</label\>?' );
		WCBase::formfield_label( 'meta_attrib', 'TESTVALUE', 'TESTLABEL' );
	}
	*/

	public function test_formfield_object() {
		$base = new WCBase( 0 );
		$this->expectOutputRegex( '!<div class="">\s+</div>!m' );
		$base->echo_formfield( 'meta_attrib_object' );
	}

	public function test_static_formfield_button() {
		// TODO.
	}

	public function test_static_formfield_nonce() {
		// TODO.
	}

}
