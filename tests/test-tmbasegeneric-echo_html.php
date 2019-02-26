<?php
/**
 * Class TMBaseGenericFormFieldsTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmbase.php';

class TMBaseGenericEchoHtmlTest extends WP_UnitTestCase {

	public function test_echo_html_string() {
		$base                     = new TMBase( 0 );
		$base->meta_attrib_string = 'TESTVALUE';
		$this->expectOutputString( 'TESTVALUE' );
		$base->echo_html( 'meta_attrib_string' );
	}

}
