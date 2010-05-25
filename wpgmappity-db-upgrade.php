<?php

function wpgmappity_upgrade_db_from_1() {
  global $wpdb;
  $map_table_name = $wpdb->prefix . "wpgmappity_maps";
  if($wpdb->get_var("show tables like '$map_table_name'") == $map_table_name) {
    $map_sql = "ALTER TABLE " . $map_table_name . " 
          ADD COLUMN map_address VARCHAR(1000),
          ADD COLUMN map_controls VARCHAR(255)
	;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  }
}


?>