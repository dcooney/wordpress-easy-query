<?php

/* Admin functions */

add_action( 'admin_head', 'ewpq_admin_vars' ); // Set admin JS variables

add_action( 'wp_ajax_ewpq_save_repeater', 'ewpq_save_repeater' ); // Ajax Save template
add_action( 'wp_ajax_nopriv_ewpq_save_repeater', 'ewpq_save_repeater' ); // Ajax Save template

add_action( 'wp_ajax_ewpq_update_repeater', 'ewpq_update_repeater' ); // Ajax Update template
add_action( 'wp_ajax_nopriv_ewpq_update_repeater', 'ewpq_update_repeater' ); // Ajax Update template

add_action( 'wp_ajax_ewpq_get_tax_terms', 'ewpq_get_tax_terms' ); // Ajax Get Taxonomy Terms
add_action( 'wp_ajax_nopriv_ewpq_get_tax_terms', 'ewpq_get_tax_terms' ); // Ajax Get Taxonomy Terms

add_action( 'wp_ajax_ewpq_query_generator', 'ewpq_query_generator' ); // Ajax Generate Query
add_action( 'wp_ajax_nopriv_ewpq_query_generator', 'ewpq_query_generator' ); // Ajax Generate Query



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
        'ewpq_admin_nonce' => wp_create_nonce( 'ewpq_repeater_nonce' )
    )); ?>
    /* ]]> */
    </script>
<?php }



/**
* ewpq_core_update
* Update templates on plugin update.
* If plugin versions do not match or the plugin has been updated and we need to update our templates.
*
* @since 1.0.0
*/

add_action('admin_init', 'ewpq_core_update');
function ewpq_core_update() {  
	global $wpdb;
	$table_name = $wpdb->prefix . "easy_query";	     
	 
   // **********************************************
   // If table exists
   // **********************************************
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) { 
   	
      // Compare template versions. 
      // - if template versions do not match, update the templates with value(s) from DB	   
         
	   $version = $wpdb->get_var("SELECT pluginVersion FROM $table_name WHERE type = 'default'");	        
	   if($version != EWPQ_VERSION){ // First, make sure versions do not match.
		   //Write to template file
		   $data = $wpdb->get_var("SELECT template FROM $table_name WHERE type = 'default'");
			$f = EWPQ_TEMPLATE_PATH. 'default.php'; // File
			$o = fopen($f, 'w+'); //Open file
			$w = fwrite($o, $data); //Save the file
			$r = fread($o, 100000); //Read it
			fclose($o); //now close it
	   }	    
   }   
    
    // **********************************************
    // If table DOES NOT exist, create it.	
    // **********************************************
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
	   $template = '<li><?php if ( has_post_thumbnail() ) { the_post_thumbnail(array(100,100));}?><h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3><p class="entry-meta"><?php the_time("F d, Y"); ?></p><?php the_excerpt(); ?></li>';	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			type longtext NOT NULL,
			alias longtext NOT NULL,
			template longtext NOT NULL,
			pluginVersion text NOT NULL,
			UNIQUE KEY id (id)
		);";		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		//Insert default data in newly created table
      $wpdb->insert($table_name, array(
         'name' => 'default', 
         'type' => 'default', 
         'alias' => '', 
         'template' => $template, 
         'pluginVersion' => EWPQ_VERSION,
      ));
   }
}



/*
 * ewpq_admin_menu
 * Create Admin Menu
 *
 * @since 1.0.0
 */

