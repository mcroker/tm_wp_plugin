<?php
/**
 * Class SampleTest
 *
 * @package Tm_wp_plugin
 */

require_once 'testobjs/class-wcpostwithcols.php';
require_once 'testobjs/class-wcpostwithinvalidfilter.php';
require_once 'testobjs/class-wcpost.php';

class WCBasePostRestrictManagePostsTest extends WP_UnitTestCase {

	public function test_restrict_manage_posts_emptypost() {
		global $_GET;
		$backup_GET        = $_GET;
		$_GET['post_type'] = 'testpost_withcols';
		WCPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;
		$this->expectOutputRegex( '!<select name="WCPostWithCols_meta_attrib_relatedpost">\s+<option value="">Filter by meta_attrib_relatedpost:</option>\s+</select>!m' );
	}

	public function test_restrict_manage_posts_singlepost() {
		global $_GET;
		$backup_GET = $_GET;

		$this->factory->post->create(
			array(
				'post_title' => 'Test Post',
				'post_type'  => 'testpost',
			)
		);

		$_GET['post_type'] = 'testpost_withcols';
		WCPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;

		$this->expectOutputRegex( '!<select name="WCPostWithCols_meta_attrib_relatedpost">\s+<option value="">Filter by meta_attrib_relatedpost:</option>\s+<option value="[0-9]+"\s*>Test Post</option>\s+</select>!m' );
	}

	public function test_restrict_manage_posts_multiplepost() {
		global $_GET;
		$backup_GET = $_GET;

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

		$_GET['post_type'] = 'testpost_withcols';
		WCPostWithCols::restrict_manage_posts();
		$_GET = $backup_GET;

		$this->expectOutputRegex( '!<select name="WCPostWithCols_meta_attrib_relatedpost">\s+<option value="">Filter by meta_attrib_relatedpost:</option>\s+(<option value="[0-9]+">Test Post [0-9]+</option>\s*)+\s+</select>!m' );
	}

	public function test_restrict_manage_posts_selectedpost() {
		global $_GET;
		$backup_GET = $_GET;

		$author1 = $this->factory->user->create_and_get( array( 'user_login' => 'jdoe', 'user_pass' => NULL, 'role' => 'author' ));
		$this->assertTrue( 0 !== $author1->ID );
 		wp_set_current_user( $author1->ID );

		$this->factory->post->create(
			array(
				'post_title' => 'Test Post 1',
				'post_type'  => 'testpost',
			)
		);
		$p = $this->factory->post->create(
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

		$_GET['post_type']                              = 'testpost_withcols';
		$_GET['WCPostWithCols_meta_attrib_relatedpost'] = $p;

		$_GET['WCPostWithCols_default_field_nonce']     = wp_create_nonce( 'WCPostWithCols_default_nonce' );

		WCPostWithCols::restrict_manage_posts();
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

		$_GET['post_type'] = 'testpost_withinvalidfilter';
		$this->expectException( Exception::class );
		$this->expectOutputRegex( '!<select name="WCPostWithInvalidFilter_meta_attrib_1">!m' );
		WCPostWithInvalidFilter::restrict_manage_posts();
		$_GET = $backup_GET;
	}
}
