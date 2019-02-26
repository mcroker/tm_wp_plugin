<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpost.php';

class TMBasePostPostColumnsSortTest extends WP_UnitTestCase {

	private $meta1 = array(
		'postlist' => array(
			'title' => 'META1',
			'index' => 1,
		),
	);

	private $meta2 = array(
		'postlist' => array(
			'title' => 'META2',
			'index' => 2,
		),
	);

	private $metaERROR = array(
		'postlist' => 'STRING',
	);

	private $metaNOINDEX = array(
		'postlist' => array(
			'title' => 'METANOINDEX',
		),
	);

	private $metaSTRINGINDEX = array(
		'postlist' => array(
			'title' => 'STRINGINDEX',
			'index' => 'STRING',
		),
	);

	private $metaNULL = [];

	public function test_1_lt_2() {
		$result = TMBasePost::post_columns_sort( $this->meta1, $this->meta2 );
		$this->assertSame( -1, $result );
	}

	public function test_2_gt_1() {
		$result = TMBasePost::post_columns_sort( $this->meta2, $this->meta1 );
		$this->assertSame( 1, $result );
	}

	public function test_1_eq_1() {
		$result = TMBasePost::post_columns_sort( $this->meta1, $this->meta1 );
		$this->assertSame( 0, $result );
	}

	public function test_noindex_gt_2() {
		$result = TMBasePost::post_columns_sort( $this->metaNOINDEX, $this->meta2 );
		$this->assertSame( 1, $result );
	}

	public function test_null_gt_noindex() {
		$result = TMBasePost::post_columns_sort( $this->metaNULL, $this->metaNOINDEX );
		$this->assertSame( 1, $result );
	}

	public function test_stingindex_gt_2() {
		$result = TMBasePost::post_columns_sort( $this->metaSTRINGINDEX, $this->meta2 );
		$this->assertSame( 1, $result );
	}

	public function test_error_gt_2() {
		$result = TMBasePost::post_columns_sort( $this->metaERROR, $this->meta2 );
		$this->assertSame( 1, $result );
	}

	public function test_string_gt_2() {
		$result = TMBasePost::post_columns_sort( 'STRING', $this->meta2 );
		$this->assertSame( 1, $result );
	}

	public function test_2_lt_noindex() {
		$result = TMBasePost::post_columns_sort( $this->meta2, $this->metaNOINDEX );
		$this->assertSame( -1, $result );
	}

	public function test_2_lt_null() {
		$result = TMBasePost::post_columns_sort( $this->meta2, $this->metaNULL );
		$this->assertSame( -1, $result );
	}

	public function test_2_lt_stingindex() {
		$result = TMBasePost::post_columns_sort( $this->meta2, $this->metaSTRINGINDEX );
		$this->assertSame( -1, $result );
	}

	public function test_2_lt_error() {
		$result = TMBasePost::post_columns_sort( $this->meta2, $this->metaERROR );
		$this->assertSame( -1, $result );
	}

	public function test_2_lt_string() {
		$result = TMBasePost::post_columns_sort( $this->meta2, 'STRING' );
		$this->assertSame( -1, $result );
	}

}
