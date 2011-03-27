
function wpgmappity_import_markers_response(map, data, marker_num) {
  return function(response, status) {
    if (status === 'OK') {
      var markers = wpgmappity_import_markers();
      var marker_data = markers[marker_num];
      var marker;
      if (marker_data.marker_image === 'default') {
	marker = new google.maps.Marker(
	  {
	    position: response[0].geometry.location,
	    map: map
	  }
	);
      }
      if ( (marker_data.marker_image !== '') && (marker_data.marker_image !== 'default') ) {

	marker = new google.maps.Marker(
	  {
	    position: response[0].geometry.location,
	    map: map,
	    icon : marker_data.marker_image
	  }
	);
      }

      var new_marker = {
	'marker_object' : marker,
	'address' : response[0].formatted_address,
	'point' : response[0].geometry.location,
	'lat' : response[0].geometry.location.lat(),
	'long' : response[0].geometry.location.lng(),
	'image' : marker_data.marker_image
      };
      if (marker_data.marker_text !== '') {
	new_marker.marker_text = marker_data.marker_text;
	}
      if (marker_data.marker_url !== '') {
	new_marker.marker_url = marker_data.marker_url;
	}

      data.markers.push(new_marker);
      wpgmappity_add_marker_to_list(new_marker, marker, data, map);


      if (marker_data.marker_text !== '') {

	google.maps.event.addListener(marker,
	'click',
	function() {
	  var html = "";
	  if (marker_data.marker_url !== undefined)
	  {
	    html += "<a href='" + marker_data.marker_url + "'>";
	  }
	  html += marker_data.marker_text.replace(/\n/g,'<br />');
	  if (marker_data.marker_url !== undefined)
	  {
	    html += "</a>";
	  }
	  var infowindow = new google.maps.InfoWindow(
	    {
	      content: html
	    });
	  infowindow.open(map,marker);
	}
	  );
      }
    }
  };
}


function wpgmappity_import_build_markers(map, data) {

  var markers = wpgmappity_import_markers();
  var x;
  for (x=0;x<markers.length;x++) {

    var point = new google.maps.LatLng(markers[x].marker_lat,markers[x].marker_long);
    var geoCodeRequest = {
      latLng : point
      };

    var geocoder = new google.maps.Geocoder();
    //console.log(markers[x])
    geocoder.geocode(geoCodeRequest, wpgmappity_import_markers_response(map, data, x));

  }

}