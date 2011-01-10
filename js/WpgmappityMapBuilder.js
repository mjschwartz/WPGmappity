function WpgmappityMapBuilder(options) {
    this.mapContainer = options.mapContianer;
    this.mapWidth = options.mapWidth;
    this.mapLength = options.mapLength;
}

WpgmappityMapBuilder.prototype.setMapSize = function() {
    var mapBuilder = this;
    
    jQuery("#" + mapBuilder.mapContainer).animate(
    {
      "width" : mapBuilder.mapWidth,
      "height" : mapBuilder.mapHeight
    },
    function() {
    	       map.checkResize();
	       }
  );
}


var WpgmappityDataDefaults = (function() {

    var data = {
    'map_length': 450,
    'map_height': 300,
    'map_zoom' : 3,
    'center_lat' : '39.185575',
    'center_long' : '-96.575206',
    'markers' : [],
    'map_type' : 'normal',
    'alignment' : 'none',
    'controls' : 'none',
    'controls_object' : '',
    'map_address' : '',
    'slider_object' : ''
    };

    return data;

})();


var WpgmappityModule = (function ($, map) {

    var data = WpgmappityDataDefaults();




}(jQuery, map));