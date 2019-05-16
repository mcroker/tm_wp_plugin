<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypecode.php';

class WCTypeCodeTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent = new WCBase( 0 );
		$mycode = new WCTypeCode(  $parent, 'mycode', [], 0 );
		$this->assertThat( $mycode, $this->isInstanceOf( 'WCTypeCode' ) );
	}

}
