
function wpgmappity_set_route_event(map,data) {

  var events = wpgmappity_set_route_container(map, data);
  events.addDestinationEvent();
  events.removeDestinationEvent();
  events.searchEvent(data,map);
  events.removeEvent(data,map);

}


function wpgmappity_set_route_container(map, data) {


  var events = {

    destinationHtml : function(count) {
      var text = '<li>' +
	'<div class="wpgmappity-destinationNumber"><p>' + (count + 1) + '</p></div>' +
	'<input type="text" size="45" class="wpgmappity-destinationSearch">' +
	'<div class="wpgmappity-destinationRemove wpgmappity-destinationRemoveOff"></div>' +
	'<br style="clear:both;" />' +
	'</li>';

      return text;
    },

    updateTerms : function() {
      var terms = [];
      data.route.points = [];
      jQuery("#wpgmappity-destinationList").find("li").each(
	function(index) {
	  var term = jQuery(this).find("input.wpgmappity-destinationSearch").val();
	  terms.push(term);
	  data.route.points.push(term);
	});
      return terms;
    },

    addDestinationEvent : function() {
      jQuery("#wpgmappity-destinationAdd").click(
	function(){
	  var count = jQuery("#wpgmappity-destinationList li").size();
	  jQuery("#wpgmappity-destinationList").append(events.destinationHtml(count));
	  return false;
	});
    },

    removeDestinationEvent : function() {
      /*
       * Mouseover events for remove button
       */

      jQuery("div.wpgmappity-destinationRemove").live('mouseover mouseout',
	function(event) {
	  if (event.type == 'mouseover') {
	    jQuery(this).removeClass('wpgmappity-destinationRemoveOff');
	    jQuery(this).addClass('wpgmappity-destinationRemoveHot');
	  }
	  else {
	    jQuery(this).removeClass('wpgmappity-destinationRemoveHot');
	    jQuery(this).addClass('wpgmappity-destinationRemoveOff');
	  }
	});

      /*
       * Remove event
       */

      jQuery("div.wpgmappity-destinationRemove").live('click',
	function(event) {
	  jQuery(this).parent().remove();
	  events.search(data, map);
	});
    },

    updateMapZoom : function() {
      data.map_zoom = map.getZoom();
      var position = (data.map_zoom + 1) * 20;

      jQuery('#wpgmappity_zoom_slider').slider( "option", "value", position );
      jQuery("#wpgmappity_zoom_slider_status").html(data.map_zoom);

      google.maps.event.removeListener(data.listeners.zoom);

    },

    updateMapCenter : function() {

      var center = map.getCenter();
      data.center_lat = center.lat();
      data.center_long = center.lng();

      var geoCodeRequest = {
	latLng : center
      };

      var geocoder = new google.maps.Geocoder;
      geocoder.geocode(geoCodeRequest,
		       function(result, status) {
			 if (status === 'OK') {
			   var center_address = result[0].formatted_address;
			   data.map_address = center_address;
			   jQuery("#wpgmappity_center_point").val(center_address);
			 }
		       });

      google.maps.event.removeListener(data.listeners.center);

    },

    removeEvent : function() {
      jQuery("#wpgmappity-destination_remove").click(
	function(){
	  data.route.display.setMap(null);
	  data.route.active = '0';
	  return false;
	});
    },

    searchEvent : function(data,map) {
      jQuery("#wpgmappity-destination_submit").click(
	function(){
	  events.search(data, map);
	  return false;
	});
    },

    search : function(data, map) {
      jQuery("#wpgmappity-route-flash").html('');
      var terms = events.updateTerms(data);

      data.route.display.setMap(map);

      if (terms.length > 2 ) {
	var points = terms.slice(1, -1);
	var waypoints = [];
	for (var x in points) {
	  var y = {
	    'location' : points[x],
	    'stopover' : true
	  };
	  waypoints.push(y);
	}
      }
      else {
	var waypoints = [];
      }
      var origin = terms.splice(0,1).join('');
      var destination = terms.splice(-1,1).join('');
      var request = {
	origin: origin,
	destination: destination,
	waypoints : waypoints,
	travelMode: google.maps.DirectionsTravelMode.DRIVING
      };
      data.route.service.route(request,
	function(result, status) {
	  if (status == google.maps.DirectionsStatus.OK) {

	    data.listeners.zoom = google.maps.event.addListener(map, 'zoom_changed',
	    function() {
	      events.updateMapZoom();
	    });
	    data.listeners.center = google.maps.event.addListener(map, 'center_changed',
	    function() {
	      events.updateMapCenter();
	    });

	    data.route.active = '1';
	    data.route.display.setDirections(result);
	  }
	  else {
	    jQuery("#wpgmappity-route-flash").html("Your search failed. Try new points.");
	  }
	});
      }

    };

  return events;
}