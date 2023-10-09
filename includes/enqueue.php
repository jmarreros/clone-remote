<?php

namespace dcms\cloneremote\includes;

/**
 * Class for enqueue javascript and styles files in WordPress
 */
class Enqueue {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts_backend' ] );
	}

	// Backend scripts
	public function register_scripts_backend():void {

		// Javascript
		wp_register_script( 'clone-remote-script',
			DCMS_CLONE_URL . '/assets/script.js',
			[ 'jquery' ],
			DCMS_CLONE_VERSION,
			true );

		wp_localize_script( 'clone-remote-script',
			'dcms_clone_remote',
			[
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ajax-nonce-clone-remote' ),
				'sending'    => __( 'Enviando...', 'clone-remote' ),
				'processing' => __( 'Procesando...', 'clone-remote' )
			] );


		// CSS
		wp_register_style( 'clone-remote-style',
			DCMS_CLONE_URL . '/assets/style.css',
			[],
			DCMS_CLONE_VERSION );

	}

}
