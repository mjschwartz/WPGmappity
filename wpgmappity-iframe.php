<?php 
function wpgmappity_iframe() {
  $post_id = esc_attr($_REQUEST['post_id']);
  $map_id =  esc_attr($_REQUEST['map_id']);
  $wgmappity_style_sheet = wpgmappity_plugin_url( 'styles/wpgmappity-iframe.css' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
<?php
wp_enqueue_script('thickbox');
wp_enqueue_style('thickbox');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script( 'wpgmappity-jquery-ui-slider', wpgmappity_plugin_url( 'js/jquery.ui.slider.js' ),
	array( 'jquery', 'jquery-ui-core' ) );
wp_enqueue_script( 'wpgmappity-json2', wpgmappity_plugin_url( 'js/json2.min.js' ) );
wp_enqueue_style( 'global' );
wp_enqueue_style( 'wp-admin' );
wp_enqueue_style( 'colors' );
wp_enqueue_style( 'media' );
wp_enqueue_style( 'ie' );
?>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time(); ?>'};
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'media-upload-popup', adminpage = 'media-upload-popup'; isRtl = <?php echo (int) is_rtl(); ?>;
//]]>
</script>
<?php
do_action('admin_enqueue_scripts', 'media-upload-popup');
do_action('admin_print_styles-media-upload-popup');
do_action('admin_print_styles');
do_action('admin_print_scripts-media-upload-popup');
do_action('admin_print_scripts');
do_action('admin_head-media-upload-popup');
do_action('admin_head');
?>
<link media="all" type="text/css" rel="stylesheet" href="<?php echo $wgmappity_style_sheet ?>" />
</head>
<body<?php if ( isset($GLOBALS['body_id']) ) echo ' id="' . $GLOBALS['body_id'] . '"'; ?>>

<?php if ($_GET['modify'] == 'select') { 
  if ($_GET['map-action'] == 'delete') {
    wpgmappity_delete_map_item($_GET['map_id']);
  }

?>
<div id="media-upload-header">
<ul id="sidemenu">
<li><a href="media-upload.php?post_id=59&amp;type=gmappity&amp;TB_iframe=true&amp;width=640&amp;height=360">Build a Map</a></li>
<li><a class="current" href="media-upload.php?post_id=59&amp;type=gmappity&amp;modify=select&amp;TB_iframe=true&amp;width=640&amp;height=360">Map Library</a></li>
</ul>
</div>

<div id="map_items">

<p>Below are the maps currently stored in your blog's database.</p>



<?php 

  $map_list = wpgmappity_fetch_all_maps();
  $content = '';
  $map_upload_iframe_src = "media-upload.php?post_id=$post_id&amp;type=gmappity&amp;TB_iframe=true";
  foreach ($map_list as $map) {

    $content .= '<div class="map-list-item">';
    $content .= '<a class="wpgmappity_map_delete" href="';
    $content .= $map_upload_iframe_src.'&amp;modify=select';
    $content .= '&amp;map_id='.$map['id'].'&amp;map-action=delete';
    $content .= '" onclick="return confirm(\'Are you sure you want to permenantly delete this map?\');">Delete</a>';
    $content .= '<a class="wpgmappity_map_edit" href="';
    $content .= $map_upload_iframe_src.'&amp;modify=edit';
    $content .= '&amp;map_id='.$map['id'];
    $content .= '">Edit / Insert</a>';
    $content .= '<div class="filename">'.$map['id'].') ';
    if ($map['map_address'] == '') {
      $content .= $map['center_lat'].', '.$map['center_long'];
    }
    else {
      $content .= $map['map_address'];
    }
    $content .= '</div>';
    $content .= '</div>';
  }

  echo $content;
?>

</div>

<?php }

else { ?>

<div id="media-upload-header">
<ul id="sidemenu">
<li><a class="current" href="media-upload.php?post_id=59&amp;type=gmappity&amp;TB_iframe=true&amp;width=640&amp;height=360">Build a Map</a></li>
<li><a href="media-upload.php?post_id=59&amp;type=gmappity&amp;modify=select&amp;TB_iframe=true&amp;width=640&amp;height=360">Map Library</a></li>
</ul>
</div>

<form id="wpgmappity-create" action="media-upload.php?type=wpgmappity&amp;tab=type&amp;post_id=<?php echo $post_id ?>" method="post" autocomplete="off">
<br />
<h3 class="media-title">Add a Google Map to your post</h3>
<div id="media-upload-notice"></div>
<div id="wpgmappity-upload-error"></div>
<div style="width:100%">
	<p style="text-align:center;margin:0;padding:0">Your map preview:</p>
<div id="wpgmappity_sample_map" style="width:450px;height:300px;margin:auto;"></div>
<p id="wpgmappity_promote_text" style="text-align:center;font-size:70%;margin:0;"></p>
</div>

<p>Map Options:</p>

<table class="widefat" id="wpgmappity_sample_table" cellspacing="0">
   <tr class="wpgmappity-iframe-dimension">
   <th valign="top" class="label" scope="row">
   <label for="wpgamppity_construct_size">
     <span class="wpgmappity_selector_size">Size</span>
   </label>
   </th>

    <td class="wpgmappity_sample_choice">

	<div class="wpgmappity_size_choice">
	<input type="radio" value="small" id="wpgmappity_selector_size_small" name="wpgmappity_selector_size" />
	<label for="wpgmappity_selector_size_small">Small</label>
	  <br/>(300x170)
	</div>

	<div class="wpgmappity_size_choice">
        <input type="radio" value="medium" id="wpgmappity_selector_size_medium" name="wpgmappity_selector_size" checked="checked"/>
        <label for="wpgmappity_selector_size_medium">Medium</label>
        <br/>(450x300)
	</div>

	<div class="wpgmappity_size_choice">
	<input type="radio" value="large" id="wpgmappity_selector_size_large" name="wpgmappity_selector_size"/>
	<label for="wpgmappity_selector_size_large">Large</label>
	<br/>(700x400)
	</div>
	
	<div class="wpgmappity_size_choice">
	<input alt="#TB_inline?height=190&width=200&inlineId=wpgmappity_custom_size_dialog" title="Set custom size" class="thickbox" type="radio" name="wpgmappity_selector_size" id="wpgmappity_selector_size_custom" />
	<label for="wpgmappity_selector_size_custom">Custom</label>
	<br/>
	<span id="wpgmappity_custom_size_indicator"></span>
		
	<div id="wpgmappity_custom_size_dialog">
	   <p style="text-align:center;margin:0 0 5px 0;">
		Length:
		<input type="text" name="wpgmappity_custom_size_length" id="wpgmappity_custom_size_length" value="" size="4" maxlength="4"/><br/> 
		<span style="font-size:80%;color:#888;">Min = 150, Max = 1000</span>
	  </p>
  	  <p style="text-align:center;margin:0 0 5px 0;">
		Height: 
		<input type="text" name="wpgmappity_custom_size_height" id="wpgmappity_custom_size_height" value="" size="4" maxlength="4"/><br/> 
		<span style="font-size:80%;color:#888;">Min = 300, Max = 1000</span>
          </p>

  	  <p style="text-align:center;margin:0 0 5px 0;">
  	  <button id="wpgmapity_custom_size_submit" class="button">Set Size</button>	
          </p>
	</div>
	</div>
      </td>
    </tr>


  <tr id="wpgmappity-iframe-center-point">
  <th>
  <label for="wpgamppity_center_point">
    <span class="wpgmappity_selector_size">Center Point</span>
  </label>
  </th>

  <td>
    <div id="wpgmappity_center_point_flash">
    <p>Type an address for your map's center point.</p>
    </div>
    <div id="wpgmappity_center_point_wrapper">
      <input type="text" name="wpgmappity_center_point" id="wpgmappity_center_point" value="" size="45" maxlength="120" style="float:left;"/>
      <button id="wpgmapity_center_point_submit" class="button" style="float:right;">Set Center</button>
      
      <div id="wpgmappity_more_center_results" style="display:none;">
	<p>Multiple possibilities were found for that location.<br/>
	Select an address from the list to set your center there, or click "Not Here" at the bottom to search again.</p>
	<div id="wpgmappity_more_center_results_contents"></div>
	<p style="text-align:center;margin:0 0 5px 0;">
	<button id="wpgmappity_more_center_results_not_here" class="button">Not Here</button>
	</p>
      </div>
    </div>
  </td>
</tr>
	
	
<!-- ZOOM -->
<tr id="wpgmappity-iframe-map-size">
  <th>
    <label for="wpgamppity_zoom_level">
      <span class="wpgmappity_selector_size">Zoom Level</span>
    </label>
  </th>
  <td>
    <div id="wpgmappity_zoom_slider_status_wrap">
       Zoom Setting: <br/>
       <div id="wpgmappity_zoom_slider_status">3</div>
    </div>
    <div id="wpgmappity_zoom_slider_wrapper">
      <p><img src="<?php echo wpgmappity_plugin_url( "styles/leftarrows.gif" )?>" width="10" height="10" alt="left arrow" />
      Adjust the zoom level of the map by dragging the slider.
      <img src="<?php echo wpgmappity_plugin_url( "styles/rightarrows.gif" )?>" width="10" height="10" alt ="right Arrow" />
    </p>
    <div id="wpgmappity_zoom_control_container">
      <div id="wpgmappity_zoom_slider"></div>
    </div>
      </div>
    </td>
  </tr>
  <!-- ADD MARKER -->
  <tr id="wpgmappity-iframe-add-marker">
    <th>
      <label for="wpgamppity_add_marker">
        <span class="wpgmappity_selector_size">
          Markers
        </span>
      </label>
    </th>
    <td>
      <div id="wpgamppity_add_marker_container">
        <h3><a href="#add_a_marker" id="wpgamppity_add_marker_go">Add a Marker</a></h3>
      </div>
      <div id="wpgmappity_add_marker_dialog" style="display:none;">
        <p>Enter a point that you would like marked.  You may specify the point with an address OR with a (latitude, longitude) pair.</p>
        
        <div id="wpgmappity_marker_point_wrapper">
          <div id="wpgmappity_marker_flash"></div>
        
	  <fieldset class="marker-fields">
	    <legend>Marker Point</legend>
	  <p style="margin-bottom:0;margin-top:0;">

	  <input type="radio" value="none" id="wpgmappity_marker_find_address" name="wpgmappity_marker_find" checked="checked" />
	  <label for="wpgmappity_marker_find_address" id="wpgmappity_marker_find_address_text">
	    Mark by Address
	  </label>
	  <br/>
        
	  <input type="radio" value="none" id="wpgmappity_marker_find_latlng" name="wpgmappity_marker_find"  />
	  <label for="wpgmappity_marker_find_latlng" id="wpgmappity_marker_find_latlng_text">
	    Mark by Latitude, Longitude
	  </label>
	</p>
          
	  <p style="margin-top:0;">
	  Point to Mark: 
	  <input type="text" name="wpgmappity_marker_point" id="wpgmappity_marker_point" value="" size="35" maxlength="120"/>
	  </p>
	  </fieldset>

	  <fieldset class="marker-fields">
	    <legend>Marker Image</legend>
	  <p style="margin-bottom:0;margin-top:0;">

	  <input type="radio" value="none" id="wpgmappity_marker_default_image" name="wpgmappity_marker_image" checked="checked" />
	  <label for="wpgmappity_marker_default_image" id="wpgmappity_marker_default_image_text">
	    Default Image
	  </label>
	  <br/></p>
        <p style="margin-top:0;margin-bottom:0;">
	  <input type="radio" value="none" id="wpgmappity_marker_custom_image" name="wpgmappity_marker_image"  />
	  <label for="wpgmappity_marker_custom_image" id="wpgmappity_marker_custom_image_text">
	    Custom Image
	  </label>
	</p>
          
	  <p id="wpgmappity_marker_custom_image_container" style="margin-top:0;">
	  URL to Custom Image:<br/> 
	  <input type="text" name="wpgmappity_marker_custom_image_url" id="wpgmappity_marker_custom_image_url" value="" size="35" maxlength="120"/>
	  <!--
	    <button id="wpgmappity_marker_image_preview" class="button">Preview</button>
	    -->
	  </p>
	  <p>Need help with custom icon images?  <a href="http://www.wordpresspluginfu.com/wpgmappity/wpgmappity-custom-marker-icons/" target="_blank">Visit the WPGMappity icon help.</a></p>
	  </fieldset>

          
	  <p style="text-align:center;">
	    <button id="wpgmappity_marker_point_submit" class="button">Mark Point</button>
	  </p>
         
          <br style="clear:both"/>
          <div id="wpgmappity_more_marker_results" style="display:none;">
            <p>Multiple possibilities were found for that location.<br/>
            Select an address from the list to mark that location, or click 
            "Not Here" at the bottom to try your search again with different parameters.</p>
            <div id="wpgmappity_more_marker_results_contents"></div>
            <p style="text-align:center;margin:0 0 5px 0;">
              <button id="wpgmappity_more_marker_results_not_here" class="button">Not Here</button>

            </p>
          </div>
        </div>
      </div>
      <div id="wgmappity_marker_configure_dialog" style="display:none;">
        <input type="hidden" name="wgmappity_marker_configure_id" id="wgmappity_marker_configure_id" value=""/>
        <div id="wgmappity_marker_configure_wrap">
        <p>Display the following text when the marker is clicked (contents can be HTML as Google Maps will allow):</p>
	<textarea name="wgmappity_marker_configure_text" id="wgmappity_marker_configure_text" rows="5" cols="45"></textarea>
        <p>
          <button id="wgmappity_marker_configure_submit" class="button">Set Configuration</button>

        </p>
        </div>
      </div>
    </td>
  </tr>

  <!-- ROUTE -->
  <tr class="wpgmappity-iframe-dimension">
    <th valign="top" class="label" scope="row">
      <label for="wpgmappity_float">
        <span class="wpgmappity_selector_size">
          Route
        </span>
      </label>
    </th>
    <td class="wpgmappity_sample_choice">
	<p>Add a route to your map.  Enter at least two addresses below to trace the route. </p>
	  <p style="text-align:center;"">Need help with routes?  <a href="http://www.wordpresspluginfu.com/wpgmappity/wpgmappity-routes/" target="_blank">Visit the WPGMappity routes help.</a></p>
      <div class="wpgmappity_size_choice" style="width:75%;">
	<p style="font-size:80;text-align:center;color:#a60000" id="wpgmappity-route-flash"></p>
	    <ul id="wpgmappity-destinationList">
	      <li>
		<div class="wpgmappity-destinationNumber"><p>1</p></div>
		<input type="text" size="45" class="wpgmappity-destinationSearch" />
		<div class="wpgmappity-destinationRemove wpgmappity-destinationRemoveOff"></div>
		<br style="clear:both;" />
		<div class="wpgmappity-destinationDistances"></div>
	      </li>
	      <li>
		<div class="wpgmappity-destinationNumber"><p>2</p></div>
		<input type="text" size="45" class="wpgmappity-destinationSearch"  />
		<div class="wpgmappity-destinationRemove wpgmappity-destinationRemoveOff"></div>
		<br style="clear:both;" />
		<div class="wpgmappity-destinationDistances"></div>
	      </li>
	    </ul>
	    <p id="wpgmappity-destinationAdd"><a href="#">Add another destination</a></p>
	  </div>
      <div class="wpgmappity_size_choice" style="width:25%;">

	    <p style="margin-top:20px;">
	      <button id="wpgmappity-destination_submit" class="button">Plot Route</button>
	    </p>

	    <p style="margin-top:10px;">
	      <button id="wpgmappity-destination_remove" class="button">Remove Route</button>
	    </p>
	    
      </div>
    </td>
  </tr>

  <!-- controls -->
  <tr class="wpgmappity-iframe-dimension">
    <th valign="top" class="label" scope="row">
      <label for="wpgamppity_controls">
        <span class="wpgmappity_selector_size">
          Map Controls
        </span>
      </label>
    </th>
    <td class="wpgmappity_sample_choice">
        <!-- ZOOM CONTROL -->
      <div class="wpgmappity_choice_row" style="border-bottom:1px solid #DFDFDF;padding-bottom:5px;padding-top:5px;float:none;height: 33px;">
          <div class="wpgmappity_type_choice" style="padding-top:5px;width:30%;">
            <input type="checkbox" value="Yes" id="wpgmappity_controls_zoom_on" name="wpgmappity_controls_zoom_on" />
            <label for="wpgmappity_controls_zoom_on"><strong>Zoom Control</strong></label>
          </div>
          <div class="wpgmappity_type_choice" style="width:30%;">
            <label for="wpgmappity_controls_zoom_size">Size:</label>
            <select name="wpgmappity_controls_zoom_size" id="wpgmappity_controls_zoom_size">
                <option value="SMALL">Small</option>
                <option value="LARGE">Large</option>
            </select>
          </div>
          <div class="wpgmappity_type_choice" style="width:39%;">
            <label for="wpgmappity_controls_zoom_position">Position:</label>
            <select name="wpgmappity_controls_zoom_position" id="wpgmappity_controls_zoom_position">
                <option value="TOP_LEFT">Top Left</option>
                <option value="TOP_RIGHT">Top Right</option>
                <option value="TOP_CENTER">Top Center</option>
                <option value="RIGHT_TOP">Right Top</option>
                <option value="RIGHT_CENTER">Right Center</option>
                <option value="RIGHT_BOTTOM">Right Bottom</option>
                <option value="BOTTOM_RIGHT">Bottom Right</option>
                <option value="BOTTOM_CENTER">Bottom Center</option>
                <option value="BOTTOM_LEFT">Bottom Left</option>
                <option value="LEFT_TOP">Left Top</option>
                <option value="LEFT_CENTER">Left Center</option>
                <option value="LEFT_BOTTOM">Left Bottom</option>
            </select>
          </div>
          <br class="clear:both;"/>
      </div> <!-- end ZOOM CONTROL -->
        <!-- MAP TYPE -->
      <div class="wpgmappity_choice_row" style="border-bottom:1px solid #DFDFDF;padding-bottom:5px;padding-top:5px;height:33px;clear:both;">
          <div class="wpgmappity_type_choice" style="padding-top:5px;width:30%;">
            <input type="checkbox" value="Yes" id="wpgmappity_controls_type_on" name="wpgmappity_controls_type_on" />
            <label for="wpgmappity_controls_type_on"><strong>Map Type Control</strong></label>
          </div>
          <div class="wpgmappity_type_choice" style="width:30%;">
            <label for="wpgmappity_controls_type_style">Style:</label>
            <select name="wpgmappity_controls_type_style" id="wpgmappity_controls_type_style">
                <option value="HORIZONTAL_BAR">Bar</option>
                <option value="DROPDOWN_MENU">Dropdown</option>
            </select>
          </div>
          <div class="wpgmappity_type_choice" style="width:39%;">
            <label for="wpgmappity_controls_type_position">Position:</label>
            <select name="wpgmappity_controls_type_position" id="wpgmappity_controls_type_position">
                <option value="TOP_RIGHT">Top Right</option>
                <option value="TOP_CENTER">Top Center</option>
                <option value="TOP_LEFT">Top Left</option>
                <option value="RIGHT_TOP">Right Top</option>
                <option value="RIGHT_CENTER">Right Center</option>
                <option value="RIGHT_BOTTOM">Right Bottom</option>
                <option value="BOTTOM_RIGHT">Bottom Right</option>
                <option value="BOTTOM_CENTER">Bottom Center</option>
                <option value="BOTTOM_LEFT">Bottom Left</option>
                <option value="LEFT_TOP">Left Top</option>
                <option value="LEFT_CENTER">Left Center</option>
                <option value="LEFT_BOTTOM">Left Bottom</option>
            </select>
          </div>
          <br class="clear:both;"/>
      </div> <!-- end MAP TYPE -->

        <!-- SCALE -->
      <div class="wpgmappity_choice_row" style="border-bottom:1px solid #DFDFDF;padding-bottom:5px;padding-top:5px;float:none;height: 33px;clear:both;">
          <div class="wpgmappity_type_choice" style="padding-top:5px;width:30%;">
            <input type="checkbox" value="Yes" id="wpgmappity_controls_scale_on" name="wpgmappity_controls_scale_on" />
            <label for="wpgmappity_controls_scale_on"><strong>Scale</strong></label>
          </div>
          <div class="wpgmappity_type_choice" style="width:39%;">
            <label for="wpgmappity_controls_scale_position">Position:</label>
            <select name="wpgmappity_controls_scale_position" id="wpgmappity_controls_scale_position">
                <option value="TOP_LEFT">Top Left</option>
                <option value="TOP_RIGHT">Top Right</option>
                <option value="TOP_CENTER">Top Center</option>
                <option value="RIGHT_TOP">Right Top</option>
                <option value="RIGHT_CENTER">Right Center</option>
                <option value="RIGHT_BOTTOM">Right Bottom</option>
                <option value="BOTTOM_RIGHT">Bottom Right</option>
                <option value="BOTTOM_CENTER">Bottom Center</option>
                <option value="BOTTOM_LEFT">Bottom Left</option>
                <option value="LEFT_TOP">Left Top</option>
                <option value="LEFT_CENTER">Left Center</option>
                <option value="LEFT_BOTTOM">Left Bottom</option>
            </select>
          </div>
          <br class="clear:both;"/>
      </div> <!-- end SCALE -->

        <!-- STREET -->
      <div class="wpgmappity_choice_row" style="padding-bottom:5px;padding-top:5px;float:none;height: 33px;clear:both;">
          <div class="wpgmappity_type_choice" style="padding-top:5px;width:30%;">
            <input type="checkbox" value="Yes" id="wpgmappity_controls_street_on" name="wpgmappity_controls_street_on" />
            <label for="wpgmappity_controls_street_on"><strong>Streetview Toggle</strong></label>
          </div>
          <div class="wpgmappity_type_choice" style="width:39%;">
            <label for="wpgmappity_controls_street_position">Position:</label>
            <select name="wpgmappity_controls_street_position" id="wpgmappity_controls_street_position">
                <option value="TOP_LEFT">Top Left</option>
                <option value="TOP_RIGHT">Top Right</option>
                <option value="TOP_CENTER">Top Center</option>
                <option value="RIGHT_TOP">Right Top</option>
                <option value="RIGHT_CENTER">Right Center</option>
                <option value="RIGHT_BOTTOM">Right Bottom</option>
                <option value="BOTTOM_RIGHT">Bottom Right</option>
                <option value="BOTTOM_CENTER">Bottom Center</option>
                <option value="BOTTOM_LEFT">Bottom Left</option>
                <option value="LEFT_TOP">Left Top</option>
                <option value="LEFT_CENTER">Left Center</option>
                <option value="LEFT_BOTTOM">Left Bottom</option>
            </select>
          </div>
          <br class="clear:both;"/>
      </div> <!-- end STREET -->
    </td>
  </tr>
  
  <!-- type -->
  <tr class="wpgmappity-iframe-dimension">
    <th valign="top" class="label" scope="row">
      <label for="wpgamppity_construct_size">
        <span class="wpgmappity_selector_size">
          Map Type
        </span>
      </label>
    </th>
    <td class="wpgmappity_size_choice">
      <div class="wpgmappity_size_choice">
        <input type="radio" value="normal" id="wpgmappity_selector_map_type_normal" name="wpgmappity_selector_map_type" checked="checked" />
        <label for="wpgmappity_selector_map_type_normal">Normal</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="satellite" id="wpgmappity_selector_map_type_satellite" name="wpgmappity_selector_map_type" />
        <label for="wpgmappity_selector_map_type_satellite">Satellite</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="hybrid" id="wpgmappity_selector_map_type_hybrid" name="wpgmappity_selector_map_type" />
        <label for="wpgmappity_selector_map_type_hybrid">Hybrid</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="terrain" id="wpgmappity_selector_map_type_terrain" name="wpgmappity_selector_map_type" />
        <label for="wpgmappity_selector_map_type_terrain">Terrain</label>
      </div>
    </td>
  </tr>
  
  <!-- float -->
  <tr class="wpgmappity-iframe-dimension">
    <th valign="top" class="label" scope="row">
      <label for="wpgmappity_float">
        <span class="wpgmappity_selector_size">
          Alignment
        </span>
      </label>
    </th>
    <td class="wpgmappity_sample_choice">
      <div class="wpgmappity_size_choice">
        <input type="radio" value="none" id="wpgmappity_float_none" name="wpgmappity_float" checked="checked" />
        <label for="wpgmappity_float_none">None</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="left" id="wpgmappity_float_left" name="wpgmappity_float" />
        <label for="wpgmappity_float_left">Left</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="center" id="wpgmappity_float_center" name="wpgmappity_float" />
        <label for="wpgmappity_float_center">Center</label>
      </div>  
      <div class="wpgmappity_size_choice">
        <input type="radio" value="right" id="wpgmappity_float_right" name="wpgmappity_float" />
        <label for="wpgmappity_float_right">Right</label>
      </div>
    </td>
  </tr>
  <!-- promote -->
  <tr class="wpgmappity-iframe-dimension">
    <th valign="top" class="label" scope="row">
      <label for="wpgmappity_float">
        <span class="wpgmappity_selector_size">
          Support
        </span>
      </label>
    </th>
    <td class="wpgmappity_sample_choice">
      <div class="wpgmappity_size_choice" style="width:100%;">
	<input type="checkbox" value="Yes" id="wpgmappity_promote" name="wpgmappity_promote" />
        <label for="wpgmappity_promote">Promote WPGmappity</label><br />
	<p>Checking this box will place a small link to the WPGMappity project page under your map.  The link will open in a new window. (preview above) </p>
      </div>
    </td>
  </tr>
 
</table>

<?php if ($_GET['modify'] == 'edit') { ?>
<p style="text-align:center;"><strong> You are editing an existing map.</strong></p>
<p>Use the controls above to customize your map.  When complete click "Edit / Insert Map".  A new shortcode of the form [wpgmappity id="1"] will be inserted in your post.  When your post is displayed this shortcode will be converted into the Google Map that you built.</p>
<input type="hidden" name="wpgmappity-edit-map" value="true"/>
<input type="hidden" name="wpgmappity-map-id" value="<?php echo $map_id ?>"/>
<div class="wpgmappity_submit">
<input type="submit" id="submit" name="submit" value="Edit / Insert Map"/>
</div>
<?php }
else { ?>

<p>Use the controls above to customize your map.  When complete click "Insert Map".  A shortcode of the form [wpgmappity id="1"] will be inserted in your post.  When your post is displayed this shortcode will be converted into the Google Map that you built.</p>
<div class="wpgmappity_submit">
<input type="submit" id="submit" name="submit" value="Insert Map"/>
</div>


<?php } ?>

<p>Need help? <a href="http://www.wordpresspluginfu.com/wpgmappity/using-wpgmappity/" target="_blank">WP GMappity usage help</a></p>
<p>If you find WP GMappity useful, please take a second to <a href="http://wordpress.org/extend/plugins/wp-gmappity-easy-google-maps/" target="_blank">rate it on the Wordpress.org site</a>.</p>
<textarea id="wpgmappity-submit-info" name="wpgmappity-submit-info" style="display:none;"></textarea>

</form>

<?php
if ($_GET['modify'] == 'edit') {
  $map_to_edit = wgmappity_get_meta_data($map_id);
  $map_to_edit = $map_to_edit[0];
?>
<script type="text/javascript">
function wpgmappity_import_saved_map() {
  return {
    'map_length' : <?php echo $map_to_edit['map_length']; ?>,
    'map_height' : <?php echo $map_to_edit['map_height']; ?>,
    'map_zoom' : <?php echo $map_to_edit['map_zoom']; ?>,
    'center_lat' : '<?php echo $map_to_edit['center_lat']; ?>',
    'center_long' : '<?php echo $map_to_edit['center_long']; ?>',
    'map_type' : '<?php echo $map_to_edit['map_type']; ?>',
    'alignment' : '<?php echo $map_to_edit['alignment']; ?>',
    'version' : '<?php echo $map_to_edit['version']; ?>',
    'promote' : '<?php echo $map_to_edit['promote']; ?>',
    'controls' : <?php echo json_encode(unserialize(base64_decode($map_to_edit['map_controls']))); ?>,
    <?php if (isset($map_to_edit['route'])) { ?>
    'route' : <?php echo json_encode(unserialize(base64_decode($map_to_edit['route']))); ?>,
    <?php } ?>
    'map_address' : '<?php echo $map_to_edit['map_address']; ?>'
  }
}
</script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-import.js?version=0.5.3' ) ?>"></script>
<?php }
$markers = wpgmappity_fetch_markers_for_map($map_id);
if ( ($_GET['modify'] == 'edit') && (isset($markers)) ) {

?>
<script type="text/javascript">

function wpgmappity_marker_flag() {
  return true;
}

function wpgmappity_import_markers() {
  return [
  <?php echo wpgmappity_marker_json($markers); ?>
  ]
}
</script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-import-markers.js?version=0.5.3' ) ?>"></script>
  <?php }
else {
 ?>
<script type="text/javascript">
function wpgmappity_marker_flag() {
  return false;
}
</script>
<?php } ?>

<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-controls-zoom.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-controls-type.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-controls-scale.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-controls-street.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-markers.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-promote.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe-routes.js?version=0.5.5' ) ?>"></script>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe.js?version=0.5.5' ) ?>"></script>

<?php } ?>

<?php
do_action('admin_print_footer_scripts');
?>

</body>
</html>
<?php


}