add_action( 'admin_menu', 'ewpq_admin_menu' );
function ewpq_admin_menu() {  
   $icon = 'dashicons-plus-alt';
   $icon = EWPQ_ADMIN_URL . "/img/logo-16x16.png";
   $ewpq_page = add_menu_page( // Settings/Main
      'Easy Query', 
      'Easy Query', 
      'edit_theme_options', 
      'easy-query', 
      'ewpq_settings_page', 
      $icon 
   );
   $ewpq_settings_page = add_submenu_page( // Settings
      'easy-query', 
      'Settings', 
      'Settings', 
      'edit_theme_options', 
      'easy-query', 
      'ewpq_settings_page'
   ); 
   $ewpq_template_page = add_submenu_page( // Templates
      'easy-query', 
      'Template', 
      'Template', 
      'edit_theme_options', 
      'easy-query-templates', 
      'ewpq_repeater_page'
   ); 
   $ewpq_shortcode_page = add_submenu_page( // Query Builder
      'easy-query', 
      'Custom Query Builder', 
      'Query Builder', 
      'edit_theme_options', 
      'easy-query-custom-query-builder', 
      'ewpq_query_builder_page'
   ); 
   $ewpq_examples_page = add_submenu_page( // Examples
      'easy-query', 
      'Examples', 
      'Examples', 
      'edit_theme_options', 
      'easy-query-examples', 
      'ewpq_example_page'
   ); 
   
   $ewpq_go_pro_page = add_submenu_page( // Go Pro
      'easy-query', 
      'Pro', 
      '<span style="color: #e1e0f5;font-weight: 700;">Pro<span>', 
      'edit_theme_options', 
      'easy-query-go-pro', 
      'ewpq_go_pro_page'
   );  	
   
   //Add our admin scripts
   add_action( 'load-' . $ewpq_settings_page, 'ewpq_load_admin_js' );
   add_action( 'load-' . $ewpq_template_page, 'ewpq_load_admin_js' );
   add_action( 'load-' . $ewpq_shortcode_page, 'ewpq_load_admin_js' );
   add_action( 'load-' . $ewpq_examples_page, 'ewpq_load_admin_js' );
   add_action( 'load-' . $ewpq_go_pro_page, 'ewpq_load_admin_js' );
   
}



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
   wp_enqueue_style( 'ewpq-admin-css', EWPQ_ADMIN_URL. 'css/admin.css');
   wp_enqueue_style( 'ewpq-select2-css', EWPQ_ADMIN_URL. 'css/select2.css');
   wp_enqueue_style( 'ewpq-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
   
   //Load CodeMirror Syntax Highlighting if on Template and Query Builder  and Saved Query pages 
   $screen = get_current_screen();
   if ( in_array( 
   	$screen->id, array(
   	'easy-query_page_easy-query-templates',
      'easy-query_page_easy-query-saved-queries',
      'easy-query_page_easy-query-custom-query-builder',
   	)
   )){ 
      //CodeMirror CSS
      wp_enqueue_style( 'ewpq-codemirror-css', EWPQ_ADMIN_URL. 'codemirror/lib/codemirror.css' );
            
      //CodeMirror JS
      wp_enqueue_script( 'ewpq-codemirror', EWPQ_ADMIN_URL. 'codemirror/lib/codemirror.js' );    
      wp_enqueue_script( 'ewpq-codemirror-matchbrackets', EWPQ_ADMIN_URL. 'codemirror/addon/edit/matchbrackets.js' );
      wp_enqueue_script( 'ewpq-codemirror-htmlmixed', EWPQ_ADMIN_URL. 'codemirror/mode/htmlmixed/htmlmixed.js' );
      wp_enqueue_script( 'ewpq-codemirror-xml', EWPQ_ADMIN_URL. 'codemirror/mode/xml/xml.js' );
      wp_enqueue_script( 'ewpq-codemirror-javascript', EWPQ_ADMIN_URL. 'codemirror/mode/javascript/javascript.js' );
      wp_enqueue_script( 'ewpq-codemirror-mode-css', EWPQ_ADMIN_URL. 'codemirror/mode/css/css.js' );
      wp_enqueue_script( 'ewpq-codemirror-clike', EWPQ_ADMIN_URL. 'codemirror/mode/clike/clike.js' );
      wp_enqueue_script( 'ewpq-codemirror-php', EWPQ_ADMIN_URL. 'codemirror/mode/php/php.js' );        
   }
   
   //Load JS   
   wp_enqueue_script( 'jquery-form' );
   wp_enqueue_script( 'ewpq-select2', EWPQ_ADMIN_URL. 'js/libs/select2.min.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-drops', EWPQ_ADMIN_URL. 'js/libs/jquery.drops.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-admin', EWPQ_ADMIN_URL. 'js/admin.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-shortcode-builder', EWPQ_ADMIN_URL. 'shortcode-builder/js/shortcode-builder.js', array( 'jquery' ));
   wp_enqueue_script( 'ewpq-query-genertor', EWPQ_ADMIN_URL. 'query-generator/js/query-generator.js', array( 'jquery' ), false);
}



