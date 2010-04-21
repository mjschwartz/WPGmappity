<?php 
function wpgmappity_iframe() {
  $post_id = esc_attr($_REQUEST['post_id']);
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
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'media-upload-popup', adminpage = 'media-upload-popup';
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
<link media="all" type="text/css" rel="stylesheet" href="<?php echo $wgmappity_style_sheet ?>">
</head>
<body<?php if ( isset($GLOBALS['body_id']) ) echo ' id="' . $GLOBALS['body_id'] . '"'; ?>>

<form id="wpgmappity-create" action="media-upload.php?type=wpgmappity&amp;tab=type&amp;post_id=<?php echo $post_id ?>" method="post" autocomplete="off">
<br>
<h3 class="media-title">Add a Google Map to your post</h3>
<div id="media-upload-notice"></div>
<div id="wpgmappity-upload-error"></div>
<div style="width:100%">
	<p style="text-align:center;margin:0;padding:0">Your map preview:</p>
<div id="wpgmappity_sample_map" style="width:450px;height:300px;margin:auto;"></div>
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
	<input type="radio" value="small" id="wpgmappity_selector_size_small" name="wpgmappity_selector_size">
	<label for="wpgmappity_selector_size_small">Small</label>
	  <br/>(300x170)
	</div>

	<div class="wpgmappity_size_choice">
        <input type="radio" value="medium" id="wpgmappity_selector_size_medium" name="wpgmappity_selector_size" checked="checked">
        <label for="wpgmappity_selector_size_medium">Medium</label>
        <br/>(450x300)
	</div>

	<div class="wpgmappity_size_choice">
	<input type="radio" value="large" id="wpgmappity_selector_size_large" name="wpgmappity_selector_size">
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
      <p><img src="<?php echo wpgmappity_plugin_url( "styles/leftarrows.gif" )?>" width="10" height="10" />
      Adjust the zoom level of the map by dragging the slider.
      <img src="<?php echo wpgmappity_plugin_url( "styles/rightarrows.gif" )?>" width="10" height="10" />
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
          Add Markers
        </span>
      </label>
    </th>
    <td>
      <div id="wpgamppity_add_marker_container">
        <h3><a href="#add_a_marker" id="wpgamppity_add_marker_go">Add a Marker</a></h3>
      </div>
      <div id="wpgmappity_add_marker_dialog" style="display:none;">
        <p>Enter the address below of the point that you would like marked.</p>
        
        <div id="wpgmappity_marker_point_wrapper">
          <div id="wpgmappity_marker_flash"></div>
          <input type="text" name="wpgmappity_marker_point" id="wpgmappity_marker_point" value="" size="45" maxlength="120" style="float:left;"/>
          <button id="wpgmappity_marker_point_submit" class="button" style="float:right;">Mark Point</button>
         
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
        <p>Display the following text when the marker is clicked:<br/>
          <input type="text" name="wgmappity_marker_configure_text" id="wgmappity_marker_configure_text" value="" size="45" maxlength="120"/></p>
        <p><input type="checkbox" value="Not Here" id="wgmappity_marker_configure_link" name="wgmappity_marker_configure_link"/> Check the box to link this text to the following URL:<br/>
          <input type="text" name="wgmappity_marker_configure_url" id="wgmappity_marker_configure_url" value="" size="45" maxlength="120" /></p>
        <p>
          <button id="wgmappity_marker_configure_submit" class="button">Set Configuration</button>

        </p>
        </div>
      </div>
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
    <td class="wpgmappity_sample_choice">
      <div class="wpgmappity_type_choice">
        <input type="radio" value="normal" id="wpgmappity_selector_map_type_normal" name="wpgmappity_selector_map_type" checked="checked" />
        <label for="wpgmappity_selector_map_type_normal">Normal</label>
      </div>
      <div class="wpgmappity_type_choice">
        <input type="radio" value="satellite" id="wpgmappity_selector_map_type_satellite" name="wpgmappity_selector_map_type" />
        <label for="wpgmappity_selector_map_type_satellite">Satellite</label>
      </div>
      <div class="wpgmappity_type_choice">
        <input type="radio" value="hybrid" id="wpgmappity_selector_map_type_hybrid" name="wpgmappity_selector_map_type" />
        <label for="wpgmappity_selector_map_type_hybrid">Hybrid</label>
      </div>  
    </td>
  </tr>
  
  <!-- float -->
  <tr class="wpgmappity-iframe-dimension">
    <th valign="top" class="label" scope="row">
      <label for="wpgamppity_float">
        <span class="wpgmappity_selector_size">
          Alignment
        </span>
      </label>
    </th>
    <td class="wpgmappity_sample_choice">
      <div class="wpgmappity_size_choice">
        <input type="radio" value="none" id="wpgamppity_float_none" name="wpgamppity_float" checked="checked" />
        <label for="wpgamppity_float_none">None</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="left" id="wpgamppity_float_left" name="wpgamppity_float" />
        <label for="wpgamppity_float_left">Left</label>
      </div>
      <div class="wpgmappity_size_choice">
        <input type="radio" value="center" id="wpgamppity_float_center" name="wpgamppity_float" />
        <label for="wpgamppity_float_center">Center</label>
      </div>  
      <div class="wpgmappity_size_choice">
        <input type="radio" value="right" id="wpgamppity_float_right" name="wpgamppity_float" />
        <label for="wpgamppity_float_right">Right</label>
      </div>
    </td>
  </tr>
  
</table>
<p>Use the controls above to customize your map.  When complete click "Insert Map".  A shortcode of the form [wpgmappity id="1"] will be inserted in your post.  When your post is displayed this shortcode will be converted into the Google Map that you built.</p>
<div class="wpgmappity_submit">
<input type="submit" id="submit" name="submit" value="Insert Map"/>
</div>
<input type="hidden" name="wpgmappity-map-id" value="<?php echo $map_number ?>"/>
<textarea id="wpgmappity-submit-info" name="wpgmappity-submit-info" style="display:none;"></textarea>

</form>
<script type="text/javascript" src="<?php echo wpgmappity_plugin_url( 'js/wpgmappity-iframe.js' ) ?>"></script>

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
?>
