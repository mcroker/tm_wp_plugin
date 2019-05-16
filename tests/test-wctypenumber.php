<?php
/**
 * Class WCBaseGenericSettersTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypenumber.php';

class WCTypeNumberTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent   = new WCBase( 0 );
		$mynumber = new WCTypeNumber( $parent, 'mynumber', [], 0 );
		$this->assertThat( $mynumber, $this->isInstanceOf( 'WCTypeNumber' ) );
	}

	public function test_attrib_number() {
		$parent   = new WCBase( 0 );
		$mynumber = new WCTypeNumber( $parent, 'mynumber', [], 0 );
		$this->assertThat( $mynumber, $this->isInstanceOf( 'WCTypeNumber' ) );
		$mynumber->set_value( 304 );
		$this->assertSame( $mynumber->get_value(), 304 );
	}

	public function test_attrib_number_fail() {
		$parent   = new WCBase( 0 );
		$mynumber = new WCTypeNumber( $parent, 'mynumber', [], 0 );
		$this->assertThat( $mynumber, $this->isInstanceOf( 'WCTypeNumber' ) );
		$this->expectException( Exception::Class );
		$mynumber->set_value( 'STRING' );
	}

}
