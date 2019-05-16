<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctypetext.php';

class WCTypeTextTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent = new WCBase( 0 );
		$mytext = new WCTypeText( $parent, 'mytext', [], 0 );
		$this->assertThat( $mytext, $this->isInstanceOf( 'WCTypeText' ) );
	}

}
