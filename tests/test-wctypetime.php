<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypetime.php';

class WCTypeTimeTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent = new WCBase( 0 );
		$mytime = new WCTypeTime( $parent, 'mytime', [], 0 );
		$this->assertThat( $mytime, $this->isInstanceOf( 'WCTypeTime' ) );
	}

}
