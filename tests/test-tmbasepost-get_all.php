<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpost.php';

class TMBasePostGetAllTest extends WP_UnitTestCase {

	public function test_get_all_empty() {
		$result = TMPost::get_all();
		$this->assertSame( $result, [] );
	}

	public function test_get_all_single() {
		$p      = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'tm_testpost',
			)
		);
		$result = TMPost::get_all();
		$this->assertSame( count( $result ), 1 );
		$this->assertInstanceOf( TMPost::class, $result[0] );
		$this->assertSame( $result[0]->title, 'Test Post 1' );
		$this->assertSame( $result[0]->ID, $p );
	}

	public function test_get_all_multiple() {
		$this->factory->post->create(
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
		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 3',
				'post_type'  => 'tm_testpost',
			)
		);
		$result = TMPost::get_all();
		$this->assertSame( count( $result ), 3 );
		$this->assertInstanceOf( TMPost::class, $result[0] );
		$this->assertInstanceOf( TMPost::class, $result[1] );
		$this->assertInstanceOf( TMPost::class, $result[2] );
	}
}
