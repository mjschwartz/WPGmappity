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
  //die(var_dump($map_meta_data));
  $map_number =  wpgmappity_insert_meta_data($map_meta_data);
  $html = '<p>[wpgmappity id="'.$map_number.'"]</p>';
  return media_send_to_editor($html);
}

// Config page
function wpgmappity_conf() {
	$settings = get_option('wpgmappity_options');
?>
<?php if ( !empty($_POST['submit'] ) ) {
	if ( function_exists('current_user_can') && !current_user_can('manage_options') )
		die(__('NO. Bad Dog. Sit.'));
	if (isset($_POST['gmaps_api'])) {
		check_admin_referer('wpgmappity-update_' . $settings);
		$settings['gmaps_api'] = $_POST['gmaps_api'];
		update_option('wpgmappity_options', $settings);
	}
?>
<div id="message" class="updated fade"><p><strong>Options saved</strong></p></div>
<?php } ?>
<div class="wrap">
<h2>WP G-Mappity Configuration</h2>
<div class="narrow">
	<form action="" method="post" id="akismet-conf" style="margin: auto; width: 500px; ">
	<?php
	if ( function_exists('wp_nonce_field') )
		wp_nonce_field('wpgmappity-update_' . $settings);
	?>
	<p>In order to use the WP G-Mappity plug-in you must have an API Key from Google Maps.  The key is free and easy to obtain.</p>
	<p>To obtain a key now you can visit the <a href="http://code.google.com/apis/maps/signup.html">Google Maps API Sign up page</a> and fill out a short form.  The page also contains lots of information explaining what the API key is.</p>
	<p>After filling out the form at the link above you will be taken to a web page that displays your shiny new API key.  Copy the key and paste it in the textbox below.</p>
	<label for="gmaps_api">Google Maps API Key:</label>
	<input id="gmaps_api" name="gmaps_api" type="text" size="60" maxlength="100" value="<?php echo $settings['gmaps_api']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.2em;" />
	<p class="submit"><input type="submit" name="submit" value="Update API Key" /></p>
	</form>
</div> <!-- narrow -->
</div> <!-- wrap -->
<?php	
}


?>