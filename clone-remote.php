<?php
/*
Plugin Name: Clone Remote Content
Plugin URI: https://decodecms.com
Description: Clone remote content using API REST WordPress
Version: 1.0
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
Text Domain: clone-remote
Domain Path: languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace dcms\cloneremote;

use dcms\cloneremote\includes\Plugin;
use dcms\cloneremote\includes\Submenu;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugin class to handle settings constants and loading files
 **/
final class Loader
{

	// Define all the constants we need
	public function define_constants()
	{
		define('DCMS_CLONE_VERSION', '1.0');
		define('DCMS_CLONE_PATH', plugin_dir_path(__FILE__));
		define('DCMS_CLONE_URL', plugin_dir_url(__FILE__));
		define('DCMS_CLONE_BASE_NAME', plugin_basename(__FILE__));
		define('DCMS_CLONE_SUBMENU', 'tools.php');
	}

	// Load all the files we need
	public function load_includes()
	{
		include_once(DCMS_CLONE_PATH . '/helpers/helper.php');
		include_once(DCMS_CLONE_PATH . '/includes/plugin.php');
		include_once(DCMS_CLONE_PATH . '/includes/submenu.php');
	}

	// Load tex domain
	public function load_domain()
	{
		add_action('plugins_loaded', function () {
			$path_languages = dirname(DCMS_CLONE_BASE_NAME) . '/languages/';
			load_plugin_textdomain('clone-remote', false, $path_languages);
		});
	}

	// Add link to plugin list
	public function add_link_plugin()
	{
		add_action('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
			return array_merge(array(
				'<a href="' . esc_url(admin_url(DCMS_CLONE_SUBMENU . '?page=clone-remote')) . '">' . __('Settings', 'clone-remote') . '</a>'
			), $links);
		});
	}

	// Initialize all
	public function init()
	{
		$this->define_constants();
		$this->load_includes();
		$this->load_domain();
		$this->add_link_plugin();
		new Plugin();
		new SubMenu();
	}
}

$dcms_clone_process = new Loader();
$dcms_clone_process->init();
