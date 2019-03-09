<?php

require_once 'class-wcpost.php';

class WCPostWithNoMetaBox extends WCPost {
	protected static $post_type = 'testpost_nobox';

	protected static $wcargs = array(
		'create_metadatabox' => false,
	);

}
