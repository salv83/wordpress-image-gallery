<?php
/* With this action we will add a new option page to our wordpress backend */
add_action('admin_menu', 'wpg_settings_menu');

/* This is the handler we pass to the action admin_menu where we define the parameters of our option page*/
function wpg_settings_menu() {
    add_menu_page('Wordpress Photo Gallery Settings', 'Wordpress Photo Gallery Settings', 'administrator', 'wpgsettingpage', 'wpg_settings_page' , 'dashicons-feedback');
    add_action( 'admin_init', 'register_wpg_settings' );
}

/* 
 * Using the admin_init action we declare that there will be a new group of options called wpg-settings-group
 * which will have two options: pictures-number which will save the number of the picture that the user wants
 * to display in the gallery and hide-image-title is a checkbox that if checked display the titles in the 
 * gallery
 */
function register_wpg_settings() {
    register_setting( 'wpg-settings-group', 'pictures-number' );
    register_setting( 'wpg-settings-group', 'hide-image-title' );
}

/*
 * The function wpg_settings_page() will display the option page in the wordpress backend there will be 
 * an input field of type number, one checkbox and a submit button that if clicked will save  the value
 * of our option inside the database
 */
function wpg_settings_page() {
    ?>
<div class="wrap">
<h1>Worpress Photo Gallery Settings</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'wpg-settings-group' ); ?>
    <?php do_settings_sections( 'wpg-settings-group' );
    
    $pictures_number = get_option('pictures-number') ;
    $hide_image_title = get_option('hide-image-title') ;
    
    ?>
    <table class="form-table">
    		<tr valign="top">
				<th scope="row">Number of picture to display in the gallery</th>
				<td>
				  <input type="number" name="pictures-number" value="<?php print $pictures_number; ?>" size="70" min="1" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show title for all images in the gallery</th>
				<td>
				 <?php printf ( '<input value="selected" type="checkbox" id="hide-image-title" name="hide-image-title" %s />', ((isset ( $hide_image_title) && (!empty($hide_image_title)) )) ? "checked" : '' ); ?>
				  
				</td>
			</tr>
	</table>    
	
    <?php submit_button(); ?>

</form>
</div>
<?php } 

/*
 * the funcion do_we_want_to_show_title() return the value of the checkbox corresponding to the option 
 * "Show title for all images in the gallery" if the checkbox is checked it will return true, false otherwise.
 * As default it will return true in the case the user has not configured the plugin and the title will be
 * displayed inside the gallery
 */

function do_we_want_to_show_title() {
    return get_option('hide-image-title', true);
}