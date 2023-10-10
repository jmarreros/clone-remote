<?php

namespace dcms\cloneremote\includes;

class Process {

	public function __construct() {
		add_action( 'wp_ajax_dcms_ajax_remote_content', [ $this, 'process_remote_content' ] );
	}

	public function process_remote_content(): void {
		$login    = DCMS_REMOTE_USER;
		$password = DCMS_REMOTE_PASSWORD;

		$post_id = $_POST['post_id'] ?? 0;
		$nonce   = $_POST['nonce'] ?? '';

		$this->validate_post_id( $post_id );
		$this->validate_nonce( $nonce );

		$post = get_post( $post_id );
		$this->validate_duplicate_slug( $post->post_name );

		$request = wp_remote_post(
			DCMS_REMOTE_URL,
			[
				'headers' => [ 'Authorization' => 'Basic ' . base64_encode( "$login:$password" ) ],
				'body'    => [
					'title'      => $post->post_title,
					'slug'       => $post->post_name,
					'content'    => $post->post_content,
					'author'     => 2,
					'status'     => 'draft',
					'categories' => wp_get_post_categories( $post_id, [ 'fields' => 'ids' ] ),
					'tags'       => wp_get_post_tags( $post_id, [ 'fields' => 'ids' ] ),
					'meta'       => $this->get_meta_data( $post_id )
				]
			]
		);

		$body = json_decode( wp_remote_retrieve_body( $request ) );

		// Error creating post
		if ( 'Created' !== wp_remote_retrieve_response_message( $request ) ) {
			wp_send_json( [ 'status' => 0, 'message' => $body->message ] );
		}

		// All right, then create custom post meta
		$post_id_created = $body->id;
		$request_meta    = wp_remote_post( DCMS_REMOTE_CUSTOM_ENDPOINT,
			[
				'body' => [
					'post_id'                   => $post_id_created,
					'genesis_custom_post_class' => get_post_meta( $post_id, '_genesis_custom_post_class', true ),
					'dcms_eufi_img'             => get_post_meta( $post_id, '_dcms_eufi_img', true ),
					'dcms_eufi_alt'             => get_post_meta( $post_id, '_dcms_eufi_alt', true ),
				]
			]
		);

		if ( is_wp_error( $request_meta ) ) {
			wp_send_json( [
				'status'  => 0,
				'message' => 'Error creating metadata ' . $request_meta->get_error_message()
			] );
		}

		wp_send_json( [ 'status' => 1, 'message' => 'Article created' ] );
	}

	private function get_meta_data( $post_id ): array {
		$meta_custom = [
			"Nivel",
			"relacionados",
			"youtube",
		];

		$meta_data = [];
		$meta_keys = get_post_custom_keys( $post_id );
		foreach ( $meta_keys as $meta_key ) {
			if ( in_array( $meta_key, $meta_custom ) ) {
				$meta_data[ $meta_key ] = get_post_meta( $post_id, $meta_key, true );
			}
		}

		return $meta_data;
	}

	private function validate_duplicate_slug( $slug ): void {
		$response = wp_remote_get( DCMS_REMOTE_SLUG . $slug );

		if ( is_wp_error( $response ) ) {
			$res = [ 'status' => 0, 'message' => '✋ Error remote site!! ' . $response->get_error_message() ];
			wp_send_json( $res );
		}

		$body = json_decode( $response['body'] );

		if ( ! empty( $body ) ) {
			$res = [ 'status' => 0, 'message' => '✋ Error duplicate slug article!' ];
			wp_send_json( $res );
		}

	}

	private function validate_nonce( $nonce ): void {
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce-clone-remote' ) ) {
			$res = [
				'status'  => 0,
				'message' => '✋ Error nonce validation!!'
			];
			wp_send_json( $res );
		}
	}

	private function validate_post_id( $post_id ): void {
		if ( ! $post_id ) {
			$res = [
				'status'  => 0,
				'message' => '✋ Error post id!!'
			];
			wp_send_json( $res );
		}
	}


}
