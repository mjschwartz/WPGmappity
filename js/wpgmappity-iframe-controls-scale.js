function wpgmappity_scale_control_activate(map, data) {

  var scalePositionSelection = jQuery("#wpgmappity_controls_scale_position").val();
  var scalePosition = wpgmappity_control_position_selection(scalePositionSelection);
  data.controls.scale.position = scalePositionSelection;

  var mapOptions =
    {
      scaleControl: true,
      scaleControlOptions:
      {
	position : scalePosition
      }
    };
  map.setOptions(mapOptions);
}


function wpgmappity_set_scale_control_event(map, data) {
  // event listener for check box
  jQuery("#wpgmappity_controls_scale_on").change(function()
    {
      /*
       * Set checkmark checked event
       */
      if ( jQuery(this).is(':checked') )
      {
	data.controls.scale.active = true;
	wpgmappity_scale_control_activate(map, data);
      }
      /*
       * Set checkmark unchecked event
       */
      else
      {
        data.controls.scale.active = false;
	var mapOptions =
	  {
	    scaleControl: false
	  };
        map.setOptions(mapOptions);
      }
    });

  /*
   * Event listener for dropdown changes
   */

  jQuery("#wpgmappity_controls_scale_position").change(function()
    {
      /*
       * If the control is "on"
       */
      if ( jQuery("#wpgmappity_controls_scale_on").is(':checked') )
      {
	data.controls.scale.active = true;
	wpgmappity_scale_control_activate(map, data);
      }
    });

}