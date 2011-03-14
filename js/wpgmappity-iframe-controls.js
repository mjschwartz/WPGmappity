
// utility function for getting the Position property
// same as the field name, but its user input and all that
function wpgmappity_control_valid_position(choice) {
    var valid_choices = [
        'TOP_RIGHT', 
        'TOP_CENTER', 
        'TOP_LEFT', 
        'RIGHT_TOP',
        'RIGHT_CENTER',
        'RIGHT_BOTTOM',
        'BOTTOM_RIGHT',
        'BOTTOM_CENTER',
        'BOTTOM_LEFT',
        'LEFT_TOP',
        'LEFT_CENTER',
        'LEFT_BOTTOM'
    ];
    
    if (jQuery.inArray(choice, valid_choices) != -1 ) {
        return choice;
    }
    else {
        return false;
    }
}


function wpgmappity_set_zoom_control_event(map, data) {

    jQuery("#wpgmappity_controls_zoom_on").change(function(map, data) {
        if ( jQuery(this).is(':checked') ) {
            alert("CHECKED")
        }
        else {
            alert("UNCHECKED")
        }
    })
}


function wpgmappity_set_controls_event(map, data) {
    wpgmappity_set_zoom_control_event(map, data);


  /*
   *
  jQuery("input[name='wpgmappity_controls']").click(function(){
    data.controls = jQuery(this).attr("value");
    wpgmappity_draw_controls(map, data);
  });
   */
}

function wpgmappity_draw_controls(map, data) {
    var control;
    switch (data.controls) {

    case 'none' :
    	if (data.controls_object != '') {
    	    map.removeControl(data.controls_object);
	    data.controls_object = '';
	}
	data.controls = 'none';
    	break;
    case 'small' :
    	 if (data.controls_object != '') {
    	    map.removeControl(data.controls_object);
	    data.controls_object = '';
	}
    	 control = new GSmallMapControl();
	 map.addControl(control);
	 data.controls_object = control;
	 break;
    case 'large' :
    	 if (data.controls_object != '') {
    	    map.removeControl(data.controls_object);
	    data.controls_object = '';
	}
    	 control = new GLargeMapControl3D();
	 map.addControl(control);
	 data.controls_object = control;
	 break;
    }
}