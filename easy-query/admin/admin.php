<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* WP Actions */
add_action( 'admin_head', 'ewpq_admin_vars' ); // Set admin JS variables
add_action( 'admin_menu', 'eq_admin_menu' ); // Create admin menu
add_action( 'wp_ajax_ewpq_save_repeater', 'ewpq_save_repeater' ); // Ajax Save template
add_action( 'wp_ajax_ewpq_update_repeater', 'ewpq_update_repeater' ); // Ajax Update template
add_filter( 'admin_footer_text', 'eq_filter_admin_footer_text'); // Admin menu text




/*
*  ewpq_admin_vars
*  Create admin variables and ajax nonce
*
*  @since 1.0.0
*/
function ewpq_admin_vars() { ?>
    <script type='text/javascript'>
	 /* <![CDATA[ */
    var ewpq_admin_localize = <?php echo json_encode( array( 
        'ajax_admin_url' => admin_url( 'admin-ajax.php' ),
        'ewpq_admin_nonce' => wp_create_nonce( 'ewpq_repeater_nonce' ),
        'active' => __('Active', EQ_VERSION),
        'inactive' => __('Inactive', EQ_VERSION),
    )); ?>
    /* ]]> */
    </script>
<?php }



/**
* ewpq_core_update
* If WP option plugin version do not match or the plugin has been updated and we need to update our templates.
*
* @since 1.0.0
*/

function ewpq_core_update() {  
	global $wpdb;
	$installed_ver = get_option( "easy_query_version" ); // Get value from WP Option tbl
	if ( $installed_ver != EQ_VERSION ) {
      ewpq_run_update();	
   }
}
add_action('plugins_loaded', 'ewpq_core_update');


/**
* ewpq_run_update
* Run the update on our blogs
*
* @since 1.0.0
*/

function ewpq_run_update(){
   global $wpdb;	
   
   if ( is_multisite()) {           
   	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );   	
      
   	// Loop all blogs and run update routine   	
      foreach ( $blog_ids as $blog_id ) {
         switch_to_blog( $blog_id );
         ewpq_update_template_files();
         restore_current_blog();
      }
      
   } else {
      ewpq_update_template_files();
   }
      
   update_option( "easy_query_version", EQ_VERSION ); // Update the WP Option tbl
}


/**
* ewpq_update_template_files
* Update routine for template files
*
* @since 1.0.0
*/

function ewpq_update_template_files(){
   global $wpdb;	
	$table_name = $wpdb->prefix . "easy_query";
	$blog_id = $wpdb->blogid;	
	 
	if($blog_id > 1){	// Create template_ directories if they don't exist 
	   $dir = EQ_PATH. 'core/templates_'. $blog_id;
   	if( !is_dir($dir) ){
         mkdir($dir);
      }
   }
   
	// Get all templates ($rows)
   $rows = $wpdb->get_results("SELECT * FROM $table_name WHERE type = 'default' OR type = 'unlimited'"); 
      
   if($rows){
      foreach( $rows as $row ) { // Loop $rows
         
         $template = $row->name; // Current template
         
         $data = $wpdb->get_var("SELECT template FROM $table_name WHERE name = '$template'");
         
         if($blog_id > 1)
            $f = EQ_PATH. 'core/templates_'. $blog_id.'/'.$template.'.php';
         else
            $f = EQ_TEMPLATE_PATH. ''.$template.'.php';
         
         $o = fopen($f, 'w+'); // Open file or create it
         $w = fwrite($o, $data);
         fclose($o);
      }
   }
}



/*
 * eq_admin_menu
 * Create Admin Menu
 *
 * @since 2.0.0
 */

function eq_admin_menu() {  
   
   $eq_settings_page = add_submenu_page(
      'options-general.php', 
      'Easy Query', 
      'Easy Query', 
      'edit_theme_options', 
      'easy-query', 
      'eq_settings_page'
   ); 	
   add_action( 'load-' . $eq_settings_page, 'ewpq_load_admin_js' );
      
}



