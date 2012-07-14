<?php


function wpgmappity_load_theme_scripts() {

  wp_enqueue_script( 'jquery' );
  $gmap_url = 'http://maps.google.com/maps/api/js?sensor=false';
  wp_enqueue_script( 'gmap_loader', $gmap_url );
}

add_action( 'wp_print_scripts', 'wpgmappity_theme_public_scripts' );
add_action( 'admin_enqueue_scripts', 'wpgmappity_load_theme_scripts' );



function wpgmappity_theme_public_scripts() {

  if (wpgamppity_map_present_in_post()) {
    wpgmappity_load_theme_scripts();
  }
}

function wpgamppity_map_present_in_post() {

  return true;
}


add_shortcode('wpgmappity', 'wpgmappity_shortcode_handle');

function wpgmappity_shortcode_handle($attr) {
  global $post;

  $map = wgmappity_get_meta_data($attr['id']); 
  $map = $map[0];
  $content = wpgmappity_shortcode_container_div($map);
  $content .=  wpgmappity_shortcode_mapjs($map); 
  return $content;
}

function wpgmappity_shortcode_float($map) {
  $content = '';  

  if ( ($map['alignment'] == 'right') || ($map['alignment'] == 'left') ) {
    $content .= 'float:'.$map['alignment'].';">';
  }
  elseif ($map['alignment'] == 'center') {
    $content .= 'margin-left:auto;margin-right:auto;">';
  }
  else {
    $content .= '">';
  }

  return $content;

}

function wpgmappity_shortcode_container_div($map) {
  $content = '<div style="width:'.$map['map_length'].'px;';
  $content .=  wpgmappity_shortcode_float($map);

  $content .= '<div class="wpgmappity_container" id="wpgmappity-map-'.$map['id'].'"';
  $content .= ' style="width:'.$map['map_length'].'px;';
  $content .= 'height:'.$map['map_height'].'px;';
  $content .=  wpgmappity_shortcode_float($map);
  $content .= '</div>';
  if ($map['promote'] == true) {
    $content .= '<p style="text-align: center; font-size: 70%; margin: 0pt;" id="wpgmappity_promote_text">Google Maps for WordPress by WPGmappity</p>';
  }
  $content .= '</div>';

  return $content;
}


function wpgmappity_shortcode_mapjs($map) {
  $content = '<script type="text/javascript">'."\n";
  $content .= 'function wpgmappity_maps_loaded'.$map['id'].'() {'."\n";
  // center point
  $content .= "var latlng = new google.maps.LatLng(".$map['center_lat'].",".$map['center_long'].");\n";
  // inital options
  $content .= "var options = {\n";
  $content .= "  center : latlng,\n";
  $content .= "  scrollwheel :  ".wpgmappity_shortcode_scroll($map).",\n";
  $content .= "  mapTypeId: google.maps.MapTypeId.".wpgmappity_shortcode_maptype($map).",\n";
  $content .= wpgmappity_shortcode_controls(unserialize(base64_decode($map['map_controls'])));
  $content .= "  zoom : ".$map['map_zoom']."\n";
  $content .= "};\n";

  $content .= 'var wpgmappitymap'.$map['id'].' = ';
  $content .= "new google.maps.Map(document.getElementById(";
  $content .= "'wpgmappity-map-".$map['id']."'), options);\n";
 
  if (wpgmappity_has_markers($map['id']) != '0') {  
    $content .= wpgmappity_shortcode_markers_js($map['id']);
  }
  $map_name = 'wpgmappitymap'.$map['id'];
  $content .= wpgmappity_shorcode_route(unserialize(base64_decode($map['route'])),$map_name);
  
  $content .= '}'."\n";
  /* Crazy IE 7 bug - wrapping in document.ready blows up the script if adminbar is present
   * work around for the time being
  $content .= "jQuery(document).ready(function() {\n";
  $content .= '  wpgmappity_maps_loaded'.$map['id'].'();'."\n";
  $content .= "});\n";
  
  $content .= 'wpgmappity_maps_loaded'.$map['id'].'();'."\n";

  */
  $content .= "jQuery(window).load(function() {\n";
  $content .= '  wpgmappity_maps_loaded'.$map['id'].'();'."\n";
  $content .=  "});\n";

  $content .= '</script>';

  return $content;
}

