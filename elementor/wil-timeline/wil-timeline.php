<?php

/**
* Tested up to:        5.6.2
* Domain Path:         /languages
* Text Domain:         wil-timeline
* License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
* License:             GPL-2.0+
* Author URI:          https://wiloke.com
* Author:              wiloke
* Version:             1.0.0
* Description:         Wiloke Timeline Addon for Elementor
* Plugin URI:          https://wiloke.com
* Plugin Name:         Wiloke Timeline for Elementor
*/

define("WILOKE_WILOKETIMELINE_VERSION", uniqid());
define("WILOKE_WILOKETIMELINE_NAMESPACE", "wil-timeline");
define("WILOKE_WILOKETIMELINE_PREFIX", "wil-timeline_");
define("WILOKE_WILOKETIMELINE_VIEWS_PATH", plugin_dir_path(__FILE__));
define("WILOKE_WILOKETIMELINE_VIEWS_URL", plugin_dir_url(__FILE__));


add_action("plugins_loaded", "loadPluginDomain");
if (!function_exists("loadPluginDomain")) {
	function loadPluginDomain()
	{
		load_plugin_textdomain("wil-timeline", false, plugin_dir_path(__FILE__) . "languages");
	}
}

require_once plugin_dir_path(__FILE__) . "vendor/autoload.php";

new \WilokeTimeline\Controllers\RegistrationController();
new \WilokeTimeline\Controllers\HandleAjaxController();