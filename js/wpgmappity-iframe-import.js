
function wpgmappity_import_center(map, data, imported_map) {
  data.center_lat = imported_map.center_lat;
  data.center_long = imported_map.center_long;
  if (imported_map.map_address != '') {
    jQuery("#wpgmappity_center_point").val(imported_map.map_address);
    data.map_address = imported_map.map_address;
  }
  wpgmappity_set_center(map,data);
}

function wpgmappity_import_promote(map, data, imported_map) {
  data.promote = imported_map.promote;

  if (data.promote == '1') {
    jQuery('#wpgmappity_promote').attr('checked', true);
    (wpgmappity_promotion_callback(map,data))();
  }
}


function wpgmappity_import_zoom(map, data, imported_map) {
  data.map_zoom = imported_map.map_zoom;
  map.setZoom(data.map_zoom);
  var position = (data.map_zoom * 20);
  position = parseInt( (position / 20) );
  jQuery("#wpgmappity_zoom_slider_status").html(position);
  jQuery('#wpgmappity_zoom_slider').slider( "option", "value", (data.map_zoom * 20) );
}

function wpgmappity_import_controls(map, data, imported_map) {
  if (imported_map.controls != '') {
    data.controls = imported_map.controls;
    if  (data.controls.scale.active == true) {
      jQuery("#wpgmappity_controls_scale_position").val(data.controls.scale.position);
      jQuery("#wpgmappity_controls_scale_on").attr('checked', 'checked');
      jQuery("#wpgmappity_controls_scale_on").trigger('change');
    }
    if  (data.controls.street.active == true) {
      jQuery("#wpgmappity_controls_street_position").val(data.controls.street.position);
      jQuery("#wpgmappity_controls_street_on").attr('checked', 'checked');
      jQuery("#wpgmappity_controls_street_on").trigger('change');
    }
    if  (data.controls.type.active == true) {
      jQuery("#wpgmappity_controls_type_style").val(data.controls.type.style);
      jQuery("#wpgmappity_controls_type_position").val(data.controls.type.position);
      jQuery("#wpgmappity_controls_type_on").attr('checked', 'checked');
      jQuery("#wpgmappity_controls_type_on").trigger('change');
    }
    if  (data.controls.zoom.active == true) {
      jQuery("#wpgmappity_controls_zoom_style").val(data.controls.zoom.style);
      jQuery("#wpgmappity_controls_zoom_position").val(data.controls.zoom.position);
      jQuery("#wpgmappity_controls_zoom_on").attr('checked', 'checked');
      jQuery("#wpgmappity_controls_zoom_on").trigger('change');
    }

/*
    switch(data.controls) {
       case 'small' :
       jQuery('#wpgmappity_controls_none').removeAttr('checked');
       jQuery('input:radio[name=wpgmappity_controls]:eq(1)').attr('checked', 'checked');
       var control = new GSmallMapControl();
       map.addControl(control);
       data.controls_object = control;
       break;
       case 'large' :
       jQuery('#wpgmappity_controls_none').removeAttr('checked');
       jQuery('input:radio[name=wpgmappity_controls]:eq(2)').attr('checked', 'checked');
       var control = new GLargeMapControl3D();
       map.addControl(control);
       data.controls_object = control;
       break;
     }
*/
  }
}

function wpgmappity_import_type(map, data, imported_map) {
  switch (imported_map.map_type) {
  case 'normal' :
  wpgmappity_change_map_type(map, data, 'normal');
  break;
  case 'satellite' :
  jQuery('#wpgmappity_selector_map_type_normal').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_selector_map_type]:eq(1)').attr('checked', 'checked');
  wpgmappity_change_map_type(map, data, 'satellite');
  break;
  case 'hybrid' :
  jQuery('#wpgmappity_selector_map_type_normal').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_selector_map_type]:eq(2)').attr('checked', 'checked');
  wpgmappity_change_map_type(map, data, 'hybrid');
  break;
  case 'terrain' :
  jQuery('#wpgmappity_selector_map_type_normal').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_selector_map_type]:eq(3)').attr('checked', 'checked');
  wpgmappity_change_map_type(map, data, 'terrain');
  break;
  }
}

