<?php
/*
Plugin Name: Zord Form
Plugin URI: https://bitbucket.org/zordsdavini/zordform/overview
Description: Zord Form is inline form to calculate some entered stuff.
Version: 1.1
Author: Udovīčė Arns
Author URI: http://arns.lt
Text Domain: zordform
Domain Path: /lang/

Copyright 2013 Udovīčė Arns.


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
global $wpdb, $wp_version;

define("ZORDFORM_DIR", WP_PLUGIN_DIR."/".basename( dirname( __FILE__ ) ) );
define("ZORDFORM_URL", plugins_url()."/".basename( dirname( __FILE__ ) ) );
define("ZORDFORM_VERSION", "1.1");
define("ZORDFORM_TABLE_NAME", $wpdb->prefix . "zordform");
define("ZORDFORM_FIELDS_TABLE_NAME", $wpdb->prefix . "zordform_fields");
define("ZORDFORM_OPTIONS_TABLE_NAME", $wpdb->prefix . "zordform_options");
define("ZORDFORM_RESULTS_TABLE_NAME", $wpdb->prefix . "zordform_results");
define("ZORDFORM_RESULT_VALUES_TABLE_NAME", $wpdb->prefix . "zordform_result_values");

/* Require Core Files */
require_once( ZORDFORM_DIR . "/includes/database.php" );
require_once( ZORDFORM_DIR . "/includes/activation.php" );
require_once( ZORDFORM_DIR . "/includes/shortcode.php" );

require_once( ZORDFORM_DIR . "/includes/admin/scripts.php" );
require_once( ZORDFORM_DIR . "/includes/admin/admin-ajax.php" );
require_once( ZORDFORM_DIR . "/includes/admin/admin.php" );
require_once( ZORDFORM_DIR . "/includes/admin/views.php" );
require_once( ZORDFORM_DIR . "/includes/admin/save.php" );

require_once( ZORDFORM_DIR . "/includes/display/scripts.php" );
require_once( ZORDFORM_DIR . "/includes/display/display-ajax.php" );

if(session_id() == '') {
	session_start();
}
$_SESSION['ZORDFORM_DIR'] = ZORDFORM_DIR;
$_SESSION['ZORDFORM_URL'] = ZORDFORM_URL;

function zordform_load_lang() {

	/** Set our unique textdomain string */
	$textdomain = 'zordform';

	/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
	$locale = apply_filters( 'plugin_locale', get_locale(), $textdomain );

	/** Set filter for WordPress languages directory */
	$wp_lang_dir = apply_filters(
		'zordform_wp_lang_dir',
		WP_LANG_DIR . '/zordform/' . $textdomain . '-' . $locale . '.mo'
	);

	/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
	load_textdomain( $textdomain, $wp_lang_dir );

	/** Translations: Secondly, look in plugin's "lang" folder = default */
	$plugin_dir = basename( dirname( __FILE__ ) );
	$lang_dir = apply_filters( 'zordform_lang_dir', $plugin_dir . '/lang/' );
	load_plugin_textdomain( $textdomain, FALSE, $lang_dir );

}
add_action('plugins_loaded', 'zordform_load_lang');

function zordform_update_version_number(){
	$plugin_settings = get_option( 'zordform_settings' );
	if ( ZORDFORM_VERSION != $plugin_settings['version'] ) {
		$plugin_settings['version'] = ZORDFORM_VERSION;
		update_option( 'zordform_settings', $plugin_settings );
	}
}
add_action( 'admin_init', 'zordform_update_version_number' );
register_activation_hook( __FILE__, 'zordform_activation' );

