<?php
/*
Plugin Name: EasyUI Pro by Mzed Studio
Description: Customize WordPress admin UI with dynamic color palettes.
Version: 1.0
Author: Mzed Studio
*/

// Enqueue the plugin styles
function easyui_pro_enqueue_styles() {
    wp_enqueue_style('easyui-pro-admin-styles', plugins_url('css/admin-styles.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'easyui_pro_enqueue_styles');

// Add admin page for color customization
function easyui_pro_menu() {
    add_menu_page('EasyUI Pro Settings', 'EasyUI Pro Settings', 'manage_options', 'easyui-pro-settings', 'easyui_pro_settings_page');
}
add_action('admin_menu', 'easyui_pro_menu');

// Include admin settings functionality
require_once(plugin_dir_path(__FILE__) . 'admin-settings.php');

// Admin settings hooks
add_action('admin_init', 'easyui_pro_register_settings');
add_action('admin_init', 'easyui_pro_settings_fields');
