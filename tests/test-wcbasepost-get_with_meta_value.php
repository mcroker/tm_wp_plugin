<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpost.php';

class WCBasePostGetWithMetaValueTest extends WP_UnitTestCase {

	public function test_get_with_meta_value_empty() {
		$result = WCPost::get_with_meta_value( 'dummy', 'dummy' );
		$this->assertSame( $result, [] );
	}

	public function test_get_with_meta_value_nomatch() {
		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'testpost',
			)
		);
		$result = WCPost::get_with_meta_value( 'dummy', 'dummy' );
		$this->assertSame( $result, [] );
	}

	public function test_get_with_meta_value_singlematch() {
		$p = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'testpost',
			)
		);
		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 2',
				'post_type'  => 'testpost',
			)
		);
		$obj = new WCPost( $p );
		$this->assertInstanceOf( WCPost::class, $obj );
		$obj->meta_attrib_number = 50;
		$result                  = WCPost::get_with_meta_value( 'meta_attrib_number', 50 );
		$this->assertSame( count( $result ), 1 );
		$this->assertInstanceOf( WCPost::class, $result[0] );
		$this->assertSame( $result[0]->title, 'Test Post 1' );
		$this->assertSame( $result[0]->ID, $p );
	}

	public function test_get_with_meta_value_multiplematch() {

		$count_no = 5;
		$count_yes = 3;

		$p_no = [];
		for ($p = 1; $p <= $count_no; $p++) {
			$wpobj = $this->factory->post->create(
				array(
					'post_title' => 'Test Post No ' . $p,
					'post_type'  => 'testpost',
				)
			);
			$p_no[$p]               = $wpobj;
		};

		$p_yes = [];
		for ($p = 1; $p <= $count_yes; $p++) {
			$wpobj = $this->factory->post->create(
				array(
					'post_title' => 'Test Post Yes ' . $p,
					'post_type'  => 'testpost',
				)
			);
			$p_yes[$p]               = $wpobj;
			$obj                     =  new WCPost( $wpobj );
		    $this->assertInstanceOf( WCPost::class, $obj );
			$obj->meta_attrib_number = 50;
		};

		$result = WCPost::get_with_meta_value( 'meta_attrib_number', 50 );

		$this->assertSame( count( $result ), $count_yes );
		foreach ( $result as $r ) {
			$this->assertInstanceOf( WCPost::class, $r );
			$this->assertContains( $r->ID, $p_yes );
			// Remove from list of yes's so we match exactly once.
			if (($key = array_search($r->ID, $p_yes)) !== false) {
    			unset($p_yes[$key]);
			}
		}
	}

	public function test_get_with_meta_value_onlyposttype() {
		$p1                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'testpost',
			)
		);
		$p2                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 2',
			)
		);
		$obj1                     = new WCPost( $p1 );
		$obj1->meta_attrib_number = 50;
		$this->assertInstanceOf( WCPost::class, $obj1 );
		$obj2                     = new WCPost( $p2 );
		$obj2->meta_attrib_number = 50;
		$this->assertInstanceOf( WCPost::class, $obj2 );
		$result = WCPost::get_with_meta_value( 'meta_attrib_number', 50 );
		$this->assertSame( count( $result ), 1 );
		$this->assertInstanceOf( WCPost::class, $result[0] );
		$this->assertSame( $result[0]->ID, $p1 );
	}
}
