<?php
/**
 * Plugin Name: Announcement Bar
 * Description: A plugin to enable an announcement bar on all pages
 * Version: 1.0
 * Author: Joshua Clark
 */

// Safety check: Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function mcp_add_banner() {
    echo '<div style="background:#663399;color:white;padding:10px;text-align:center;">
        This is an announcement bar!
    </div>';
}
add_action( 'wp_body_open', 'mcp_add_banner' );
