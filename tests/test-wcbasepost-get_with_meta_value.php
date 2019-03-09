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
		$p1                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'testpost',
			)
		);
		$p2                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 2',
				'post_type'  => 'testpost',
			)
		);
		$p3                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 3',
				'post_type'  => 'testpost',
			)
		);
		$p4                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 4',
			)
		);
		$obj1                     = new WCPost( $p1 );
		$obj1->meta_attrib_number = 50;
		$this->assertInstanceOf( WCPost::class, $obj1 );
		$obj2                     = new WCPost( $p2 );
		$obj2->meta_attrib_number = 50;
		$this->assertInstanceOf( WCPost::class, $obj2 );
		$obj3                     = new WCPost( $p3 );
		$obj3->meta_attrib_number = 10;
		$this->assertInstanceOf( WCPost::class, $obj3 );
		$obj4                     = new WCPost( $p4 );
		$obj4->meta_attrib_number = 50;
		$this->assertInstanceOf( WCPost::class, $obj3 );
		$result = WCPost::get_with_meta_value( 'meta_attrib_number', 50 );
		$this->assertSame( count( $result ), 2 );
		$this->assertInstanceOf( WCPost::class, $result[0] );
		$this->assertSame( $result[0]->ID, $p1 );
		$this->assertInstanceOf( WCPost::class, $result[1] );
		$this->assertSame( $result[1]->ID, $p2 );
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
