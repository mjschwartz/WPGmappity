<?php
/*
Plugin Name: Google Maps made Simple
Plugin URI: http://matthewschwartz.me/wordpress/wpgmappity/
Description: Point, Click, Google Maps.  Easily build a Google Map for your posts with a WYSIWYG form.
Version: 0.6
Author: Matthew Schwartz
Author URI: http://matthewschwartz.me/
*/


/*  Copyright 2012  Matthew Schwartz  (email : matt@matthewschwartz.me)

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
if ( ! defined( 'WPGMAPPITY_PLUGIN_CURRENT_DB' ) )
	define( 'WPGMAPPITY_PLUGIN_CURRENT_DB', '0.6' );

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

require_once wpgmappity_plugin_path() . 'wpgmappity-admin.php';
require_once wpgmappity_plugin_path() . 'wpgmappity-iframe.php';
require_once wpgmappity_plugin_path() . 'wpgmappity-posts.php';
require_once wpgmappity_plugin_path() . 'wpgmappity-metadata.php';

// add / remove tables
function wpgmappity_delete_table() {
  global $wpdb;

  $map_table_name = $wpdb->prefix . "wpgmappity_maps";
  $marker_table_name = $wpdb->prefix . "wpgmappity_markers";
  $map_sql = "DROP TABLE IF EXISTS" . $map_table_name . ";";
  $marker_sql = "DROP TABLE IF EXISTS" . $marker_table_name . ";";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  $wpdb->query("DROP TABLE IF EXISTS ".$map_table_name);
  $wpdb->query("DROP TABLE IF EXISTS ".$marker_table_name);
 
   delete_option("wpgmappity_db_version", $wpgmappity_db_version);
}

function wpgmappity_init_table() {
   global $wpdb;
   $wpgmappity_db_version = WPGMAPPITY_PLUGIN_CURRENT_DB;

   $map_table_name = $wpdb->prefix . "wpgmappity_maps";
   $marker_table_name = $wpdb->prefix . "wpgmappity_markers";

   wpgmappity_db_version();

   if($wpdb->get_var("show tables like '$map_table_name'") != $map_table_name) {
      
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
          scroll VARCHAR(255) NOT NULL,
          map_address VARCHAR(1000) NOT NULL,
          map_controls VARCHAR(1000) NOT NULL,
          promote VARCHAR(255) NOT NULL,
          version VARCHAR(255) NOT NULL,
          route VARCHAR(2000) NOT NULL,
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
          marker_image VARCHAR(500),
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
  add_option('wpgmappity_options', array('save_tables' => '1'));
  wpgmappity_init_table();
}

function wgmappity_settings_destroy() {
  $settings = get_option('wpgmappity_options');
  if ($settings['save_tables'] == '0') {
    delete_option('wpgmappity_options');
    delete_option('wpgmappity_db_version');
    wpgmappity_delete_table();
  }
}
register_activation_hook( __FILE__, 'wgmappity_settings_init' );
register_deactivation_hook( __FILE__, 'wgmappity_settings_destroy' );

// DB Versioning
add_action('admin_init', 'wpgmappity_db_version');
function wpgmappity_db_version() {

  $db_version = get_option('wpgmappity_db_version');
  $current_db_version = WPGMAPPITY_PLUGIN_CURRENT_DB;
  $is_wedged = get_option('wpgmappity_db_wedge');

  // Fix the DB's that have gotten stuck with the bad version
  // temp hack until the next db upgrade
  if ( (isset($db_version)) && ($db_version == $current_db_version) && ($is_wedged === false) ) 
    {
      global $wpdb;
      $marker_table_name = $wpdb->prefix . "wpgmappity_markers";
      if($wpdb->get_var("show columns from $marker_table_name like 'marker_image'") == false) {
	require_once wpgmappity_plugin_path() . 'wpgmappity-db-upgrade.php';
	wpgmappity_upgrade_db_from_3();
      }
      add_option("wpgmappity_db_wedge", '1');
    }
  
  if ( (isset($db_version)) && ($db_version != $current_db_version) ) {
    require_once wpgmappity_plugin_path() . 'wpgmappity-db-upgrade.php';
    switch ($db_version) {
      case '0.1':
      	wpgmappity_upgrade_db_from_1();
      	update_option("wpgmappity_db_version", $current_db_version);
      	break;
      case '0.3':
      	wpgmappity_upgrade_db_from_3();
      	update_option("wpgmappity_db_version", $current_db_version);
      	break;
      case '0.5':
        wpgmappity_upgrade_db_from_5();
        update_option("wpgmappity_db_version", $current_db_version);
        break;
    }
  }
}


// WP-Gmappity plug-in settings menu
function wpgmappity_menu() {
  add_submenu_page('plugins.php', 'WP G-Mappity Configuration', 'WP G-Mappity Configuration', 'manage_options', 'wpgmappity-api-config', 'wpgmappity_conf');
}
add_action('admin_menu', 'wpgmappity_menu');

// stylesheet for posts
function wpgmappity_post_stylesheet() {
  $content = '<link media="screen" type="text/css" rel="stylesheet" ';
  $content .= 'href="'.wpgmappity_plugin_url().'/styles/wpgmappity-post-styles.css" />';
  echo $content;
}
add_action('wp_head', 'wpgmappity_post_stylesheet');


?>