/*
*  eq_settings_page
*  Settings page
*
*  @since 2.0.0
*/

function eq_settings_page(){ 
   if( isset($_GET['tab']) && $_GET['tab'] == 'settings'){
      
      $name = __('Settings', 'easy-query' );
      $tab = 'settings';  
   
   }else if( isset($_GET['tab']) && $_GET['tab'] == 'template'){
   
      $name = __('Template', 'easy-query' );
      $tab = 'template';  
   
   }else if( isset($_GET['tab']) && $_GET['tab'] == 'query-builder'){
   
      $name = __('Query Builder', 'easy-query' );
      $tab = 'query-builder';  
   
   }elseif( isset($_GET['tab']) && $_GET['tab'] == 'examples'){
   
      $name = __('Examples', 'easy-query' );
      $tab = 'examples';  
   
   }elseif( isset($_GET['tab']) && $_GET['tab'] == 'pro'){
   
      $name = __('Pro', 'easy-query' );
      $tab = 'pro';  
   
   }else{
   
      $name = __('Settings', 'easy-query' );
      $tab = 'settings';  
   
   }
?>
   <ul class="eq-nav">
      <li class="eq-dashboard">
         <a class="tab<?php if( !isset($_GET['tab'])) echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>">
            <span><?php _e('Dashboard', 'easy-query' ); ?></span>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'template') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=template">
   		   <?php _e('Template', 'easy-query' ); ?>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'query-builder') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=query-builder">
   		   <?php _e('Query Builder', 'easy-query' ); ?>
   		</a>
      </li>
      <li>
		   <a class="tab<?php if( isset($_GET['tab']) && $tab == 'examples') echo ' nav-tab-active'; ?>" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=examples">
   		   <?php _e('Examples', 'easy-query' ); ?>
   		</a>
      </li>
		<li>
         <a class="tab<?php if( isset($_GET['tab']) && $tab == 'pro') echo ' nav-tab-active'; ?>" id="nav-pro" href="options-general.php?page=<?php echo EQ_SLUG; ?>&tab=pro">
            <?php _e('Pro', 'easy-query' ); ?>
         </a>
		</li>
   </ul>
	<div class="content" id="poststuff">
	<?php 
		if( !isset($_GET['tab'])){
		   include_once( EQ_PATH . 'admin/views/settings.php');
      }
      if( isset($_GET['tab']) && $tab == 'shortcode'){
         include_once( EQ_PATH . 'admin/views/shortcode.php');
      }
      if( isset($_GET['tab']) && $tab == 'template'){
         include_once( EQ_PATH . 'admin/views/template.php');
      }
      if( isset($_GET['tab']) && $tab == 'query-builder'){
         include_once( EQ_PATH . 'admin/views/query-builder.php');
      } 
      if( isset($_GET['tab']) && $tab == 'examples'){
         include_once( EQ_PATH . 'admin/views/examples.php');
      } 
      if( isset($_GET['tab']) && $tab == 'pro'){
         include_once( EQ_PATH . 'admin/views/pro.php');
      } 
   ?>
   <div class="clear"></div>
<?php }



/**
* ewpq_load_admin_js
* Load Admin JS
*
* @since 1.0.0
*/

function ewpq_load_admin_js(){
	add_action( 'admin_enqueue_scripts', 'ewpq_enqueue_admin_scripts' );
}



/**
* ewpq_enqueue_admin_scripts
* Enqueue Admin JS
*
* @since 1.0.0
*/