/*
*  ewpq_settings_page
*  Settings page
*
*  @since 1.0.0
*/

function ewpq_settings_page(){ 
   include_once( EWPQ_PATH . 'admin/views/settings.php');
}



/*
*  ewpq_repeater_page
*  Custom Repeaters
*
*  @since 1.0.0
*/

function ewpq_repeater_page(){ 
   include_once( EWPQ_PATH . 'admin/views/repeater-templates.php');
}



/*
*  ewpq_query_builder_page
*  Query Builder
*
*  @since 1.0.0
*/

function ewpq_query_builder_page(){ 
   include_once( EWPQ_PATH . 'admin/views/query-builder.php');	
}



/*
*  ewpq_go_pro
*  Go Pro
*
*  @since 1.0.0
*/

function ewpq_go_pro_page(){ 
   include_once( EWPQ_PATH . 'admin/views/go-pro.php');	
}



/*
*  ewpq_example_page
*  Examples Page
*
*  @since 1.0.0
*/

function ewpq_example_page(){ 
   include_once( EWPQ_PATH . 'admin/views/examples.php');		
}



/*
*  ewpq_get_template_list
*  List our repeaters for selection on query builder page
*
*  @since 1.0
*/

function ewpq_get_template_list(){	
   global $wpdb;
	$table_name = $wpdb->prefix . "easy_query";
	$rows = $wpdb->get_results("SELECT * FROM $table_name where type != 'default' AND type != 'saved'"); // Get all data
   $i = 0;
	foreach( $rows as $template )  {  
	   // Get repeater alias, if avaialble	
	   $i++;
	   $name = $template->name;
   	$template_alias = $template->alias;
   	if(empty($template_alias)){
   	   echo '<option name="'.$name.'" id="chk-'.$name.'" value="'.$name.'">Template #'. $i .'</option>';
   	}else{				
   	   echo '<option name="'.$name.'" id="chk-'.$name.'" value="'.$name.'">'.$template_alias.'</option>';    	
   	}
	}
}



/*
*  ewpq_query_generator
*  Get template data from database
*
*  @return   DB value
*  @since 1.0.0
*/

function ewpq_query_generator(){ 
   error_reporting(E_ALL|E_STRICT);   
	$nonce = $_POST["nonce"];
   
	// Check our nonce, if they don't match then bounce!
	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
		die('Error - unable to verify nonce, please try again.');
		
   $template = Trim(stripslashes($_POST["template"])); // template   
   $f = EWPQ_TEMPLATE_PATH. ''. $template .'.php'; // file()   
   
	$open_error = '<span class="saved-error"><b>'. __('Error Opening Template', EWPQ_NAME) .'</b></span>';
   $open_error .= '<em>'. $f .'</em>';
   $open_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the plugin directory', EWPQ_NAME);
    
	$data = file_get_contents($f) or die($open_error); // Open file
	
	echo $data;   
	
	die();
}



/*
*  ewpq_save_repeater
*  Template Save function
*
*  @return   response
*  @since 1.0.0
*/

