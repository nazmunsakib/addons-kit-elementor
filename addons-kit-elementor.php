<?php
/**
 * Plugin Name: Addons Kit Elementor
 * Description: The Addons Kit plugin you install after Elementor!
 * Plugin URI: https://addonskit.com/
 * Author: nazmunsakib
 * Version: 1.0.0
 * Author URI: https://nazmunsakib.com/
 * Text Domain: addons-kit
 * Domain Path: /languages
 *
 * Elementor tested up to: 3.13.4
 * Elementor Pro tested up to: 3.13.2
 * 
 * @package AddonsKit
 */

/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2023 NazmunSakib <http://nazmunsakib.com>
*/

defined('ABSPATH') || die();

/**
 * Defining plugin constants.
 *
 * @since 1.0.0
 */

if ( defined( 'WP_ENVIRONMENT_TYPE' ) && 'development' == wp_get_environment_type() ) {
	define('ADDONS_KIT_PLUGIN_VERSION', time() );
} else {
	define('ADDONS_KIT_PLUGIN_VERSION', '1.0.0');
}

define('ADDONS_KIT_PLUGIN_FILE', __FILE__ );
define('ADDONS_KIT_DIR_PATH', plugin_dir_path( ADDONS_KIT_PLUGIN_FILE ) );
define('ADDONS_KIT_DIR_URL', plugin_dir_url( ADDONS_KIT_PLUGIN_FILE ) );
define('ADDONS_KIT_PLUGIN_BASENAME', plugin_basename( ADDONS_KIT_PLUGIN_FILE ) );
define('ADDONS_KIT_ASSETS', trailingslashit( ADDONS_KIT_DIR_URL . 'assets'));

define('ADDONS_KIT_MINIMUM_ELEMENTOR_VERSION', '3.5.0');
define('ADDONS_KIT_MINIMUM_PHP_VERSION', '5.4');

/**
 * The journey starts here.
 *
 * @return void Some voids are not really void, you have to explore to figure out why not!
 */
function addons_kit_begin(){

    //require( HAPPY_ADDONS_DIR_PATH . 'inc/functions.php' );

    /**
     * Check for required PHP version
     */
    if ( version_compare( PHP_VERSION, ADDONS_KIT_MINIMUM_PHP_VERSION, '<' ) ){
        add_action('admin_notices', 'ak_admin_notice_required_php_version');
        return;
    }

    /**
     * Check if Elementor installed and activated
     */
    if ( ! did_action('elementor/loaded') ) {
        add_action('admin_notices', 'ak_admin_notice_elementor_missing');
        return;
    }

    /**
     * Check for required Elementor version
     */
    if ( ! version_compare( ELEMENTOR_VERSION, ADDONS_KIT_MINIMUM_ELEMENTOR_VERSION, '>=' ) ){

        add_action('admin_notices', 'ak_admin_notice_required_elementor_version');
        return;

    }

    require ADDONS_KIT_DIR_PATH . 'Addons_Kit.php';
    \Addons_Kit\Elementor\Addons_Kit::instance();
}

add_action('plugins_loaded', 'addons_kit_begin');

/**
 * Admin notice for required php version
 *
 * @return void
 */
function ak_admin_notice_required_php_version() {

    $notice = sprintf(
        esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'addons-kit-elementor'),
        '<strong>' . esc_html__('Addons Kit Elementor', 'addons-kit-elementor') . '</strong>',
        '<strong>' . esc_html__('PHP', 'addons-kit-elementor') . '</strong>',
        ADDONS_KIT_MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );

}

/**
 * Admin notice for elementor if missing
 *
 * @return void
 */
function ak_admin_notice_elementor_missing() {

    if( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php') ) {

        $notice_title = __('Activate Elementor', 'addons-kit-elementor');
        $notice_url = wp_nonce_url('plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php');

    } else {

        $notice_title = __('Install Elementor', 'addons-kit-elementor');
        $notice_url = wp_nonce_url( self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor' );

    }

    $notice = ha_kses_intermediate( sprintf(
        __('%1$s requires %2$s to be installed and activated to function properly. %3$s', 'addons-kit-elementor'),
        '<strong>' . __('Addons Kit Elementor', 'addons-kit-elementor') . '</strong>',
        '<strong>' . __('Elementor', 'addons-kit-elementor') . '</strong>',
        '<a href="' . esc_url( $notice_url ) . '">' . $notice_title . '</a>'
    ) );

    printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
}

/**
 * Admin notice for required elementor version
 *
 * @return void
 */
function ak_admin_notice_required_elementor_version() {

    $notice_title = __('Update Elementor', 'addons-kit-elementor');
    $notice_url = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=elementor/elementor.php'), 'upgrade-plugin_elementor/elementor.php');

    $notice = sprintf(
        esc_html__('"%1$s" requires "%2$s" version %4$s or greater. %3$s', 'addons-kit-elementor'),
        '<strong>' . esc_html__('Addons Kit Elementor', 'addons-kit-elementor') . '</strong>',
        '<strong>' . esc_html__('Elementor', 'addons-kit-elementor') . '</strong>',
        '<a href="' . esc_url( $notice_url ) . '">' . $notice_title . '</a>',
        ADDONS_KIT_MINIMUM_ELEMENTOR_VERSION
    );

    printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
}








