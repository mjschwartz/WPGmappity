<?php

function wpgmappity_insert_meta_data($map) {
  global $wpdb;
  // JSON.stringify leaves \'s - remove them for json_decode
  $map = json_decode(str_replace('\\', '', $map), true);
  //  die(var_dump($map));  
  $table = $wpdb->prefix . "wpgmappity_maps";
  $query = $wpdb->prepare( "
    INSERT INTO $table
    ( map_length, map_height, map_zoom, center_lat, 
    center_long, map_type, alignment )
    VALUES ( %s, %s, %s, %s, %s, %s, %s )",
    $map['map_length'], $map['map_height'], $map['map_zoom'],
    $map['center_lat'], $map['center_long'], $map['map_type'], $map['alignment']);

  $wpdb->query($query);
  $insert_id = $wpdb->insert_id;
  // markers
  foreach ($map['markers'] as $marker) {
    $table = $wpdb->prefix . "wpgmappity_markers";
    $query = $wpdb->prepare( "
      INSERT INTO $table
      ( map_id, marker_lat, marker_long, marker_text, marker_url )
      VALUES ( %s, %s, %s, %s, %s )",
      $insert_id, $marker['lat'], $marker['long'],
      $marker['marker_text'], $marker['marker_url'] );

    $wpdb->query($query);
  }
  return $insert_id;
}

function wgmappity_get_meta_data($map_id) {
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_maps";
  return $wpdb->get_results("SELECT * FROM $table WHERE id = $map_id", ARRAY_A);

}
?>