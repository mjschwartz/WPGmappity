<?php

function wpgmappity_insert_meta_data($map) {
  global $wpdb;
  // define json_decode for PHP4 users
  if (!function_exists('json_decode')) {
    function json_decode($content, $assoc=false) {
      require_once wpgmappity_plugin_path().'classes/JSON.phps';
        if ($assoc) {
          $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        }
        else {
          $json = new Services_JSON;
        }
        return $json->decode($content);
    }
  }
   wpgmappity_db_version();
  // JSON.stringify leaves \'s - remove them for json_decode
  $map = json_decode(stripslashes($map), true);
  $table = $wpdb->prefix . "wpgmappity_maps";
  $query = $wpdb->prepare( "
    INSERT INTO $table
    ( map_length, map_height, map_zoom, center_lat, 
    center_long, map_type, alignment, map_address, map_controls, route, promote, version, scroll )
    VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
    $map['map_length'], $map['map_height'], $map['map_zoom'],
    $map['center_lat'], $map['center_long'], $map['map_type'], 
    $map['alignment'], $map['map_address'], base64_encode(serialize($map['controls'])),
    base64_encode(serialize($map['route'])),
    $map['promote'],  WPGMAPPITY_PLUGIN_CURRENT_DB, $map['scroll']  );
  $wpdb->query($query);
  $insert_id = $wpdb->insert_id;
  // markers
  foreach ($map['markers'] as $marker) {
    $table = $wpdb->prefix . "wpgmappity_markers";
    $query = $wpdb->prepare( "
      INSERT INTO $table
      ( map_id, marker_lat, marker_long, marker_text, marker_url, marker_image )
      VALUES ( %s, %s, %s, %s, %s, %s )",
      $insert_id, $marker['lat'], $marker['long'],
      $marker['marker_text'], $marker['marker_url'], $marker['image'] );

    $wpdb->query($query);
  }
  return $insert_id;
}

function wpgmappity_update_meta_data($map, $map_id) {
  
  global $wpdb;
  // define json_decode for PHP4 users
  if (!function_exists('json_decode')) {
    function json_decode($content, $assoc=false) {
      require_once wpgmappity_plugin_path().'classes/JSON.phps';
        if ($assoc) {
          $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        }
        else {
          $json = new Services_JSON;
        }
        return $json->decode($content);
    }
  }


   wpgmappity_db_version();
  // JSON.stringify leaves \'s - remove them for json_decode
  $map = json_decode(stripslashes($map), true);
  $table = $wpdb->prefix . "wpgmappity_maps";
  $marker_table = $wpdb->prefix . "wpgmappity_markers";
  //die(var_dump($map));
  $wpdb->update( $table, array( 'map_length' => $map['map_length'], 
      'map_height' => $map['map_height'], 
      'map_zoom' => $map['map_zoom'], 
      'center_lat' => $map['center_lat'],
      'center_long' => $map['center_long'], 
      'map_type' => $map['map_type'], 
      'alignment' => $map['alignment'],
      'map_address' => $map['map_address'], 
      'map_controls' => base64_encode(serialize($map['controls'])),
      'route' => base64_encode(serialize($map['route'])),
      'promote' => $map['promote'], 
      'scroll' => $map['scroll'], 
      'version' => $map['version'] ),
    array( 'id' => $map_id ) );
// delete all old markers
  $query = $wpdb->prepare( "
    DELETE FROM $marker_table
    WHERE map_id = $map_id;");
  $wpdb->query($query);
  // re-add updated markers
  $i = 0;
  foreach ($map['markers'] as $marker) {
    $query = $wpdb->prepare( "
      INSERT INTO $marker_table
      ( map_id, marker_lat, marker_long, marker_text, marker_url, marker_image )
      VALUES ( %s, %s, %s, %s, %s, %s )",
      $map_id, $marker['lat'], $marker['long'],
	     $marker['marker_text'], '', $marker['image'] );
    $wpdb->query($query);
  }
  return $map_id; 
}

function wpgmappity_delete_map_item($map_id) {
  
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_maps";
  $marker_table = $wpdb->prefix . "wpgmappity_markers";
  
  $query = $wpdb->prepare( "
    DELETE FROM $table
    WHERE id = $map_id;");
  $wpdb->query($query);
  
  $query = $wpdb->prepare( "
    DELETE FROM $marker_table
    WHERE map_id = $map_id;");
  $wpdb->query($query);
  
}

function wgmappity_get_meta_data($map_id) {
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_maps";
  return $wpdb->get_results("SELECT * FROM $table WHERE id = $map_id", ARRAY_A);

}
?>
