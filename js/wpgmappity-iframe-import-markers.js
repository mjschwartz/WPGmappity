
function wpgmappity_import_markers_response(map, data, marker_data, marker_num) {
  return function(response, status) {
    if (status === 'OK') {
      if (marker_data.marker_image === 'default') {
	jQuery("#wpgmappity_marker_default_image").attr('checked', true);
      }
      if ( (marker_data.marker_image !== '') && (marker_data.marker_image !== 'default') ) {
	jQuery("#wpgmappity_marker_custom_image").attr('checked', true);
	jQuery("#wpgmappity_marker_custom_image_url").val(marker_data.marker_image);
      }


       wpgmapity_add_marker_to_map(data, map, response);
       if (marker_data.marker_text !== '') {
	 data.markers[marker_num].marker_text = marker_data.marker_text;
	 if (marker_data.marker_url !== '') {
	   data.markers[marker_num].marker_url = marker_data.marker_url;
	 }
	 wpgamppity_rebuild_marker(data, map, data.markers[marker_num], marker_num);
       }
    }
  };
}


function wpgmappity_import_build_markers(map, data) {

  var markers = wpgmappity_import_markers();
  var x;
  for (x in markers) {

    var point = new google.maps.LatLng(markers[x].marker_lat,markers[x].marker_long);
    var geoCodeRequest = {
      latLng : point
      };

    var geocoder = new google.maps.Geocoder;
    geocoder.geocode(geoCodeRequest, wpgmappity_import_markers_response(map, data, markers[x], x));

  }

}