<?php
/*
Plugin Name: Wp G-Mappity
Plugin URI: http://www.wordpresspluginfu.com/wpgmappity/
Description: Point, Click, Google Maps.
Version: 0.1
Author: Matthew Schwartz
Author URI: http://schwartzlink.net
*/


/*  Copyright 2010  Matthew Schwartz  (email : schwartz.matthew@schwartzlink.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( ! defined( 'WPGMAPPITY_PLUGIN_BASENAME' ) )
	define( 'WPGMAPPITY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'WPGMAPPITY_PLUGIN_NAME' ) )
	define( 'WPGMAPPITY_PLUGIN_NAME', trim( dirname( WPGMAPPITY_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'WPGMAPPITY_PLUGIN_DIR' ) )
	define( 'WPGMAPPITY_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WPGMAPPITY_PLUGIN_NAME );

if ( ! defined( 'WPGMAPPITY_PLUGIN_URL' ) )
	define( 'WPGMAPPITY_PLUGIN_URL', WP_PLUGIN_URL . '/' . WPGMAPPITY_PLUGIN_NAME );

function wpgmappity_plugin_path( $path = '' ) {
	return path_join( WPGMAPPITY_PLUGIN_DIR, trim( $path, '/' ) );
}

function wpgmappity_plugin_url( $path = '' ) {
	return plugins_url( $path, WPGMAPPITY_PLUGIN_BASENAME );
}

require_once wpgmappity_plugin_path() . '/wpgmappity-admin.php';
require_once wpgmappity_plugin_path() . '/wpgmappity-iframe.php';
require_once wpgmappity_plugin_path() . '/wpgmappity-posts.php';
require_once wpgmappity_plugin_path() . '/wpgmappity-metadata.php';

// add / remove tables
function wpgmappity_delete_table() {
  global $wpdb;

  $map_table_name = $wpdb->prefix . "wpgmappity_maps";
  $marker_table_name = $wpdb->prefix . "wpgmappity_markers";
  $map_sql = "DROP TABLE IF EXISTS" . $map_table_name . ";";
  $marker_sql = "DROP TABLE IF EXISTS" . $marker_table_name . ";";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  //dbDelta($map_sql);
  //dbDelta($marker_sql);
  $wpdb->query("DROP TABLE IF EXISTS ".$map_table_name);
  $wpdb->query("DROP TABLE IF EXISTS ".$marker_table_name);
 
   delete_option("wpgmappity_db_version", $wpgmappity_db_version);
}

function wpgmappity_init_table() {
   global $wpdb;
   $wpgmappity_db_version = "0.1";

   $map_table_name = $wpdb->prefix . "wpgmappity_maps";
   $marker_table_name = $wpdb->prefix . "wpgmappity_markers";

   if($wpdb->get_var("show tables like '$table_name'") != $map_table_name) {
      
      $map_sql = "CREATE TABLE " . $map_table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
          active int(1) DEFAULT '1' NOT NULL,
          map_length VARCHAR(255) NOT NULL,
          map_height VARCHAR(255) NOT NULL,
          map_zoom VARCHAR(255) NOT NULL,
          center_lat VARCHAR(255) NOT NULL,
          center_long VARCHAR(255) NOT NULL,
          map_type VARCHAR(255) NOT NULL,
          alignment VARCHAR(255) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      $marker_sql = "CREATE TABLE " . $marker_table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  map_id mediumint(9),
          active int(1) DEFAULT '1' NOT NULL,
          marker_lat VARCHAR(255) NOT NULL,
          marker_long VARCHAR(255) NOT NULL,
          marker_text VARCHAR(1000),
          marker_url VARCHAR(1000),
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($map_sql);
      dbDelta($marker_sql);
 
      add_option("wpgmappity_db_version", $wpgmappity_db_version);
   }
}

// add / remove plug-in
function wgmappity_settings_init() {
	add_option('wpgmappity_options', array('gmaps_api' => ''));
	wpgmappity_init_table();
}
function wgmappity_settings_destroy() {
	delete_option('wpgmappity_options');
	wpgmappity_delete_table();
}
register_activation_hook( __FILE__, 'wgmappity_settings_init' );
register_deactivation_hook( __FILE__, 'wgmappity_settings_destroy' );


function wpgmappity_get_api_key() {
	$settings = get_option('wpgmappity_options');

	return $settings['gmaps_api'];
}


// Warning for no api in place
add_action('admin_init', 'wpgmappity_admin_warning');
function wpgmappity_admin_warning() {
	
	$settings = get_option('wpgmappity_options');
	if ( $settings['gmaps_api'] == '' ) {
		function wpgmappity_api_warning() {
			echo "
			<div id='wpgmappity_api-warning' class='updated fade'><p><strong>".__('WP G-Mappity is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">enter your Google Maps API key</a> for it to work.'), "plugins.php?page=wpgmappity-api-config")."</p></div>
			";
		}
		add_action('admin_notices', 'wpgmappity_api_warning');
		
	}
}


// WP-Gmappity plug-in settings menu
function wpgmappity_menu() {
	add_submenu_page('plugins.php', 'WP G-Mappity Configuration', 'WP G-Mappity Configuration', 'manage_options', 'wpgmappity-api-config', 'wpgmappity_conf');
}
add_action('admin_menu', 'wpgmappity_menu');





?>