function ewpq_save_repeater(){
	$nonce = $_POST["nonce"];
	// Check our nonce, if they don't match then bounce!
	if (! wp_verify_nonce( $nonce, 'ewpq_repeater_nonce' ))
		die('Error - unable to verify nonce, please try again.');
		
   // Get _POST Vars 
	$c = Trim(stripslashes($_POST["value"])); // Template Value
	$n = Trim(stripslashes($_POST["template"])); // Template name
	$t = Trim(stripslashes($_POST["type"])); // Template type
	$a = Trim(stripslashes($_POST["alias"])); // Template alias
	
   $f = EWPQ_TEMPLATE_PATH. ''.$n .'.php'; // File
		
   $o_error = '<span class="saved-error"><b>'. __('Error Opening File', EWPQ_NAME) .'</b></span>';
   $o_error .= '<em>'. $f .'</em>';
   $o_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the /ajax-load-more/ plugin directory', EWPQ_NAME);
   
   $w_error = '<span class="saved-error"><b>'. __('Error Saving File', EWPQ_NAME) .'</b></span>';
   $w_error .= '<em>'. $f .'</em>';
   $w_error .=  __('Please check your file path and ensure your server is configured to allow Easy Query to read and write files within the /ajax-load-more/ plugin directory', EWPQ_NAME);
   
   // Open file
	$o = fopen($f, 'w+') or die($o_error); 
	
	// Save/Write the file
	$w = fwrite($o, $c) or die($w_error);
	
	// $r = fread($o, 100000); //Read it
	fclose($o); //now close it
	
	//Save to database
	global $wpdb;
	$table_name = $wpdb->prefix . "easy_query";	
	
   if($t === 'unlimited'){ // Unlimited Templates	  
	   $data_update = array('template' => "$c", 'alias' => "$a", 'pluginVersion' => EWPQ_VERSION);
	   $data_where = array('name' => $n);
   }
   else{ // Custom Repeaters
	   $data_update = array('template' => "$c", 'pluginVersion' => EWPQ_VERSION);
	   $data_where = array('name' => "default");
   }
   
	$wpdb->update($table_name , $data_update, $data_where);
	
	//Our results
	if($w){
	    echo '<span class="saved">Template Saved Successfully</span>';
	} else {
	    echo '<span class="saved-error"><b>'. __('Error Writing File', EWPQ_NAME) .'</b></span><br/>Something went wrong and the data could not be saved.';
	}
	die();
}



/*
*  ewpq_update_repeater
*  Update repeater template from database
*
*  @return   DB value
*  @since 1.0.0
*/

function ewpq_update_repeater(){
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
}



/*
 *  ewpq_get_tax_terms
 *  Get taxonomy terms for shortcode builder
 *
 *  @return   Taxonomy Terms
 *  @since 1.0.0
 */

function ewpq_get_tax_terms(){	
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
		__('Disable CSS', EWPQ_NAME ), 
		'_ewpq_disable_css_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);
	
	/*add_settings_field(  // Hide btn
		'_ewpq_hide_btn', 
		__('Editor Button', EWPQ_NAME ), 
		'ewpq_hide_btn_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);*/
	
	add_settings_field(  // Load dynamic queries
		'_ewpq_disable_dynamic', 
		__('Dynamic Content', EWPQ_NAME ), 
		'ewpq_disable_dynamic_callback', 
		'easy-wp-query', 
		'ewpq_general_settings' 
	);	
	
}



/*
*  ewpq_general_settings_callback
*  Some general settings text
*
*  @since 2.0.0
*/

function ewpq_general_settings_callback() {
    echo '<p>' . __('Customize your version of Easy Query by updating the fields below.', EWPQ_NAME) . '</p>';
}


/*
*  _ewpq_sanitize_settings
*  Sanitize our form fields
*
*  @since 2.0.0
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
	$html .= '<label for="ewpq_hide_btn">'.__('Hide Query Builder button in WYSIWYG editor.', EWPQ_NAME).'</label>';	
	
	echo $html;
}


/*
*  _ewpq_disable_css_callback
*  Diabale Ajax Load More CSS.
*
*  @since 2.0.0
*/

function _ewpq_disable_css_callback(){
	$options = get_option( 'ewpq_settings' );
	if(!isset($options['_ewpq_disable_css'])) 
	   $options['_ewpq_disable_css'] = '0';
	
	$html = '<input type="hidden" name="ewpq_settings[_ewpq_disable_css]" value="0" />';
	$html .= '<input type="checkbox" id="ewpq_disable_css_input" name="ewpq_settings[_ewpq_disable_css]" value="1"'. (($options['_ewpq_disable_css']) ? ' checked="checked"' : '') .' />';
	$html .= '<label for="ewpq_disable_css_input">'.__('I want to use my own CSS styles', EWPQ_NAME).'<br/><span style="display:block;"><i class="fa fa-file-text-o"></i> &nbsp;<a href="'.EWPQ_URL.'/core/css/easy-query.css" target="blank">'.__('View Easy Query CSS', EWPQ_NAME).'</a></span></label>';
	
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
	$html .= '<label for="_ewpq_disable_dynamic">'.__('Disable dynamic population of categories, tags and authors in the Query Builder.<span style="display:block">Recommended only if you have an extraordinary number of categories, tags and/or authors.', EWPQ_NAME).'</label>';	
	
	echo $html;
}

