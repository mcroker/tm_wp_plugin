<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypeobject.php';

class WCTypeObjectTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent   = new WCBase( 0 );
		$myobject = new WCTypeObject( $parent, 'myobject', [], 0 );
		$this->assertThat( $myobject, $this->isInstanceOf( 'WCTypeObject' ) );
	}

}
