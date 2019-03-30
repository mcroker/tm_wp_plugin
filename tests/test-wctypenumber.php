<?php
/**
 * Class WCBaseGenericSettersTest
 *
 * @package Tm_wp_plugin
 */

class WCTypeNumberTest extends WP_UnitTestCase {

	public function test_constructors() {
		$mynumber = new WCTypeNumber( 'Parent', 'mynumber', [], 0 );
		$this->assertThat( $mynumber, $this->isInstanceOf( 'WCTypeNumber' ) );
	}

	public function test_attrib_number() {
		$mynumber = new WCTypeNumber( 'Parent', 'mynumber', [], 0 );
		$this->assertThat( $mynumber, $this->isInstanceOf( 'WCTypeNumber' ) );
		$mynumber->set_value( 304 );
		$this->assertSame( $mynumber->get_value(), 304 );
	}

	public function test_attrib_number_fail() {
		$mynumber = new WCTypeNumber( 'Parent', 'mynumber', [], 0 );
		$this->assertThat( $mynumber, $this->isInstanceOf( 'WCTypeNumber' ) );
		$this->expectException( Exception::Class );
		$mynumber->set_value( 'STRING' );
	}

}
