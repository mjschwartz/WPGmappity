
// utility function for getting the Position property
// same as the field name, but its user input and all that
function wpgmappity_control_position_selection(choice) {
  switch(choice) {
  case 'TOP_RIGHT' :
    return google.maps.ControlPosition.TOP_RIGHT;
    break;

  case 'TOP_CENTER' :
    return google.maps.ControlPosition.TOP_CENTER;
    break;

  case 'TOP_LEFT' :
    return google.maps.ControlPosition.TOP_LEFT;
    break;

  case 'RIGHT_TOP' :
    return google.maps.ControlPosition.RIGHT_TOP;
    break;

  case 'RIGHT_CENTER' :
    return google.maps.ControlPosition.RIGHT_CENTER;
    break;

  case 'RIGHT_BOTTOM' :
    return google.maps.ControlPosition.RIGHT_BOTTOM;
    break;

  case 'BOTTOM_RIGHT' :
    return google.maps.ControlPosition.BOTTOM_RIGHT;
    break;

  case 'BOTTOM_CENTER' :
    return google.maps.ControlPosition.BOTTOM_CENTER;
    break;

  case 'BOTTOM_LEFT' :
    return google.maps.ControlPosition.BOTTOM_LEFT;
    break;

  case 'LEFT_TOP' :
    return google.maps.ControlPosition.LEFT_TOP;
    break;

  case 'LEFT_CENTER' :
    return google.maps.ControlPosition.LEFT_CENTER;
    break;

  case 'LEFT_BOTTOM' :
    return google.maps.ControlPosition.LEFT_BOTTOM;
    break;

  default :
    return false;
  }
}

function wpgamppity_zoom_control_style_selection(selection) {
  switch(selection) {

  case 'SMALL' :
    return google.maps.ZoomControlStyle.SMALL;
    break;

  case 'LARGE' :
    return google.maps.ZoomControlStyle.LARGE;
    break;
  }

  return false;
}

function wpgmappity_zoom_control_activate(map, data) {
  var zoomStyleSelection = jQuery("#wpgmappity_controls_zoom_size").val();
  var zoomStyle =  wpgamppity_zoom_control_style_selection(zoomStyleSelection);
  data.controls.zoom.style = zoomStyleSelection;

  var zoomPositionSelection = jQuery("#wpgmappity_controls_zoom_position").val();
  var zoomPosition = wpgmappity_control_position_selection(zoomPositionSelection);
  data.controls.zoom.position = zoomPositionSelection;

  var mapOptions =
    {
      zoomControl: true,
      zoomControlOptions:
      {
	style: zoomStyle,
	position : zoomPosition
      }
    };
  map.setOptions(mapOptions);
}


function wpgmappity_set_zoom_control_event(map, data) {
  // event listener for check box
  jQuery("#wpgmappity_controls_zoom_on").change(function()
    {
      /*
       * Set checkmark checked event
       */
      if ( jQuery(this).is(':checked') )
      {
	data.controls.zoom.active = true;
	wpgmappity_zoom_control_activate(map, data);
      }
      /*
       * Set checkmark unchecked event
       */
      else
      {
        data.controls.zoom.active = false;
	var mapOptions =
	  {
	    zoomControl: false
	  };
        map.setOptions(mapOptions);
      }
    });

  /*
   * Event listener for dropdown changes
   */

  jQuery("#wpgmappity_controls_zoom_size").change(function()
    {
      /*
       * If the control is "on"
       */
      if ( jQuery("#wpgmappity_controls_zoom_on").is(':checked') )
      {
	data.controls.zoom.active = true;
	wpgmappity_zoom_control_activate(map, data);
      }
    });

  jQuery("#wpgmappity_controls_zoom_position").change(function()
    {
      /*
       * If the control is "on"
       */
      if ( jQuery("#wpgmappity_controls_zoom_on").is(':checked') )
      {
	data.controls.zoom.active = true;
	wpgmappity_zoom_control_activate(map, data);
      }
    });

}


function wpgmappity_set_controls_event(map, data) {
    wpgmappity_set_zoom_control_event(map, data);
    wpgmappity_set_type_control_event(map, data);
    wpgmappity_set_scale_control_event(map, data);
    wpgmappity_set_street_control_event(map, data);
}
