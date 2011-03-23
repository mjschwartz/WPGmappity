<?php 


// Add a new button that will call the iframe to build our map
function add_wpgmappity_button() {
  global $post_ID, $temp_ID;
  $uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
  $map_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID&amp;type=gmappity";
  $content = '<a href="'.$map_upload_iframe_src.'&amp;TB_iframe=true" id="add_map" class="thickbox" title="Build a Map" onclick="return false;">';
  $content .= '<img src="'.wpgmappity_plugin_url().'/styles/admin_icon.png" alt="Build a Map" /></a>';

  echo $content;
}
// Call the new action. Give it a realtivly low priority so it is displayed after the default items.
add_action( 'media_buttons', 'add_wpgmappity_button', 15 );

//add the iframe function
add_action('media_upload_gmappity', 'wpgmappity_iframe');

//add wpgmappity action for the media upload
add_action("media_upload_wpgmappity", "wpgmappity_upload");

function wpgmappity_upload() {
  $map_meta_data = $_REQUEST['wpgmappity-submit-info'];
  //die(var_dump($_REQUEST));
  if ($_REQUEST['wpgmappity-edit-map'] == 'true') {
    $map_id = esc_attr($_REQUEST['wpgmappity-map-id']);
    $map_number = wpgmappity_update_meta_data($map_meta_data, $map_id);
  }
  else {
    $map_number =  wpgmappity_insert_meta_data($map_meta_data);
  }
  $html = '<p>[wpgmappity id="'.$map_number.'"]</p>';
  return media_send_to_editor($html);
}

// Config page
function wpgmappity_conf() {
	$settings = get_option('wpgmappity_options');
?>
<?php if ( !empty($_POST['submit-api'] ) ) {
	if ( function_exists('current_user_can') && !current_user_can('manage_options') )
		die(__('NO. Bad Dog. Sit.'));
?>
<div id="message" class="updated fade"><p><strong>Options saved</strong></p></div>
<?php }
	
	if ( !empty($_POST['submit-tables'] ) ) {
	if ( function_exists('current_user_can') && !current_user_can('manage_options') )
		die(__('NO. Bad Dog. Sit.'));
	if (isset($_POST['wpgmappity-save-tables'])) {
		check_admin_referer('wpgmappity-save_tables_' . $settings);
		$settings['save_tables'] = $_POST['wpgmappity-save-tables'];
		update_option('wpgmappity_options', $settings);
	}
?>
<div id="message" class="updated fade"><p><strong>Options saved</strong></p></div>
<?php } ?>
<div class="wrap">
<h2>WP G-Mappity Configuration</h2>
<div class="narrow">
	<form action="" method="post" style="margin: auto; width: 500px;">
	<?php
	if ( function_exists('wp_nonce_field') )
		wp_nonce_field('wpgmappity-save_tables_' . $settings);
	?>
	
	<p>WPGMappity stores information about its maps in your blog's database.  Depending on the setting below, if the plugin is uninstalled that information can either be deleted permenantly, or retained in case you reinstall this plugin.</p>
	<p>If you are sure that you will never use the plugin again, or if you are sure you no longer need the maps you have created its probably best to select "no" to clean up the clutter.  If you want to retain the information leave "yes" selected.</p>
	<p><strong>Save map information in your database if WPGMMappity is uninstalled:</strong><br />
	 <input id="wpgmappity-save-tables-yes" name="wpgmappity-save-tables" type="radio" value="1" 
	<?php if ($settings['save_tables'] == '1') {
		echo ' checked="checked"';
		}
		?>/> <label for="wpgmappity-save-tables-yes">Yes, save my information just in case.</label><br />
	 <input id="wpgmappity-save-tables-no" name="wpgmappity-save-tables" type="radio" value="0" 
	<?php if ($settings['save_tables'] == '0') {
		echo ' checked="checked"';
		}
		?>/> <label for="wpgmappity-save-tables-no">No, delete my maps premanently when the plugin is uninstalled.</label></p>
	
	
	<p class="submit"><input type="submit" name="submit-tables" value="Update Table Settings" /></p>
	</form>
	
	
	<p>Visit the project home for user help or more infomration: <a href="http://www.wordpresspluginfu.com/wpgmappity/">http://www.wordpresspluginfu.com/wpgmappity/</a>.</p>
<p>Is this plugin useful to you? Please take a second to <a href="http://wordpress.org/extend/plugins/wp-gmappity-easy-google-maps/">rate it on wordpress.org.</a></p>
</div> <!-- narrow -->
</div> <!-- wrap -->
<?php	
}


?>