function wpgmappity_shortcode_markers_js($map_id) {
  $markers = wpgmappity_retrieve_markers($map_id);
  $i = 0;
  $content = '';
  foreach($markers as $marker) {
    $marker_name = "marker".$map_id."_".$i;
    $map_name = "wpgmappitymap".$map_id;

    $content .= "var point$map_id_$i = new google.maps.LatLng(";
    $content .= $marker['marker_lat'].",".$marker['marker_long'].");\n"; 
    $content .= "var $marker_name = new google.maps.Marker({\n";
    if ( isset($marker['marker_image']) && 
      ($marker['marker_image'] != 'default') &&
      ($marker['marker_image'] != '' ) ) {
      
      $content .= "  icon : '".$marker['marker_image']."',\n";
    }
    $content .= "  position : point$map_id_$i,\n";
    $content .= "  map : ".$map_name."\n";
    $content .= "  });\n";

    if ($marker['marker_text'] != '') {
      if ($marker['marker_url'] != '') {
	$html = '<a href="'.$marker['marker_url'].'">'.$marker['marker_text'].'</a>';
      }
      else {
	$html = str_replace("\n", '', nl2br($marker['marker_text']));
	$html = addslashes($html);
      }
      
      $content .= "google.maps.event.addListener($marker_name,'click',\n";
      $content .= "  function() {\n";
      $content .= "    var infowindow = new google.maps.InfoWindow(\n";
      $content .= "    {content: '$html'});\n";
      $content .= "    infowindow.open($map_name,$marker_name);\n";
      $content .= "    });\n";
	
    }
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



function wpgmappity_shortcode_scroll($map) {
  if ($map['scroll'] == 'no_scroll') {
    return "false";
  }
  else {
    return "true";
  }
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

function wpgmappity_shortcode_controls($controls) {
  $content = wpgmappity_shortcode_zoom($controls['zoom']);
  $content .= wpgmappity_shortcode_type($controls['type']);
  $content .= wpgmappity_shortcode_scale($controls['scale']);
  $content .= wpgmappity_shortcode_streetview($controls['street']);
  $content .= '  panControl : false,';

  return $content;

}

function wpgmappity_shortcode_streetview($control) {
  if ($control['active'] == false) {
    return "  streetViewControl : false,\n";
  }

  else {
    $content = "  streetViewControl : true,\n";
    $content .= "  streetViewControlOptions :\n";
    $content .= "    {\n";
    $content .= "    position: ".wpgmappity_shortcode_control_position($control['position'])."\n";
    $content .= "    },\n";
    return $content;
  }
}

function wpgmappity_shortcode_scale($control) {
  if ($control['active'] == false) {
    return "  scaleControl : false,\n";
  }

  else {
    $content = "  scaleControl : true,\n";
    $content .= "  scaleControlOptions :\n";
    $content .= "    {\n";
    $content .= "    position: ".wpgmappity_shortcode_control_position($control['position'])."\n";
    $content .= "    },\n";
    return $content;
  }
}

function wpgmappity_shortcode_type($control) {
  if ($control['active'] == false) {
    return "  mapTypeControl : false,\n";
  }

  else {
    $content = "  mapTypeControl : true,\n";
    $content .= "  mapTypeControlOptions :\n";
    $content .= "    {\n";
    $content .= "    style: ".wpgmappity_shortcode_type_control_style_selection($control['style']).",\n";
    $content .= "    position: ".wpgmappity_shortcode_control_position($control['position'])."\n";
    $content .= "    },\n";
    return $content;
  }
}

function wpgmappity_shortcode_zoom($control) {
    if ($control['active'] == false) {
    return "  zoomControl : false,\n";
  }

  else {
    $content = "  zoomControl : true,\n";
    $content .= "  zoomControlOptions :\n";
    $content .= "    {\n";
    $content .= "    style: ".wpgamppity_shortcode_zoom_control_style_selection($control['style']).",\n";
    $content .= "    position: ".wpgmappity_shortcode_control_position($control['position'])."\n";
    $content .= "    },\n";
    return $content;
  }
}


function wpgmappity_shortcode_type_control_style_selection($selection) {
  switch($selection) {

  case 'DROPDOWN_MENU' :
    return 'google.maps.MapTypeControlStyle.DROPDOWN_MENU';
    break;

  case 'HORIZONTAL_BAR' :
    return 'google.maps.MapTypeControlStyle.HORIZONTAL_BAR';
    break;
  }

  return false;
}

function wpgamppity_shortcode_zoom_control_style_selection($selection) {
  switch($selection) {

  case 'SMALL' :
    return 'google.maps.ZoomControlStyle.SMALL';
    break;

  case 'LARGE' :
    return 'google.maps.ZoomControlStyle.LARGE';
    break;
  }

  return false;
}

/*
 * Ensure a properly formatted position specification
 * Usually not necessary, but don' trust user input and all that
 */

function wpgmappity_shortcode_control_position($position) {
  switch($position) {
  case 'TOP_RIGHT' :
    return 'google.maps.ControlPosition.TOP_RIGHT';
    break;

  case 'TOP_CENTER' :
    return 'google.maps.ControlPosition.TOP_CENTER';
    break;

  case 'TOP_LEFT' :
    return 'google.maps.ControlPosition.TOP_LEFT';
    break;

  case 'RIGHT_TOP' :
    return 'google.maps.ControlPosition.RIGHT_TOP';
    break;

  case 'RIGHT_CENTER' :
    return 'google.maps.ControlPosition.RIGHT_CENTER';
    break;

  case 'RIGHT_BOTTOM' :
    return 'google.maps.ControlPosition.RIGHT_BOTTOM';
    break;

  case 'BOTTOM_RIGHT' :
    return 'google.maps.ControlPosition.BOTTOM_RIGHT';
    break;

  case 'BOTTOM_CENTER' :
    return 'google.maps.ControlPosition.BOTTOM_CENTER';
    break;

  case 'BOTTOM_LEFT' :
    return 'google.maps.ControlPosition.BOTTOM_LEFT';
    break;

  case 'LEFT_TOP' :
    return 'google.maps.ControlPosition.LEFT_TOP';
    break;

  case 'LEFT_CENTER' :
    return 'google.maps.ControlPosition.LEFT_CENTER';
    break;

  case 'LEFT_BOTTOM' :
    return 'google.maps.ControlPosition.LEFT_BOTTOM';
    break;

  }
}

function wpgmappity_shorcode_route($route, $map_name) {
  // no route
  if ( (!isset($route['active'])) || ($route['active'] == '0') ) {
    return '';
  }

  else {
    $content = "var service = new google.maps.DirectionsService();\n";
    $content .= "var display = new google.maps.DirectionsRenderer();\n";
    $content .= "var x;\n";
    $content .= "var terms = ".json_encode($route['points']).";\n";
    $content .= "display.setMap($map_name);

      var waypoints = [];
      if (terms.length > 2 ) {
	var points = terms.slice(1, -1);
	for (x in points) {
	  var y = {
	    'location' : points[x],
	    'stopover' : true
	  };
	  waypoints.push(y);
	}
      }
      else {
	waypoints = [];
      }
      var origin = terms.splice(0,1).join('');
      var destination = terms.splice(-1,1).join('');
      var request = {
	origin: origin,
	destination: destination,
	waypoints : waypoints,
	travelMode: google.maps.DirectionsTravelMode.DRIVING
      };\n";
    $content .= "service.route(request,
	function(result, status) {
	  if (status == google.maps.DirectionsStatus.OK) {
            display.setDirections(result);
          }
        });";
    return $content;
  }

}