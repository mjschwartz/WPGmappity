<?php

function wpgmappity_upgrade_db_from_1() {
  global $wpdb;
  $map_table_name = $wpdb->prefix . "wpgmappity_maps";
  if($wpdb->get_var("show tables like '$map_table_name'") == $map_table_name) {
    $map_sql = "ALTER TABLE " . $map_table_name . " 
        ADD COLUMN map_address VARCHAR(1000),
        ADD COLUMN map_controls VARCHAR(1000),
        ADD COLUMN promote VARCHAR(255) NOT NULL,
        ADD COLUMN version VARCHAR(255) NOT NULL,
        ADD COLUMN route VARCHAR(2000) NOT NULL
	;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $wpdb->query($map_sql);
  }
  $marker_table_name = $wpdb->prefix . "wpgmappity_markers";
  if($wpdb->get_var("show tables like '$marker_table_name'") == $marker_table_name) {
    $marker_sql = "ALTER TABLE " . $marker_table_name . "
         ADD COLUMN marker_image VARCHAR(1000) NOT NULL;";
    $wpdb->query($marker_sql);
  }
}


function wpgmappity_upgrade_db_from_3() {
  global $wpdb;
  $map_table_name = $wpdb->prefix . "wpgmappity_maps";
  if($wpdb->get_var("show tables like '$map_table_name'") == $map_table_name) {
    $map_sql = "ALTER TABLE " . $map_table_name . "
         ADD COLUMN promote VARCHAR(255) NOT NULL,
         ADD COLUMN version VARCHAR(255) NOT NULL,
         ADD COLUMN route VARCHAR(2000) NOT NULL,
         MODIFY map_controls varchar(1000);";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    //dbDelta($map_sql);
    $wpdb->query($map_sql);
  }

  $marker_table_name = $wpdb->prefix . "wpgmappity_markers";
  if($wpdb->get_var("show tables like '$marker_table_name'") == $marker_table_name) {
    $marker_sql = "ALTER TABLE " . $marker_table_name . "
         ADD COLUMN marker_image VARCHAR(1000) NOT NULL;";
    $wpdb->query($marker_sql);
  }
}