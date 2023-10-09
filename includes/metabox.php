<?php

namespace dcms\cloneremote\includes;

class Metabox {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'add_clone_remote_box' ] );
	}

	public function add_clone_remote_box(): void {
		add_meta_box(
			'clone_remote_box_id',
			'Clone remote content',
			[ $this, 'clone_remote_box_html' ],
			'post',
			'side'
		);

		wp_enqueue_script( 'clone-remote-script' );
		wp_enqueue_style( 'clone-remote-style' );
	}

	public function clone_remote_box_html(): void {
        global $post;

		$post_id = $post->ID;
        $status = $post->post_status;

		if ( $post_id && $status === 'publish'):
			?>
            <button class="button primary-button" id="clone-remote-content" name="clone-remote-content">Clone remote
                content
            </button>
            <input type="hidden" name="current-id" id="current-id" value="<?= $post_id ?>">
            <div style="margin-top:5px" id="clone-remote-message"></div>
		<?php
		else :
			?>
            <div>Save the post before cloning remote content</div>
		<?php
		endif;
	}
}
