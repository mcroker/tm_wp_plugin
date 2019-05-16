<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypestring.php';

class WCTypeStringTest extends WP_UnitTestCase {

	public function test_constructor_invalidstring() {
		$parent   = new WCBase( 0 );
		$this->expectException( Exception::Class );
		$mystring = new WCTypeString( $parent, 'mystring', [], 0 );
	}

	public function test_setget() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeString( $parent, 'mystring', [], 'STRINGVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeString' ) );
		$this->assertSame( 'STRINGVALUE', $mystring->get_value() );
		$mystring->set_value( '909' );
		$this->assertSame( '909', $mystring->get_value() );
	}

	public function test_setget_invalidstring() {
		$parent   = new WCBase( 0 );
		$mystring = new WCTypeString( $parent, 'mystring', [], 'STRINGVALUE' );
		$this->assertThat( $mystring, $this->isInstanceOf( 'WCTypeString' ) );
		$this->expectException( Exception::Class );
		$mystring->set_value( 909 );
	}

}
