<?php

require_once 'class-tmpost.php';

class TMPostWithOverrides extends TMPost {
	protected static $post_type = 'tm_testpost_over';

	protected static $labels = array(
		'singular_name'      => 'TestPostWithOverrides',
		'slug'               => 'testpostwithoverrides',
		'menu_name'          => 'menu_name',
		'parent_item_colon'  => 'parent_item_colon',
		'all_items'          => 'all_items',
		'view_item'          => 'view_item',
		'add_new_item'       => 'add_new_item',
		'add_new'            => 'add_new',
		'edit_item'          => 'edit_item',
		'update_item'        => 'update_item',
		'search_items'       => 'search_items',
		'not_found'          => 'not_found',
		'not_found_in_trash' => 'not_found_in_trash',
	);

	protected static $args = array(
		'public' => false,
	);

}
