<?php
/**
 * Plugin Name: FontX
 * Description: A lightweight and practical plugin for Persian fonts with !important applied styling.
 * Version: 1.0.0
 * Author: RezaEi.Ali
 * Author URI: https://ovan.dev/
 * Text Domain: fontx
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

define('FONTX_DIR', plugin_dir_path(__FILE__));
define('FONTX_URL', plugin_dir_url(__FILE__));

add_action('admin_menu', 'fontx_add_admin_menu');
add_action('admin_enqueue_scripts', 'fontx_enqueue_admin_assets');
add_action('init', 'fontx_load_textdomain');
add_action('wp_head', 'fontx_output_font_styles');

function fontx_add_admin_menu() {
    add_menu_page(
        __('FontX Settings', 'fontx'),
        __('FontX', 'fontx'),
        'manage_options',
        'fontx-settings',
        'fontx_settings_page_callback',
        'dashicons-editor-textcolor'
    );
}

function fontx_enqueue_admin_assets($hook) {
    if ($hook !== 'toplevel_page_fontx-settings') return;
    wp_enqueue_style('fontx-admin-style', FONTX_URL . 'assets/admin-style.css');
}

function fontx_load_textdomain() {
    load_plugin_textdomain('fontx', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

require_once FONTX_DIR . 'admin/settings-page.php';

function fontx_output_font_styles() {
    $selected_font = get_option('fontx_selected_font');
    if (!$selected_font || $selected_font === '__default__') return;

    $woff = esc_url(FONTX_URL . 'fonts/' . $selected_font . '.woff');
    $woff2 = esc_url(FONTX_URL . 'fonts/' . $selected_font . '.woff2');

    echo "<style>
    @font-face {
        font-family: 'FontX';
        src: url('$woff2') format('woff2'),
             url('$woff') format('woff');
        font-display: swap;
    }
    * {
        font-family: 'FontX' !important;
    }
    </style>";
}
