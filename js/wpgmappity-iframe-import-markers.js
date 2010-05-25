
function wpgmappity_import_markers_response(map, data, marker_data, marker_num) {
  return function(response) {
    if (response.Status.code == 200) {
       wpgmapity_add_marker_to_map(data, map, response);
       if (marker_data.marker_text != '') {
	 data.markers[marker_num].marker_text = marker_data.marker_text;
	 if (marker_data.marker_url != '') {
	   data.markers[marker_num].marker_url = marker_data.marker_url;
	 }	   
	 wpgamppity_rebuild_marker(data, map, data.markers[marker_num].marker_object, marker_num);
       }
    }
  }
}


function wpgmappity_import_build_markers(map, data) {

  var markers = wpgmappity_import_markers();
  for (x in markers) {
    var geocoder = new GClientGeocoder();
    geocoder.getLocations(new GLatLng(markers[x].marker_lat, markers[x].marker_long), 
    			  wpgmappity_import_markers_response(map, data, markers[x], x) );
  }

}