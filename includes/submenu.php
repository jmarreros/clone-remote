<?php

namespace dcms\cloneremote\includes;

/**
 * Class for creating a dashboard submenu
 */
class Submenu
{
    // Constructor
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_submenu']);
    }

    // Register submenu
    public function register_submenu()
    {
        add_submenu_page(
            DCMS_CLONE_SUBMENU,
            __('Clone remote content', 'clone-remote'),
            __('Clone remote content', 'clone-remote'),
            'manage_options',
            'clone-remote',
            [$this, 'submenu_page_callback']
        );
    }

    // Callback, show view
    public function submenu_page_callback()
    {
        include_once(DCMS_CLONE_PATH . '/views/main-screen.php');
    }
}
