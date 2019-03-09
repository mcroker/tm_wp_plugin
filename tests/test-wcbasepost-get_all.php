<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpost.php';

class WCBasePostGetAllTest extends WP_UnitTestCase {

	public function test_get_all_empty() {
		$result = WCPost::get_all();
		$this->assertSame( $result, [] );
	}

	public function test_get_all_single() {
		$p      = $this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'testpost',
			)
		);
		$result = WCPost::get_all();
		$this->assertSame( count( $result ), 1 );
		$this->assertInstanceOf( WCPost::class, $result[0] );
		$this->assertSame( $result[0]->title, 'Test Post 1' );
		$this->assertSame( $result[0]->ID, $p );
	}

	public function test_get_all_multiple() {
		$this->factory->post->create(
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
		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 3',
				'post_type'  => 'testpost',
			)
		);
		$result = WCPost::get_all();
		$this->assertSame( count( $result ), 3 );
		$this->assertInstanceOf( WCPost::class, $result[0] );
		$this->assertInstanceOf( WCPost::class, $result[1] );
		$this->assertInstanceOf( WCPost::class, $result[2] );
	}
}
