<?php
/**
 * Class WCTypeBaseTest
 *
 * @package Tm_wp_plugin
 */

require_once __DIR__ . '/../types/class-wctyperelatedpost.php';

class WCTypeRelatedPostTest extends WP_UnitTestCase {

	public function test_constructors() {
		$parent    = new WCBase( 0 );
		$myrelpost = new WCTypeRelatedPost( $parent, 'myrelpost', [], 0 );
		$this->assertThat( $myrelpost, $this->isInstanceOf( 'WCTypeRelatedPost' ) );
	}

}
