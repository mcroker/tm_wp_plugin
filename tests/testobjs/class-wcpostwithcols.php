<?php

require_once 'class-wcpost.php';

class WCPostWithCols extends WCPost {
	protected static $post_type = 'testpost_withcols';

	protected static $meta_keys = array(
		'meta_attrib_2'            => array(
			'type'     => 'string',
			'meta_key' => 'meta_attrib_2',
			'postlist' => array(
				'title' => '2-meta_attrib',
				'index' => 2,
			),
		),
		'meta_attrib_1'            => array(
			'type'     => 'string',
			'meta_key' => 'meta_attrib_1',
			'postlist' => array(
				'title'    => '1-meta_attrib',
				'index'    => 1,
				'sortable' => true,
			),
		),
		'meta_attrib_nocontent'    => array(
			'type'     => 'string',
			'meta_key' => 'meta_attrib_nocontent',
			'postlist' => array(
				'title'    => 'nocontent-meta_attrib',
				'index'    => 3,
				'content'  => false,
				'sortable' => false,
				'filter'   => false,
			),
		),
		'meta_attrib_relatedpost'  => array(
			'type'      => 'related_post',
			'meta_key'  => 'meta_attrib_relatedpost',
			'classname' => 'WCPost',
			'postlist'  => array(
				'title'  => 'meta_attrib_relatedpost',
				'index'  => 4,
				'filter' => true,
			),
		),
		'meta_attrib_relatedpost2' => array(
			'type'      => 'related_post',
			'meta_key'  => 'meta_attrib_relatedpost2',
			'classname' => 'WCPost',
			'postlist'  => array(
				'title'  => 'meta_attrib_relatedpost2',
				'index'  => 5,
				'filter' => true,
			),
		),
		'meta_attrib_relatedtax'   => array(
			'type'      => 'related_tax',
			'meta_key'  => 'meta_attrib_relatedpost',
			'classname' => 'WCTax',
			'postlist'  => array(
				'title'  => 'meta_attrib_relatedtax',
				'index'  => 6,
				'filter' => false,
			),
		),
	);
}