function ewpq_enqueue_admin_scripts(){

   //Load Admin CSS
   wp_enqueue_style( 'ewpq-admin', EQ_ADMIN_URL. 'css/admin.css');
  
   //CodeMirror
   
      // CSS
      wp_enqueue_style( 'ewpq-codemirror-css', EQ_ADMIN_URL. 'codemirror/lib/codemirror.css' );
            
      // JS
      wp_enqueue_script( 'ewpq-codemirror', EQ_ADMIN_URL. 'codemirror/lib/codemirror.js' );    
      wp_enqueue_script( 'ewpq-codemirror-matchbrackets', EQ_ADMIN_URL. 'codemirror/addon/edit/matchbrackets.js' );
      wp_enqueue_script( 'ewpq-codemirror-htmlmixed', EQ_ADMIN_URL. 'codemirror/mode/htmlmixed/htmlmixed.js' );
      wp_enqueue_script( 'ewpq-codemirror-xml', EQ_ADMIN_URL. 'codemirror/mode/xml/xml.js' );
      wp_enqueue_script( 'ewpq-codemirror-javascript', EQ_ADMIN_URL. 'codemirror/mode/javascript/javascript.js' );
      wp_enqueue_script( 'ewpq-codemirror-mode-css', EQ_ADMIN_URL. 'codemirror/mode/css/css.js' );
      wp_enqueue_script( 'ewpq-codemirror-clike', EQ_ADMIN_URL. 'codemirror/mode/clike/clike.js' );
      wp_enqueue_script( 'ewpq-codemirror-php', EQ_ADMIN_URL. 'codemirror/mode/php/php.js' );        
   
   //Load JS   
   wp_enqueue_script( 'jquery-form' );
   wp_enqueue_script( 'ewpq-select2', EQ_ADMIN_URL. 'js/libs/select2.min.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-drops', EQ_ADMIN_URL. 'js/libs/jquery.drops.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-admin', EQ_ADMIN_URL. 'js/admin.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-shortcode-builder', EQ_ADMIN_URL. 'shortcode-builder/js/shortcode-builder.js', array( 'jquery' ));
}



/*
*  eq_filter_admin_footer_text
*  Filter the WP Admin footer text only on Easy Query pages
*
*  @since 2.0
*/

function eq_filter_admin_footer_text( $text ) {	
	$screen = eq_is_admin_screen();	
	if(!$screen){
		return;
	}
	
	echo '<strong>Easy Query</strong> is made with <span style="color: #e25555;">♥</span> by <a href="https://connekthq.com" target="_blank" style="font-weight: 500;">Connekt</a>';
}



/*
*  ewpq_pro_page
*  Easy Query Pro
*
*  @since 2.0.0
*/

function ewpq_pro_page(){ 
   include_once( EQ_PATH . 'admin/views/pro.php');
}



/*
*  ewpq_save_repeater
*  Template Save function
*
*  @return   response
*  @since 1.0.0
*/

