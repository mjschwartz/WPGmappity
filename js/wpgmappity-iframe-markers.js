
function wpgmappity_delete_marker_callback(data, map, marker) {
  return function()
  {
    var marker_num = jQuery(this).attr("id");
    marker_num = marker_num.split("wgmappity_delete_marker_");
    marker_num = parseInt(marker_num[1],10) - 1;
    data.markers[marker_num].marker_object.setMap(null);
    jQuery(this).parent().parent().remove();
    data.markers.splice(marker_num, 1);
  };
}

function wpgmappity_clean_configure_marker_frame() {
  jQuery("#wgmappity_marker_configure_text").val('');
  jQuery("#wgmappity_marker_configure_url").val('');
  jQuery("#wgmappity_marker_configure_link").attr('checked', false);

  return true;

}

function wpgmappity_marker_event_callback(data, map, marker, marker_id) {
  return function(latlng) {
    var html = "";
    if (data.markers[marker_id].marker_url !== undefined)
    {
      html += "<a href='" + data.markers[marker_id].marker_url + "'>";
    }
    html += data.markers[marker_id].marker_text.replace(/\n/g,'<br />');
    if (data.markers[marker_id].marker_url !== undefined)
    {
      html += "</a>";
    }
    //var html = data.markers[marker_id].marker_text;

    var infowindow = new google.maps.InfoWindow(
      {
	content: html
      });
    infowindow.open(map,marker);
  };
}

function wpgamppity_rebuild_marker(data, map, marker, marker_id) {

  try {
    var mapobject = data.markers[parseInt(marker_id,10)].marker_object;
    mapobject.setMap(null);
    }
  catch(err) {
  }
  var newMarker = new google.maps.Marker(
    {
      position: data.markers[marker_id].point,
      map: map
    });
  if (data.markers[marker_id].image !== 'default') {
    newMarker.setIcon(data.markers[marker_id].image);
  }

  google.maps.event.addListener(newMarker,
				'click',
				wpgmappity_marker_event_callback(data, map, newMarker, marker_id)
			       );
  data.markers[marker_id].marker_object = newMarker;
}

function wpgmappity_configure_marker_submit_callback(data, map) {
  return function() {

    var marker_id = ( jQuery("#wgmappity_marker_configure_id").val());
    var marker = data.markers[marker_id];
    data.markers[marker_id].marker_text = jQuery("#wgmappity_marker_configure_text").val();
    wpgamppity_rebuild_marker(data, map, marker, marker_id);
    wpgmappity_clean_configure_marker_frame();
    jQuery("#wgmappity_marker_configure_text").val('');
    tb_remove();
    return false;
  };
}

function wpgmappity_configure_marker_callback(data, map, marker, marker_number) {
  return function() {
    jQuery("#wgmappity_marker_configure_id").val(marker_number);
    jQuery("#wgmappity_marker_configure_text").val('');
    if (data.markers[marker_number] !== undefined) {
    jQuery("#wgmappity_marker_configure_text").val(data.markers[marker_number].marker_text);

    }
    tb_show('Marker Configuration',"#TB_inline?height=220&width=475&inlineId=wgmappity_marker_configure_dialog", null);
 };
}

function wpgmappity_add_marker_to_list(marker_data, marker, data, map) {
  var text = "<div class='wpgmappity_marker_on_map'>";
  var delete_marker_id =  "wgmappity_delete_marker_" + data.markers.length;
  var configure_marker_id =  "wgmappity_configure_marker_" + data.markers.length;
  text += "<p class='wpgmappity_delete_marker'><a id='" + configure_marker_id + "' href='#configure_marker'>Configure</a> | ";
  text += "<a id='" + delete_marker_id + "' href='#delete_marker'>Remove</a></p>";
  text += "<p>" + marker_data.address + "</p>";
  text += "</div>";
  jQuery("#wpgamppity_add_marker_container").before(text);
  jQuery("#" + delete_marker_id).click(wpgmappity_delete_marker_callback(data, map, marker));
  jQuery("#" + configure_marker_id).click(wpgmappity_configure_marker_callback(data, map, marker, (data.markers.length-1)));
}

function wpgmapity_add_marker_to_map(data, map, response) {
  jQuery("#wpgmappity_marker_point").val('');
  jQuery("#wpgmappity_marker_point_latlng").val('');
  var image, marker;

  var point = new google.maps.LatLng(response[0].geometry.location.lat(),
				    response[0].geometry.location.lng());

  if (jQuery("#wpgmappity_marker_default_image").is(':checked')) {
    image = 'default';
    marker = new google.maps.Marker(
      {
	position: point,
	map: map
      }
    );
  }


  if (jQuery("#wpgmappity_marker_custom_image").is(':checked')) {
    image = jQuery("#wpgmappity_marker_custom_image_url").val();

    marker = new google.maps.Marker(
      {
	position: point,
	map: map,
	icon : image
      }
    );
  }
  var marker_data = {
    'marker_object' : marker,
    'address' : response[0].formatted_address,
    'point' : point,
    'lat' : response[0].geometry.location.lat(),
    'long' : response[0].geometry.location.lng(),
    'image' : image
    };
  data.markers.push(marker_data);
  wpgmappity_add_marker_to_list(marker_data, marker, data, map);
}

function wpgmappity_geocode_from_latlng(map, data) {
  return function(response, status) {
    if (status === 'OK') {
      tb_remove();
      wpgmapity_add_marker_to_map(data, map, response);
    }
  };
}


function wpgmappity_set_marker_display_events() {
/*
  jQuery('input[name="wpgmappity_marker_find"]').live('change',
    function() {
      jQuery(this).parent().find('label').each(
	function(index, domEle) {
	  jQuery(domEle).addClass('grey-out');
	});
      var name = jQuery(this).attr('id');
      jQuery('label[for=' + name + ']').removeClass('grey-out');
return false;
    });

   jQuery('input[name="wpgmappity_marker_image"]').live('change',
    function() {
      if ( jQuery("#wpgmappity_marker_default_image").is(':checked') ) {
	jQuery('#wpgmappity_marker_custom_image_text').addClass('grey-out');
	jQuery('#wpgmappity_marker_default_image_text').removeClass('grey-out');
	jQuery('#wpgmappity_marker_custom_image_container').hide();

	jQuery("#wpgmappity-default-img").show();
	jQuery("#wpgmappity-custom-img").hide();
      }
      if ( jQuery("#wpgmappity_marker_custom_image").is(':checked') ) {
	jQuery('#wpgmappity_marker_custom_image_text').removeClass('grey-out');
	jQuery('#wpgmappity_marker_default_image_text').addClass('grey-out');
	jQuery('#wpgmappity_marker_custom_image_container').show();
      }
      return false;
      });

  jQuery("#wpgmappity_marker_image_preview").live('click',
    function() {
      jQuery("#wpgmappity-custom-img").attr('src',
	function() {
	    return jQuery("#wpgmappity_marker_custom_image_url").val();
	});
      jQuery("#wpgmappity-default-img").hide();
      jQuery("#wpgmappity-custom-img").show();
 return false;
    });
*/
}