<?php

/**
* Tested up to:        5.6.2
* Domain Path:         /languages
* Text Domain:         wil-featured-block
* License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
* License:             GPL-2.0+
* Author URI:          https://wiloke.com
* Author:              wiloke
* Version:             1.0.0
* Description:         Wiloke Featured Block Addon for Elementor
* Plugin URI:          https://wiloke.com
* Plugin Name:         Wiloke Featured Block for Elementor
*/

define("WILOKE_WILOKEFEATUREDBLOCK_VERSION", uniqid());
define("WILOKE_WILOKEFEATUREDBLOCK_NAMESPACE", "wil-featured-block");
define("WILOKE_WILOKEFEATUREDBLOCK_VIEWS_PATH", plugin_dir_path(__FILE__));
define("WILOKE_WILOKEFEATUREDBLOCK_VIEWS_URL", plugin_dir_url(__FILE__));


add_action("plugins_loaded", "loadPluginDomain");
if (!function_exists("loadPluginDomain")) {
	function loadPluginDomain()
	{
		load_plugin_textdomain("wil-featured-block", false, plugin_dir_path(__FILE__) . "languages");
	}
}

require_once plugin_dir_path(__FILE__) . "vendor/autoload.php";

new \WilokeFeaturedBlock\Controllers\RegistrationController();
new \WilokeFeaturedBlock\Controllers\HandleAjaxController();