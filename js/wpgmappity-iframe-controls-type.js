
function wpgamppity_type_control_style_selection(selection) {
  switch(selection) {

  case 'DROPDOWN_MENU' :
    return google.maps.MapTypeControlStyle.DROPDOWN_MENU;
    break;

  case 'HORIZONTAL_BAR' :
    return google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
    break;
  }

  return false;
}

function wpgmappity_type_control_activate(map, data) {
  var typeStyleSelection = jQuery("#wpgmappity_controls_type_style").val();
  var typeStyle =  wpgamppity_type_control_style_selection(typeStyleSelection);
  data.controls.type.style = typeStyleSelection;

  var typePositionSelection = jQuery("#wpgmappity_controls_type_position").val();
  var typePosition = wpgmappity_control_position_selection(typePositionSelection);
  data.controls.type.position = typePositionSelection;

  var mapOptions =
    {
      mapTypeControl: true,
      mapTypeControlOptions:
      {
	style: typeStyle,
	position : typePosition
      }
    };
  map.setOptions(mapOptions);
}


function wpgmappity_set_type_control_event(map, data) {
  // event listener for check box
  jQuery("#wpgmappity_controls_type_on").change(function()
    {
      /*
       * Set checkmark checked event
       */
      if ( jQuery(this).is(':checked') )
      {
	data.controls.type.active = true;
	wpgmappity_type_control_activate(map, data);
      }
      /*
       * Set checkmark unchecked event
       */
      else
      {
        data.controls.type.active = false;
	var mapOptions =
	  {
	    mapTypeControl: false
	  };
        map.setOptions(mapOptions);
      }
    });

  /*
   * Event listener for dropdown changes
   */

  jQuery("#wpgmappity_controls_type_style").change(function()
    {
      /*
       * If the control is "on"
       */
      if ( jQuery("#wpgmappity_controls_type_on").is(':checked') )
      {
	data.controls.type.active = true;
	wpgmappity_type_control_activate(map, data);
      }
    });

  jQuery("#wpgmappity_controls_type_position").change(function()
    {
      /*
       * If the control is "on"
       */
      if ( jQuery("#wpgmappity_controls_type_on").is(':checked') )
      {
	data.controls.type.active = true;
	wpgmappity_type_control_activate(map, data);
      }
    });

}