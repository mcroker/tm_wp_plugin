<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypecheck.php';

class WCTypeCheckTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent  = new WCBase( 0 );
		$mycheck = new WCTypeCheck( $parent, 'mycheck', [], 0 );
		$this->assertThat( $mycheck, $this->isInstanceOf( 'WCTypeCheck' ) );
	}

}
