<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpost.php';

class TMBasePostGetWithMetaValueTest extends WP_UnitTestCase {

	public function test_get_with_meta_value_empty() {
		$result = TMPost::get_with_meta_value( 'dummy', 'dummy' );
		$this->assertSame( $result, [] );
	}

	public function test_get_with_meta_value_nomatch() {
		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'tm_testpost',
			)
		);
		$result = TMPost::get_with_meta_value( 'dummy', 'dummy' );
		$this->assertSame( $result, [] );
	}

	public function test_get_with_meta_value_singlematch() {
		$p = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'tm_testpost',
			)
		);
		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 2',
				'post_type'  => 'tm_testpost',
			)
		);
		$obj = new TMPost( $p );
		$this->assertInstanceOf( TMPost::class, $obj );
		$obj->meta_attrib_number = 50;
		$result                  = TMPost::get_with_meta_value( 'meta_attrib_number', 50 );
		$this->assertSame( count( $result ), 1 );
		$this->assertInstanceOf( TMPost::class, $result[0] );
		$this->assertSame( $result[0]->title, 'Test Post 1' );
		$this->assertSame( $result[0]->ID, $p );
	}

	public function test_get_with_meta_value_multiplematch() {
		$p1                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'tm_testpost',
			)
		);
		$p2                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 2',
				'post_type'  => 'tm_testpost',
			)
		);
		$p3                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 3',
				'post_type'  => 'tm_testpost',
			)
		);
		$p4                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 4',
			)
		);
		$obj1                     = new TMPost( $p1 );
		$obj1->meta_attrib_number = 50;
		$this->assertInstanceOf( TMPost::class, $obj1 );
		$obj2                     = new TMPost( $p2 );
		$obj2->meta_attrib_number = 50;
		$this->assertInstanceOf( TMPost::class, $obj2 );
		$obj3                     = new TMPost( $p3 );
		$obj3->meta_attrib_number = 10;
		$this->assertInstanceOf( TMPost::class, $obj3 );
		$obj4                     = new TMPost( $p4 );
		$obj4->meta_attrib_number = 50;
		$this->assertInstanceOf( TMPost::class, $obj3 );
		$result = TMPost::get_with_meta_value( 'meta_attrib_number', 50 );
		$this->assertSame( count( $result ), 2 );
		$this->assertInstanceOf( TMPost::class, $result[0] );
		$this->assertSame( $result[0]->ID, $p1 );
		$this->assertInstanceOf( TMPost::class, $result[1] );
		$this->assertSame( $result[1]->ID, $p2 );
	}

	public function test_get_with_meta_value_onlyposttype() {
		$p1                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'tm_testpost',
			)
		);
		$p2                       = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 2',
			)
		);
		$obj1                     = new TMPost( $p1 );
		$obj1->meta_attrib_number = 50;
		$this->assertInstanceOf( TMPost::class, $obj1 );
		$obj2                     = new TMPost( $p2 );
		$obj2->meta_attrib_number = 50;
		$this->assertInstanceOf( TMPost::class, $obj2 );
		$result = TMPost::get_with_meta_value( 'meta_attrib_number', 50 );
		$this->assertSame( count( $result ), 1 );
		$this->assertInstanceOf( TMPost::class, $result[0] );
		$this->assertSame( $result[0]->ID, $p1 );
	}
}
