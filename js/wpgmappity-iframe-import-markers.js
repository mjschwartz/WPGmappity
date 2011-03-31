
function wpgmappity_marker_importer() {

  return {

    markerData : null,
    point : null,
    markerObject : null,
    response : null,
    map : null,
    data : null,
    status : null,

    setOptions : function(options) {
      this.point = options.point;
      this.map = options.map;
      this.data = options.data;
      this.markerData = options.markerData;
    },

    mark : function(x) {
      var wpgmGeocoder = this;
      setTimeout(wpgmGeocoder.geocode, (x*1000));
    },

    geocode : function() {
      var geoCodeRequest = {
	latLng : this.point
      };

      var geocoder = new google.maps.Geocoder();
      geocoder.geocode(geoCodeRequest, this.geocodeCallback());
    },

    geocodeCallback : function() {
      var wpgmGeocoder = this;
      return function(response, status) {
	if (status === 'OK') {
	  wpgmGeocoder.response = response[0];
	  wpgmGeocoder.addMarker();
	}

	// fake it till you make it:
	// if queries are happening too quickly geocoder api will throttle
	// below is fake data enough to build the map
	else {
	  var fakeGeometry = {
	    location : wpgmGeocoder.point,
	    lat : function() {
	      return wpgmGeocoder.markerData.marker_lat;
	    },
	    lng : function() {
	      return wpgmGeocoder.markerData.marker_long;
	    }
	  };
	  var fakeResponse = {
	    geometry : fakeGeometry,
	    formatted_address : "Throttled by Geocoder. Your data is safe. Can't be displayed here."
	  };
	  wpgmGeocoder.response = fakeResponse;
	  wpgmGeocoder.addMarker();
	}
      };
    },

    wpgmappityMarkerData : function() {

      var new_marker = {
	'marker_object' : this.markerObject,
	'address' : this.response.formatted_address,
	'point' : this.response.geometry.location,
	'lat' : this.response.geometry.location.lat(),
	'long' : this.response.geometry.location.lng(),
	'image' : this.markerData.marker_image
      };
      if (this.markerData.marker_text !== '') {
	new_marker.marker_text = this.markerData.marker_text;
      }
      if (this.markerData.marker_url !== '') {
	new_marker.marker_url = this.markerData.marker_url;
      }

      this.data.markers.push(new_marker);
      wpgmappity_add_marker_to_list(new_marker, this.markerObject, this.data, this.map);

    },

    addMarker : function() {
      var markerOptions;

      markerOptions = {
	position: this.response.geometry.location,
	map: this.map
      };

      //ensure older verions have marker_image set to default
      if ( (this.markerData.marker_image === 'default') || (this.markerData.marker_image === '') ) {
	this.markerData.marker_image = 'default';
      }
      else {
	markerOptions.icon = this.markerData.marker_image;
      }

      this.markerObject = new google.maps.Marker(markerOptions);

      if (this.markerData.marker_text !== '') {
	this.addTextPopUp();
      }
      this.wpgmappityMarkerData();
    },

    addTextPopUp : function() {
      var wpgmGeocoder = this;
      google.maps.event.addListener(this.markerObject, 'click',
	function() {
	  var html = "";
	  if ( (wpgmGeocoder.markerData.marker_url !== undefined) &&
	    (wpgmGeocoder.markerData.marker_url !== '') )

	    {
	      html += "<a href='" + this.markerData.marker_url + "'>";
	    }
	    html += wpgmGeocoder.markerData.marker_text.replace(/\n/g,'<br />');

	  if ( (wpgmGeocoder.markerData.marker_url !== undefined) &&
	    (wpgmGeocoder.markerData.marker_url !== '') )

	    {
	      html += "</a>";
	    }
	  var infowindow = new google.maps.InfoWindow(
	    {
	      content: html
	    });
	  infowindow.open(wpgmGeocoder.map,wpgmGeocoder.markerObject);
	}
      );
    }
  };
}

function wpgmappity_import_geocode_markers(map, data, markerData, x) {

    var options = {
      markerData : markerData,
      data : data,
      map : map,
      point : new google.maps.LatLng(markerData.marker_lat,markerData.marker_long)
    };

    var geocoder = wpgmappity_marker_importer();
    geocoder.setOptions(options);
    // slow down - will hit geocoder threshhold with large marker sets
    setTimeout(function(){geocoder.geocode();}, (x*1500));
}


function wpgmappity_import_build_markers(map, data) {

  var markers = wpgmappity_import_markers();
  var x;

  for (x=0;x<markers.length;x++) {
    wpgmappity_import_geocode_markers(map, data, markers[x], x);
  }

}