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

		$post       = get_post( $post_id );
		$categories = wp_get_post_categories( $post_id, [ 'fields' => 'ids' ] );
		$tags       = wp_get_post_tags( $post_id, [ 'fields' => 'ids' ] );
		$meta_data  = $this->get_meta_data( $post_id );

		$data = [
			'title'      => $post->post_title,
			'slug'       => $post->post_name,
			'content'    => $post->post_content,
			'author'     => 2,
			'status'     => 'draft',
			'categories' => $categories,
			'tags'       => $tags,
			'meta'       => $meta_data
		];

		error_log(print_r($data,true));

		$request = wp_remote_post(
			DCMS_REMOTE_URL,
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( "$login:$password" )
				),
				'body'    => $data
			)
		);

		$res = [
			'status'  => 1,
			'message' => 'OK'
		];

		if( 'Created' !== wp_remote_retrieve_response_message( $request ) ) {
			$body = json_decode( wp_remote_retrieve_body( $request ) );
			$res = [
				'status'  => 0,
				'message' => $body->message
			];
		}

		wp_send_json( $res );
	}

//	public function search_remote_content(): void {
//
//		$res = [
//			'status'  => 1,
//			'message' => 'OK'
//		];
//
//		wp_send_json( $res );
//	}

	private function get_meta_data( $post_id ): array {
		$meta_custom = [
			"Nivel",
			"relacionados",
			"youtube",
//			"_genesis_custom_post_class",
//			"_dcms_eufi_img",
//			"_dcms_eufi_alt",
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
