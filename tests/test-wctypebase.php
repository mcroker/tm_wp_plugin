<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypebase.php';

class WCTypeBaseChild extends WCTypeBase {
}

class WCTypeBaseTest extends WP_UnitTestCase {

	public function test_constructors_nometakey() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertSame( $mystring->get_meta_key(), 'mystring' );
	}

	public function test_constructors_withmetakey() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [ 'meta_key' => 'other_meta_key' ], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertSame( $mystring->get_meta_key(), 'other_meta_key' );
	}

	public function test_constructors_invalidsettings() {
		$parent   = new WCBase( 0 );
		$this->expectException( Exception::Class );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', 'INVALIDSETTINGS', 0 );
	}

	public function test_constructors_invalidparent() {
		$this->expectException( Exception::Class );
		$mystring = new WCTypeBaseChild( null, 'mystring', [], 0 );
	}

	public function test_constructors_invalidattribname() {
		$parent   = new WCBase( 0 );
		$this->expectException( Exception::Class );
		$mystring = new WCTypeBaseChild( $parent, null, [], 0 );
	}

	public function test_setget() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$mystring->set_value( '909' );
		$this->assertSame( '909', $mystring->get_value() );
	}

	public function test_getelemname() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertSame( 'WCBase_mystring', $mystring->get_elem_name() );
	}

	public function test_getformfieldsettings_nooptions() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertInternalType( 'array', $mystring->get_formfield_settings() );
		$this->assertsame( [ ], $mystring->get_formfield_settings() );
		$this->assertsame( [ 'setting1' => 'mark' ], $mystring->get_formfield_settings( ['setting1' => 'mark' ] ) );
	}

	public function test_getformfieldsettings_emptyformfield() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [ 'formfield' => [] ], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertInternalType( 'array', $mystring->get_formfield_settings() );
		$this->assertsame( [ ], $mystring->get_formfield_settings() );
		$this->assertsame( [ 'setting1' => 'mark' ], $mystring->get_formfield_settings( [ 'setting1' => 'mark' ] ) );
	}

	public function test_getformfieldsettings_setformfield() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [ 'formfield' => [ 'setting1' => 'bob' ] ], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertInternalType( 'array', $mystring->get_formfield_settings() );
		$this->assertsame( [ 'setting1' => 'bob' ], $mystring->get_formfield_settings() );
		$this->assertsame( [ 'setting1' => 'mark' ], $mystring->get_formfield_settings( [ 'setting1' => 'mark' ] ) );
	}

	public function test_getformfieldsettings_invalidarg() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectException( Exception::Class );
		$this->assertInternalType( 'array', $mystring->get_formfield_settings( 'INVALIDARG' ) );
	}

	public function test_gethtmlsettings_nooptions() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertInternalType( 'array', $mystring->get_html_settings() );
		$this->assertsame( [ 'labelclass' => '', 'valueclass' => '', 'inputclass' => '' ], $mystring->get_html_settings() );
		$this->assertsame( [ 'labelclass' => 'mark', 'valueclass' => '', 'inputclass' => '' ], $mystring->get_html_settings( [ 'labelclass' => 'mark' ] ) );
	}

	public function test_gethtmlsettings_setlabelclass() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [ 'html' => [ 'labelclass' => 'bob' ] ], 0 );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->assertInternalType( 'array', $mystring->get_html_settings() );
		$this->assertsame( [ 'labelclass' => 'bob', 'valueclass' => '', 'inputclass' => '' ], $mystring->get_html_settings() );
		$this->assertsame( [ 'labelclass' => 'mark', 'valueclass' => '', 'inputclass' => '' ], $mystring->get_html_settings( [ 'labelclass' => 'mark' ] ) );
	}

	public function test_gethtmlsettings_invalidarg() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectException( Exception::Class );
		$this->assertInternalType( 'array', $mystring->get_html_settings( 'INVALIDARG' ) );
	}

	public function test_formfield() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectOutputRegex( '!<input\s+class=""\s+type="text"\s+name="WCBase_mystring"\s+value="TESTVALUE"\s+id="WCBase_mystring"\s+/><br>!m' );
		$mystring->echo_formfield();
	}

	public function test_formfield_setclass() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectOutputRegex( '!<input\s+class="bob"\s+type="text"\s+name="WCBase_mystring"\s+value="TESTVALUE"\s+id="WCBase_mystring"\s+/><br>!m' );
		$mystring->echo_formfield( [ 'inputclass' => 'bob' ] );
	}

	public function test_echohtml() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectOutputString( 'TESTVALUE' );
		$mystring->echo_html();
	}

	public function test_echohtml_invalidarg() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectException( Exception::Class );
		$mystring->echo_html( 'INVALIDARG' );
	}

	public function test_echolabel_empty() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectOutputRegex( '!<label\s+class=""\s+for="WCBase_mystring"\s+>\s+mystring\s+</label>!m' );
		$mystring->echo_label();
	}

	public function test_echolabel_set() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [ 'label' => 'TESTLABEL' ], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectOutputRegex( '!<label\s+class=""\s+for="WCBase_mystring"\s+>\s+TESTLABEL\s+</label>!m' );
		$mystring->echo_label();
	}

	public function test_echolabel_invalidoptions() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectException( Exception::Class );
		$mystring->echo_label( 'string', 'INVALIDARG' );
	}

	public function test_echolabel_invalidlabel() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeBaseChild( $parent, 'mystring', [], 'TESTVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeBaseChild' ) );
		$this->expectException( Exception::Class );
		$mystring->echo_label( [], [] );
	}

}
