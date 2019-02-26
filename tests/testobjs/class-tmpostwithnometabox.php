<?php

require_once 'class-tmpost.php';

class TMPostWithNoMetaBox extends TMPost {
	protected static $post_type = 'tm_testpost_nobox';

	protected static $tmargs = array(
		'create_metadatabox' => false,
	);

}
