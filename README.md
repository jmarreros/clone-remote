# Clone Remote

Plugin to clone remote content using REST API WordPress

- If you work locally you need to add the following line to your wp-config.php file:
```php
define( 'WP_ENVIRONMENT_TYPE', 'local' );
```

- For remote site you have to register no private post meta
```php
add_action('rest_api_init', function () {
    $metas = [
        "Nivel",
        "relacionados",
        "youtube",
    ];

    foreach ($metas as $meta) {
        register_meta(
            'post',
            $meta,
            [
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true
            ]
        );
    }
});
```

- For the remote site you need to create endpoint with the following code for private custom post meta:

```php
add_action('rest_api_init', 'dcms_register_custom_route');


function dcms_register_custom_route(): void
{
    register_rest_route('dcms-custom-meta/v1', '/add/', array(
        'methods'  => 'POST',
        'callback' => 'dcms_custom_meta_data',
    ));
}

function dcms_custom_meta_data(\WP_REST_Request $req): void
{
    $post_id = $req->get_param('post_id');
    update_post_meta($post_id, '_genesis_custom_post_class', $req->get_param('genesis_custom_post_class'));
    update_post_meta($post_id, '_dcms_eufi_img', $req->get_param('dcms_eufi_img'));
    update_post_meta($post_id, '_dcms_eufi_alt', $req->get_param('dcms_eufi_alt'));
}
```

- You can change credentials and URLs in clone-remote.php file
