<?php

require_once 'class-wcpost.php';

class WCPostWithInvalidFilter extends WCPost {
	protected static $post_type = 'testpost_withinvalidfilter';

	protected static $meta_keys = array(
		'meta_attrib_1' => array(
			'type'     => 'string',
			'meta_key' => 'meta_attrib_1',
			'postlist' => array(
				'title'  => '1-meta_attrib',
				'index'  => 1,
				'filter' => true,
			),
		),
	);
}
