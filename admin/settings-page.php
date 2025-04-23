<?php
defined('ABSPATH') || exit;

function fontx_settings_page_callback() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'fontx'));
    }

    $font_dir = FONTX_DIR . 'fonts/';
    $files = scandir($font_dir);
    $font_names = [];

    foreach ($files as $file) {
        if (preg_match('/\.woff2$/', $file)) {
            $name = basename($file, '.woff2');
            $font_names[] = sanitize_text_field($name);
        }
    }

    $font_names = array_unique($font_names);
    sort($font_names);

    $current = get_option('fontx_selected_font', '__default__');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('fontx_save_font', 'fontx_nonce')) {
        if (isset($_POST['fontx_font'])) {
            $font = sanitize_text_field($_POST['fontx_font']);

            if ($font === '__default__') {
                delete_option('fontx_selected_font');
                echo '<div class="notice notice-success is-dismissible"><p>' . __('🔄 Plugin font has been disabled. Reverted to the theme default font', 'fontx') . '</p></div>';
                $current = '__default__';
            } elseif (in_array($font, $font_names)) {
                update_option('fontx_selected_font', $font);
                echo '<div class="notice notice-success is-dismissible"><p>' . __('✅ The selected font has been successfully applied', 'fontx') . '</p></div>';
                $current = $font;
            }
        }
    }

    echo '<div class="wrap" style="text-align:center;font-size:20px;background-color:white;color:royalblue;padding:20px;border-radius:6px;">';
    echo '<h1>' . esc_html__('FontX - By Ovan.Dev', 'fontx') . '</h1>';
    echo '<form method="post">';
    wp_nonce_field('fontx_save_font', 'fontx_nonce');

    echo '<label for="fontx_font" dir="ltr">' . esc_html__('Select Font :', 'fontx') . '</label><br>';
    echo '<select style="width:400px !important;text-align:center;" name="fontx_font" id="fontx_font" required>';

    echo '<option value="__default__"' . selected($current, '__default__', false) . '>' . esc_html__('— Use default font —', 'fontx') . '</option>';

    foreach ($font_names as $font) {
        $selected = selected($current, $font, false);
        echo "<option value='" . esc_attr($font) . "' $selected>" . esc_html($font) . '</option>';
    }

    echo '</select>';
    echo '<br><br><input type="submit" class="button button-primary" value="' . esc_attr__('Save', 'fontx') . '">';
    echo '</form>';
    echo '</div>';
}
