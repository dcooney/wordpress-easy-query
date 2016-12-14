<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
*  eq_is_admin_screen
*  Determine whether user is on an Easy Query admin screen
*
*  @return boolean
*  @since 1.0
*/
	
function eq_is_admin_screen(){
	$return = false;
	$screen = get_current_screen();
	if($screen->id === 'settings_page_easy-query'){
		$return = true;
	}
	return $return;
}