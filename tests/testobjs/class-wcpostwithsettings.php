<?php

require_once 'class-wcpost.php';

class WCPostWithSettings extends WCPost {
	protected static $post_type = 'testpost_settings';

	protected static $setting_keys = array(
		'key',
	);

}
