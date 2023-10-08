<?php

namespace dcms\cloneremote\includes;

class Process {

	public function __construct() {
		add_action( 'wp_ajax_dcms_ajax_remote_content', [ $this, 'process_remote_content' ] );
	}

	public function process_remote_content(): void {
		$res = [
			'status'  => 1,
			'message' => 'OK'
		];

		wp_send_json( $res );
	}
}
