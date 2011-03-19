<?php

function wpgmappity_theme_scripts() {
  wp_enqueue_script( 'jquery' );
  $gmap_url = 'http://maps.google.com/maps/api/js?sensor=false';
  wp_enqueue_script( 'gmap_loader', $gmap_url );
  
  wp_enqueue_script( 'gmap-admin-functions', wpgmappity_plugin_url( 'js/wpgmappity-gmap.js' ),array( 'gmap_loader' ) );
}

add_action( 'wp_print_scripts', 'wpgmappity_theme_scripts' );

add_shortcode('wpgmappity', 'wpgmappity_shortcode_handle');

function wpgmappity_shortcode_handle($attr) {
  global $post;

  $map = wgmappity_get_meta_data($attr['id']); 
  $map = $map[0];
  $content = wpgmappity_shortcode_container_div($map);
  $content .=  wpgmappity_shortcode_mapjs($map); 
  return $content;
}

function wpgmappity_shortcode_container_div($map) {
  $content = '<div class="wpgmappity_container" id="wpgmappity-map-'.$map['id'].'"';
  $content .= ' style="width:'.$map['map_length'].'px;';
  $content .= 'height:'.$map['map_height'].'px;';
  if ( ($map['alignment'] == 'right') || ($map['alignment'] == 'left') ) {
    $content .= 'float:'.$map['alignment'].';">';
  }
  elseif ($map['alignment'] == 'center') {
    $content .= 'margin-left:auto;margin-right:auto;">';
  }
  else {
    $content .= '">';
  }
  $content .= '</div>';
  return $content;
}

/*
 
  var latlng = new google.maps.LatLng(data.center_lat, data.center_long);

  var myOptions = {
    zoom: data.map_zoom,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDoubleClickZoom : true,
    scrollwheel : false,
    disableDefaultUI : true
  };
  var map = new google.maps.Map(document.getElementById(target_div), myOptions);
  */

function wpgmappity_shortcode_mapjs($map) {
  $content = '<script type="text/javascript">'."\n";
  $content .= 'function wpgmappity_maps_loaded'.$map['id'].'() {'."\n";
  // center point
  $content .= "var latlng = new google.maps.LatLng(".$map['center_lat'].",".$map['center_long'].");\n";
  // inital options
  $content .= "var options = {\n";
  $content .= "  center : latlng,\n";
  $content .= "  mapTypeId: google.maps.MapTypeId.".wpgmappity_shortcode_maptype($map).",\n";
  $content .= "  zoom : ".$map['map_zoom']."\n";
  $content .= "};\n";

  $content .= 'var wpgmappitymap'.$map['id'].' = ';
  $content .= "new google.maps.Map(document.getElementById(";
  $content .= "'wpgmappity-map-".$map['id']."'), options);\n";
  /*
  //div-id
  $content .= 'var wpgmappitymap'.$map['id'].' = ';
  $content .= 'new google.maps.Map2(document.getElementById("';
  $content .= 'wpgmappity-map-'.$map['id'].'"));'."\n";
  //center
  $content .= 'wpgmappitymap'.$map['id'].'.setCenter(new google.maps.LatLng(';
  $content .= $map['center_lat'].', '.$map['center_long'].'), '.$map['map_zoom'].');'."\n";
  $content .= wpgmappity_shortcode_maptype($map);
  if ( ($map['map_controls'] == 'small') || ($map['map_controls'] == 'large') ) {
    $content .= wpgmappity_shortcode_controls($map);
  }
  if (wpgmappity_has_markers($map['id']) != '0') {  
    $content .= wpgmappity_shortcode_markers_js($map['id']);
  }
  $content .= '}'."\n";
  $content .= 'if (typeof(google.maps) == undefined) {'."\n";
  $content .= 'google.load("maps", "2", {"callback" : wpgmapptiy_maps_loaded'.$map['id'].'});';
  $content .= '}'."\n";
  $content .= 'else {'."\n";*/
  
  $content .= '}'."\n";
  $content .= "jQuery(document).ready(function() {\n";
  $content .= '  wpgmappity_maps_loaded'.$map['id'].'();'."\n";
  $content .= "})\n";
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
	  $html = str_replace("\n", '', nl2br($marker['marker_text']));
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
  switch($map['map_type']) {
  case 'hybrid' :
    return 'HYBRID';
    break;

  case 'normal' :
    return 'ROADMAP';
    break;

  case 'satellite' :
    return 'SATELLITE';
    break;

  case 'terrain' :
    return 'TERRAIN';
    break;
  }

}

function wpgmappity_shortcode_controls($map) {
  switch ($map['map_controls']) {
  case 'large' :
  $content .= 'wpgmappitymap'.$map['id'].'.addControl(new GLargeMapControl3D());'."\n";
  break;
  case 'small' :
  $content .= 'wpgmappitymap'.$map['id'].'.addControl(new GSmallMapControl());'."\n";
  break;
  }
  return $content;
}

?>