function ewpq_save_repeater(){
   
   if (current_user_can( 'edit_theme_options' )){
   
      global $wpdb;
   	$blog_id = $wpdb->blogid;
      
   	$nonce = $_POST["nonce"];
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      // Get _POST Vars 
   	$c = Trim(stripslashes($_POST["value"])); // Template Value
   	$n = Trim(stripslashes($_POST["template"])); // Template name
   	$t = Trim(stripslashes($_POST["type"])); // Template type
   	$a = Trim(stripslashes($_POST["alias"])); // Template alias
      
      if($blog_id > 1) // multisite
         $f = EQ_PATH. 'core/templates_'. $blog_id.'/'.$n .'.php'; // File
   	else
   	   $f = EQ_TEMPLATE_PATH. ''.$n .'.php'; // File	   
   		
      $o_error = '<span class="saved-error"><b>'. __('Error Opening File', 'easy-query') .'</b></span>';
      $o_error .= '<em>'. $f .'</em>';
      $o_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the /easy-query/ plugin directory', 'easy-query');
      
      $w_error = '<span class="saved-error"><b>'. __('Error Saving File', 'easy-query') .'</b></span>';
      $w_error .= '<em>'. $f .'</em>';
      $w_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the /easy-query/ plugin directory', 'easy-query');
      
      $o = fopen($f, 'w+') or die($o_error); // Open file
   	
   	$w = fwrite($o, $c) or die($w_error); // Save/Write the file
   	
   	fclose($o); //now close it
   	
   	$table_name = $wpdb->prefix . "easy_query";	
   	
      if($t === 'unlimited'){ // Unlimited Templates	  
   	   $data_update = array('template' => "$c", 'alias' => "$a", 'pluginVersion' => EQ_VERSION);
   	   $data_where = array('name' => $n);
      }
      else{ // Custom Repeaters
   	   $data_update = array('template' => "$c", 'pluginVersion' => EQ_VERSION);
   	   $data_where = array('name' => "default");
      }
      
   	$wpdb->update($table_name , $data_update, $data_where);
   	
   	//Our results
   	if($w){
   	    echo '<span class="saved">Template Saved Successfully</span>';
   	} else {
   	    echo '<span class="saved-error"><b>'. __('Error Writing File', 'easy-query') .'</b></span><br/>Something went wrong and the data could not be saved.';
   	}
   	die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
*  ewpq_update_repeater
*  Update repeater template from database
*
*  @return   DB value
*  @since 1.0.0
*/

function ewpq_update_repeater(){
   
   if (current_user_can( 'edit_theme_options' )){

   	$nonce = $_POST["nonce"];
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Error - unable to verify nonce, please try again.');
   		
      // Get _POST Vars  	
   	$n = Trim(stripslashes($_POST["template"])); // Repeater name
   	$t = Trim(stripslashes($_POST["type"])); // Repeater type (default | unlimited)
   	
   	// Get value from database
   	global $wpdb;
   	$table_name = $wpdb->prefix . "easy_query";	
   		
   	if($t === 'default')	$n = 'default';      
      
      $the_template = $wpdb->get_var("SELECT template FROM " . $table_name . " WHERE name = '$n'");
      
      echo $the_template; // Return repeater value
      
   	die();
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}	
}



/*
 *  ewpq_get_tax_terms
 *  Get taxonomy terms for shortcode builder
 *
 *  @return   Taxonomy Terms
 *  @since 1.0.0
 */

function ewpq_get_tax_terms(){	
   
   if (current_user_can( 'edit_theme_options' )){
      
   	$nonce = $_GET["nonce"];
   	// Check our nonce, if they don't match then bounce!
   	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
   		die('Get Bounced!');
   		
   	$taxonomy = (isset($_GET['taxonomy'])) ? $_GET['taxonomy'] : '';	
   	$tax_args = array(
   		'orderby'       => 'name', 
   		'order'         => 'ASC',
   		'hide_empty'    => false
   	);	
   	$terms = get_terms($taxonomy, $tax_args);
   	$returnVal = '';
   	if ( !empty( $terms ) && !is_wp_error( $terms ) ){		
   		$returnVal .= '<ul>';
   		foreach ( $terms as $term ) {
   			//print_r($term);
   			$returnVal .='<li><input type="checkbox" class="alm_element" name="tax-term-'.$term->slug.'" id="tax-term-'.$term->slug.'" data-type="'.$term->slug.'"><label for="tax-term-'.$term->slug.'">'.$term->name.'</label></li>';		
   		}
   		$returnVal .= '</ul>';		
   		echo $returnVal;
   		die();
   	}else{
   		echo "<p class='warning'>No terms exist within this taxonomy</p>";
   		die();
   	}
		   	
	}else {
		echo __('You don\'t belong here.', 'easy-query');
	}
	
}



/*
 *  ewpq_admin_init
 *  Initiate the plugin, create our setting variables.
 *
 *  @since 1.0.0
 */

add_action( 'admin_init', 'ewpq_admin_init');
function ewpq_admin_init(){

	register_setting( 
		'ewpq-setting-group', 
		'ewpq_settings', 
		'_ewpq_sanitize_settings' 
	);
	
	add_settings_section( 
		'ewpq_general_settings',  
		'Global Settings', 
		'ewpq_general_settings_callback', 
		'easy-wp-query' 
	);
	
	add_settings_field(  // Disable CSS
		'_ewpq_disable_css', 
		__('Disable CSS', 'easy-query' ), 
		'_ewpq_disable_css_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);
	
	add_settings_field(  // Hide btn
		'_ewpq_hide_btn', 
		__('Editor Button', 'easy-query' ), 
		'ewpq_hide_btn_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);
	
	add_settings_field(  // Load dynamic queries
		'_ewpq_disable_dynamic', 
		__('Dynamic Content', 'easy-query' ), 
		'ewpq_disable_dynamic_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);	
	
}


/*
*  ewpq_general_settings_callback
*  Some general settings text
*
*  @since 1.0.0
*/

function ewpq_general_settings_callback() {
    echo '<p>' . __('Customize your version of Easy Query by updating the various settings below.', 'easy-query') . '</p>';
}


/*
*  _ewpq_sanitize_settings
*  Sanitize our form fields
*
*  @since 1.0.0
*/

function _ewpq_sanitize_settings( $input ) {
    return $input;
}


/*
*  ewpq_hide_btn_callback
*  Disbale the Easy Query shortcode button in the WordPress content editor
*
*  @since 1.0.0
*/

function ewpq_hide_btn_callback(){
	$options = get_option( 'ewpq_settings' );
	if(!isset($options['_ewpq_hide_btn'])) 
	   $options['_ewpq_hide_btn'] = '0';
	
	$html = '<input type="hidden" name="ewpq_settings[_ewpq_hide_btn]" value="0" /><input type="checkbox" id="ewpq_hide_btn" name="ewpq_settings[_ewpq_hide_btn]" value="1"'. (($options['_ewpq_hide_btn']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="ewpq_hide_btn">'.__('Hide Query Builder button in WYSIWYG editor.', 'easy-query').'</label>';	
	
	echo $html;
}


/*
*  _ewpq_disable_css_callback
*  Diabale Easy Query CSS.
*
*  @since 1.0.0
*/

function _ewpq_disable_css_callback(){
	$options = get_option( 'ewpq_settings' );
	if(!isset($options['_ewpq_disable_css'])) 
	   $options['_ewpq_disable_css'] = '0';
	
	$html = '<input type="hidden" name="ewpq_settings[_ewpq_disable_css]" value="0" />';
	$html .= '<input type="checkbox" id="ewpq_disable_css_input" name="ewpq_settings[_ewpq_disable_css]" value="1"'. (($options['_ewpq_disable_css']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="ewpq_disable_css_input">'.__('I want to use my own CSS styles', 'easy-query').'<br/><span style="display:block;"><i class="fa fa-file-text-o"></i> &nbsp;<a href="'.EQ_URL.'/core/css/easy-query.css" target="blank">'.__('View Easy Query CSS', 'easy-query').'</a></span></label>';
	
	echo $html;
}


/*
*  ewpq_disable_dynamic_callback
*  Disable the dynamic population of categories, tags and authors
*
*  @since 1.0.0
*/

function ewpq_disable_dynamic_callback(){
	$options = get_option( 'ewpq_settings' );		
	if(!isset($options['_ewpq_disable_dynamic'])) 
	   $options['_ewpq_disable_dynamic'] = '0';
	
	$html =  '<input type="hidden" name="ewpq_settings[_ewpq_disable_dynamic]" value="0" />';
	$html .= '<input type="checkbox" name="ewpq_settings[_ewpq_disable_dynamic]" id="_ewpq_disable_dynamic" value="1"'. (($options['_ewpq_disable_dynamic']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="_ewpq_disable_dynamic">'.__('Disable dynamic population of categories, tags and authors in the Query Builder.<span style="display:block">Recommended only if you have an extraordinary number of categories, tags and/or authors.', 'easy-query').'</label>';	
	
	echo $html;
}


