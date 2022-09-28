<?php
/*
Plugin Name: Preloader Bundle
Plugin URI : https://wordpress.org/plugins/preloader-bundle/
Description: Add Preloader To Your Site With Just One Click! 150+ Preloader To Select From.
Version: 1.0.0
Author: Sajjad Hossain Sagor
Author URI: https://profiles.wordpress.org/sajjad67
Text Domain: preloader-bundle
Domain Path: /languages

License: GPL2
This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------
if ( ! defined( 'PRELOADER_BUNDLE_PLUGIN_PATH' ) ) define( 'PRELOADER_BUNDLE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'PRELOADER_BUNDLE_PLUGIN_URL' ) ) define( 'PRELOADER_BUNDLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'PRELOADER_BUNDLE_PLUGIN_VERSION' ) ) define( 'PRELOADER_BUNDLE_PLUGIN_VERSION', '1.0.0' );

// ---------------------------------------------------------
// Load Language Translations
// ---------------------------------------------------------
add_action( 'plugins_loaded', 'preloader_bundle_load_plugin_textdomain' );

if ( ! function_exists( 'preloader_bundle_load_plugin_textdomain' ) )
{
    function preloader_bundle_load_plugin_textdomain()
    {
        load_plugin_textdomain( 'preloader-bundle', "", basename( dirname( __FILE__ ) ) . '/languages/' );
    }
}

// ---------------------------------------------------------
// Load Admin Settings
// ---------------------------------------------------------
require_once PRELOADER_BUNDLE_PLUGIN_PATH . 'includes/admin_settings.php';

// ---------------------------------------------------------
// Load Public Settings
// ---------------------------------------------------------
require_once PRELOADER_BUNDLE_PLUGIN_PATH . 'includes/public.php';

// ---------------------------------------------------------
// Add Go To Settings Page Link in Plugin List Table
// ---------------------------------------------------------
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'preloader_bundle_add_goto_settings_link' );

if ( ! function_exists( 'preloader_bundle_add_goto_settings_link' ) )
{
    function preloader_bundle_add_goto_settings_link( $links )
    {   
        $goto_settings_link = array( '<a href="' . admin_url( 'options-general.php?page=preloader-bundle.php' ) . '">' . __( "Settings", 'preloader_bundle' ) . '</a>' );
        
        return array_merge( $links, $goto_settings_link );
    }
}

// ---------------------------------------------------------
// Enqueue Plugin Scripts & Stylesheets in Admin
// ---------------------------------------------------------
add_action( 'admin_enqueue_scripts', 'preloader_bundle_admin_enqueue_scripts' );

if ( ! function_exists( 'preloader_bundle_admin_enqueue_scripts' ) )
{
    function preloader_bundle_admin_enqueue_scripts()
    {
        global $current_screen;

        if ( $current_screen->id !== 'settings_page_preloader-bundle' ) return;

        wp_enqueue_style( 'preloader_bundle_admin_stylesheet', plugins_url( '/assets/admin/css/style.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'assets/admin/css/style.css' ), false );
        
        wp_enqueue_script( 'preloader_bundle_admin_script', plugins_url( '/assets/admin/js/script.js', __FILE__ ), array( 'jquery' ) );
    }
}
