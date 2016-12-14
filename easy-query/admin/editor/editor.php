<?php

/* Create shortcode builder button in WYSIWYG editor */

add_action('init','ewpq_editor_init');
// Enqueue jQuery in editor
function ewpq_editor_init() {
	wp_enqueue_script( 'jquery' );
}


//Check for permissions
add_action('wp_ajax_ewpq', 'ewpq_ajax_tinymce' );
function ewpq_ajax_tinymce(){
	// check for rights
	if ( ! current_user_can('edit_pages') && ! current_user_can('edit_posts') )
		die( __("You are not allowed to be here", ALM_NAME) );

	$ewpqwindow = EQ_PATH . 'admin/editor/editor-build.php';
	include_once( $ewpqwindow );

	die();
}


// filters the tinyMCE buttons and adds our custom buttons
add_action('admin_head', 'ewpq_shortcode_buttons');
function ewpq_shortcode_buttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
		
	// Check options for hiding shortcode builder	
   $options = get_option( 'ewpq_settings' );
   
   if(!isset($options['_ewpq_hide_btn'])) // Check if '_alm_hide_btn isset
	   $options['_ewpq_hide_btn'] = '0';
   
	if($options['_ewpq_hide_btn'] != '1'){
   	// Add only in Rich Editor mode
   	if ( get_user_option('rich_editing') == 'true') {
   		// filter the tinyMCE buttons and add our own
   		add_filter("mce_external_plugins", "ewpq_tinymce_plugin");
   		add_filter('mce_buttons', 'ewpq_friendly_buttons');
   	}
	}
}


// registers the buttons for use
function ewpq_friendly_buttons($ewpqbuttons) {
	array_push($ewpqbuttons, 'ewpq_shortcode_button');
	return $ewpqbuttons;
}


// add the button to the tinyMCE bar
function ewpq_tinymce_plugin($plugin_arrays) {	
	$plugin_arrays['ewpq_shortcode_button'] = plugins_url( '/js/editor-btn.js' , __FILE__ );
	return $plugin_arrays;
}
