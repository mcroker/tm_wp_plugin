<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypedate.php';

class WCTypeDateTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent = new WCBase( 0 );
		$mydate = new WCTypeDate( $parent, 'mydate', [], 0 );
		$this->assertThat( $mydate, $this->isInstanceOf( 'WCTypeDate' ) );
	}

}
