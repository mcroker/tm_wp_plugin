<?php

require_once 'class-tmpost.php';

class TMPostWithSettings extends TMPost {
	protected static $post_type = 'tm_testpost_settings';

	protected static $setting_keys = array(
		'key',
	);

}
