function wpgmappity_street_control_activate(map, data) {

  var streetPositionSelection = jQuery("#wpgmappity_controls_street_position").val();
  var streetPosition = wpgmappity_control_position_selection(streetPositionSelection);
  data.controls.street.position = streetPositionSelection;

  var mapOptions =
    {
      streetViewControl: true,
      streetViewControlOptions:
      {
	position : streetPosition
      }
    };
  map.setOptions(mapOptions);
}


function wpgmappity_set_street_control_event(map, data) {
  // event listener for check box
  jQuery("#wpgmappity_controls_street_on").change(function()
    {
      /*
       * Set checkmark checked event
       */
      if ( jQuery(this).is(':checked') )
      {
	data.controls.street.active = true;
	wpgmappity_street_control_activate(map, data);
      }
      /*
       * Set checkmark unchecked event
       */
      else
      {
        data.controls.street.active = false;
	var mapOptions =
	  {
	    streetViewControl: false
	  };
        map.setOptions(mapOptions);
      }
    });

  /*
   * Event listener for dropdown changes
   */

  jQuery("#wpgmappity_controls_street_position").change(function()
    {
      /*
       * If the control is "on"
       */
      if ( jQuery("#wpgmappity_controls_street_on").is(':checked') )
      {
	data.controls.street.active = true;
	wpgmappity_street_control_activate(map, data);
      }
    });

}