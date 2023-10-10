<?php
/*
Plugin Name: Clone Remote Content
Plugin URI: https://decodecms.com
Description: Clone remote content using API REST WordPress
Version: 1.2
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
Text Domain: clone-remote
Domain Path: languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace dcms\cloneremote;

use dcms\cloneremote\includes\Metabox;
use dcms\cloneremote\includes\Enqueue;
use dcms\cloneremote\includes\Process;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class to handle settings constants and loading files
 **/
final class Loader {

	// Define all the constants we need
	public function define_constants():void {
		define( 'DCMS_CLONE_VERSION', '1.0' );
		define( 'DCMS_CLONE_PATH', plugin_dir_path( __FILE__ ) );
		define( 'DCMS_CLONE_URL', plugin_dir_url( __FILE__ ) );
		define( 'DCMS_CLONE_BASE_NAME', plugin_basename( __FILE__ ) );
		define( 'DCMS_CLONE_SUBMENU', 'tools.php' );

		// User and password for remote site
		define( 'DCMS_REMOTE_URL' , 'http://decodecms2.local/wp-json/wp/v2/posts' );
		define( 'DCMS_REMOTE_CUSTOM_ENDPOINT', 'http://decodecms2.local/wp-json/dcms-custom-meta/v1/add/');
		define ( 'DCMS_REMOTE_SLUG', 'http://decodecms2.local/wp-json/wp/v2/posts/?slug=');
		define( 'DCMS_REMOTE_USER' , 'jmarreros');
		define( 'DCMS_REMOTE_PASSWORD' , 'HYvI l1mF m9fU kYE8 oCKd k3YI');
	}

	// Load all the files we need
	public function load_includes() :void{
		include_once( DCMS_CLONE_PATH . '/includes/metabox.php' );
		include_once( DCMS_CLONE_PATH . '/includes/enqueue.php' );
		include_once( DCMS_CLONE_PATH . '/includes/process.php' );
	}

	// Load tex domain
	public function load_domain():void {
		add_action( 'plugins_loaded', function () {
			$path_languages = dirname( DCMS_CLONE_BASE_NAME ) . '/languages/';
			load_plugin_textdomain( 'clone-remote', false, $path_languages );
		} );
	}


	// Initialize all
	public function init():void {
		$this->define_constants();
		$this->load_includes();
		$this->load_domain();
		new Metabox();
		new Enqueue();
		new Process();
	}
}

$dcms_clone_process = new Loader();
$dcms_clone_process->init();
