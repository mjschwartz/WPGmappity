<?php

function wpgmappity_theme_scripts() {
  wp_enqueue_script( 'jquery' );
  $wpgmappity_api_key = wpgmappity_get_api_key();
  if ( isset($wpgmappity_api_key) && ($wpgmappity_api_key != '') ) {
    $gmap_url = 'http://www.google.com/jsapi?key=' . $wpgmappity_api_key;
    wp_enqueue_script( 'gmap_loader', $gmap_url );
  }
  wp_enqueue_script( 'gmap-admin-functions', wpgmappity_plugin_url( 'js/wpgmappity-gmap.js' ),array( 'gmap_loader' ) );
}

add_action( 'wp_print_scripts', 'wpgmappity_theme_scripts' );

add_shortcode('wpgmappity', 'wpgmappity_shortcode_handle');

function wpgmappity_shortcode_handle($attr) {
  global $post;

  $map = wgmappity_get_meta_data($attr['id']); 
  $map = $map[0];
  //die(var_dump($map));
  $content = wpgmappity_shortcode_container_div($map);
  $content .=  wpgmappity_shortcode_mapjs($map); 
  return $content;
}

function wpgmappity_shortcode_container_div($map) {
  $content = '<div id="wpgmappity-map-'.$map['id'].'"';
  $content .= ' style="width:'.$map['map_length'].'px;';
  $content .= 'height:'.$map['map_height'].'px;';
  $content .= 'float:'.$map['alignment'].';">';
  $content .= '</div>';
  return $content;
}

function wpgmappity_shortcode_mapjs($map) {
  $content = '<script type="text/javascript">'."\n";
  $content .= 'function wpgmapptiy_maps_loaded'.$map['id'].'() {'."\n";
  //div-id
  $content .= 'var wpgmappitymap'.$map['id'].' = ';
  $content .= 'new google.maps.Map2(document.getElementById("';
  $content .= 'wpgmappity-map-'.$map['id'].'"));'."\n";
  //center
  $content .= 'wpgmappitymap'.$map['id'].'.setCenter(new google.maps.LatLng(';
  $content .= $map['center_lat'].', '.$map['center_long'].'), '.$map['map_zoom'].');'."\n";
  $content .= wpgmappity_shortcode_maptype($map);
  if (wpgmappity_has_markers($map['id']) != '0') {  
    $content .= wpgmappity_shortcode_markers_js($map['id']);
  }
  $content .= '};'."\n";
  $content .= 'google.load("maps", "2", {"callback" : wpgmapptiy_maps_loaded'.$map['id'].'});';
  $content .= '</script>';

  return $content;
}

function wpgmappity_shortcode_markers_js($map_id) {
  $markers = wpgmappity_retrieve_markers($map_id);
  $i = 0;
  $content = '';
  foreach($markers as $marker) {
    $content .= "var point$map_id_$i = new GLatLng(".$marker['marker_lat'].",".$marker['marker_long'].");\n";
    $content .= "var marker".$map_id."_".$i." = new GMarker(point$map_id_$i);\n";
      if ($marker['marker_text'] != '') {
	if ($marker['marker_url'] != '') {
	  $html = '<a href="'.$marker['marker_url'].'">'.$marker['marker_text'].'</a>';
	}
	else {
	  $html = $marker['marker_text'];
	}
	$content .= "GEvent.addListener(marker".$map_id."_".$i.", 'click', function() {wpgmappitymap$map_id.openInfoWindow(point$map_id_$i, '".$html."');});\n";
      }

    $content .= "wpgmappitymap$map_id.addOverlay(marker".$map_id."_".$i.");\n";
    $i += 1;
  }
  return $content;

}

function wpgmappity_has_markers($id) {
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_markers";
  $query = "SELECT COUNT(id) FROM $table WHERE map_id = '$id';";
  return $wpdb->get_var($query);
}

function wpgmappity_retrieve_markers($map_id) {
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_markers";
  return $wpdb->get_results("SELECT * FROM $table WHERE map_id = $map_id", ARRAY_A);
}

function wpgmappity_shortcode_maptype($map) {
  if ($map['map_type'] == 'normal') {
    $type = "G_NORMAL_MAP";
  }
  elseif ($map['map_type'] == 'satellite') {
    $type = "G_SATELLITE_MAP";
  }
  else {
    $type = "G_HYBRID_MAP";
  }
  return "wpgmappitymap".$map['id'].".setMapType(".$type.");\n";

}

?>