function wpgmappity_import_alignment(data, imported_map) {

switch (imported_map.alignment) {
  case 'none' :
  data.alignment = 'none';
  break;
  case 'left' :
  data.alignment = 'left';
  jQuery('#wpgmappity_float_none').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_float]:eq(1)').attr('checked', 'checked');
  break;
  case 'center' :
  data.alignment = 'center';
  jQuery('#wpgmappity_float_none').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_float]:eq(2)').attr('checked', 'checked');
  break;
  case 'right' :
  data.alignment = 'right';
  jQuery('#wpgmappity_float_none').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_float]:eq(3)').attr('checked', 'checked');
  break;
  }
console.log(imported_map)
switch (imported_map.scroll) {
  case '' :
  data.scroll = 'scroll';
  break;
  case 'scroll' :
  data.scroll = 'scroll';
  break;
  case 'no_scroll' :
  data.scroll = 'no_scroll';
  jQuery('#wpgmappity_scroll_none').removeAttr('checked');
  jQuery('input:radio[name=wpgmappity_scroll]:eq(1)').attr('checked', 'checked');
  break;
  }
}

function wpgmappity_import_size(map, data, imported_map) {
  if ( (imported_map.map_height == '170') && (imported_map.map_length == '300') ) {
    data.map_height = 170;
    data.map_length = 300;
    jQuery('#wpgmappity_selector_size_medium').removeAttr('checked');
    jQuery('input:radio[name=wpgmappity_selector_size]:eq(0)').attr('checked', 'checked');
    }
  else if ( (imported_map.map_height == '300') && (imported_map.map_length == '450') ) {
    data.map_height = 300;
    data.map_length = 450;
    }
  else if ( (imported_map.map_height == '400') && (imported_map.map_length == '700') ) {
    data.map_height = 400;
    data.map_length = 700;
    jQuery('#wpgmappity_selector_size_medium').removeAttr('checked');
    jQuery('input:radio[name=wpgmappity_selector_size]:eq(2)').attr('checked', 'checked');
    }
  else {
    data.map_height = parseInt(imported_map.map_height);
    data.map_length = parseInt(imported_map.map_length);
    jQuery('#wpgmappity_selector_size_medium').removeAttr('checked');
    jQuery('input:radio[name=wpgmappity_selector_size]:eq(3)').attr('checked', 'checked');
  }
    wpgmappity_update_map_size(map, data);
}

function wpgmappity_import_route(map, data, imported_map) {
  data.route.points = imported_map.route.points;

  var events = wpgmappity_set_route_container(map, data);
  if (data.route.points.length > 2) {
    var length = data.route.points.length;
    for (var i = 3;i <= length;i++) {
      jQuery("#wpgmappity-destinationAdd").trigger('click');
    }
  }

  var point_containers = jQuery("#wpgmappity-destinationList").find("li");
  for (var x in data.route.points) {
    jQuery(point_containers[x]).find("input.wpgmappity-destinationSearch").val(data.route.points[x]);
  }

  events.search(data,map);

}


function wpgmappity_set_up_map(map_id, map, data) {
  var imported_map = wpgmappity_import_saved_map();
  wpgmappity_import_zoom(map, data, imported_map);
  wpgmappity_import_center(map, data, imported_map);
  if ( (typeof imported_map.controls !== 'undefined') && (imported_map.controls !== false) ) {
    wpgmappity_import_controls(map, data, imported_map);
    }
  wpgmappity_import_type(map, data, imported_map);
  wpgmappity_import_alignment(data, imported_map);
  wpgmappity_import_size(map, data, imported_map);
  wpgmappity_import_promote(map, data, imported_map);
  if (wpgmappity_marker_flag() == true) {
    wpgmappity_import_build_markers(map, data);
  }
  if ( (typeof imported_map.route !== 'undefined') &&
    (imported_map.route.active !== '0') && (imported_map.route !== false) )  {
    wpgmappity_import_route(map,data,imported_map);

  }
}