function gmappity_shortcode_to_editor($html) {
  
  return media_send_to_editor($html);
}

function wpgmappity_fetch_all_maps() {
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_maps";
  return $wpdb->get_results("SELECT * FROM $table WHERE active = '1'", ARRAY_A);
}

function wpgmappity_fetch_markers_for_map($map_id) {
  global $wpdb;
  $table = $wpdb->prefix . "wpgmappity_markers";
  return $wpdb->get_results("SELECT * FROM $table WHERE map_id = $map_id", ARRAY_A);
}

function wpgmappity_marker_json($markers) { 
  $content = '';
  foreach ($markers as $key => $marker) {
    $content .= wpgmappity_marker_json_object($marker);
    if (isset($markers[$key + 1])) {
      $content .= ",\n";
    }
    else {
      $content .= "\n";
    }
  }
  return $content;
}

function wpgmappity_marker_json_object($marker) {
  /*
  $marker_text = explode("\n", $marker['marker_text']);
  $text = '[';
  foreach ($marker_text as $bit) {
    $text .= "'".$bit."',";
  }
  $text = substr($text,0,-1);
  $text .= '].join("\n")';
  $content = "{
  'marker_lat' : '".$marker['marker_lat']."',
  'marker_long' : '".$marker['marker_long']."',
  'marker_text' : ".$text.",
  'marker_url' : '".$marker['marker_url']."',
  'marker_image' : '".$marker['marker_image']."'
  }";
  return $content;
  */
  unset($marker['id']);
  unset($marker['map_id']);
  unset($marker['active']);
  return json_encode($marker);
}

?>
