<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-tmpostwithcols.php';
require_once 'testobjs/class-tmpostwithinvalidfilter.php';
require_once 'testobjs/class-tmpost.php';

class TMBasePostRestrictManagePostsTest extends WP_UnitTestCase {

	public function test_restrict_manage_posts_emptypost() {
		global $_GET;
		$backup_GET        = $_GET;
		$_GET['post_type'] = 'tm_testpost_withcols';
		TMPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;
		$this->expectOutputRegex( '!<select name="TMPostWithCols_meta_attrib_relatedpost">\s+<option value="">Filter by meta_attrib_relatedpost:</option>\s+</select>!m' );
	}

	public function test_restrict_manage_posts_singlepost() {
		global $_GET;
		$backup_GET = $_GET;

		$this->factory->post->create(
			array(
				'post_title' => 'Test Post',
				'post_type'  => 'tm_testpost',
			)
		);

		$_GET['post_type'] = 'tm_testpost_withcols';
		TMPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;

		$this->expectOutputRegex( '!<select name="TMPostWithCols_meta_attrib_relatedpost">\s+<option value="">Filter by meta_attrib_relatedpost:</option>\s+<option value="[0-9]+"\s*>Test Post</option>\s+</select>!m' );
	}

	public function test_restrict_manage_posts_multiplepost() {
		global $_GET;
		$backup_GET = $_GET;

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

		$_GET['post_type'] = 'tm_testpost_withcols';
		TMPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;

		$this->expectOutputRegex( '!<select name="TMPostWithCols_meta_attrib_relatedpost">\s+<option value="">Filter by meta_attrib_relatedpost:</option>\s+(<option value="[0-9]+">Test Post [0-9]+</option>\s*)+\s+</select>!m' );
	}

	public function test_restrict_manage_posts_selectedpost() {
		global $_GET;
		$backup_GET = $_GET;

		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'tm_testpost',
			)
		);
		$p = $this->factory->post->create(
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

		$_GET['post_type']                              = 'tm_testpost_withcols';
		$_GET['TMPostWithCols_meta_attrib_relatedpost'] = $p;
		TMPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;

		$this->expectOutputRegex( '!<option value="[0-9]+" selected=\'selected\'>Test Post 2</option>!m' );
	}

	public function test_restrict_manage_posts_singletax() {
		// TODO.
	}

	public function test_restrict_manage_posts_mutlipletax() {
		// TODO.
	}

	public function test_restrict_manage_posts_selectedtax() {
		// TODO.
	}

	public function test_restrict_manage_posts_invalidfilter() {
		global $_GET;
		$backup_GET = $_GET;

		$_GET['post_type'] = 'tm_testpost_withinvalidfilter';
		$this->expectException( Exception::class );
		$this->expectOutputRegex( '!<select name="TMPostWithInvalidFilter_meta_attrib_1">!m' );
		TMPostWithInvalidFilter::restrict_manage_posts();
		$_GET = $backup_GET;
	}
}
