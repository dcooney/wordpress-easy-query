<?php
/*
Plugin Name: Easy Query Lite
Plugin URI: https://connekthq.com/plugins/easy-query/
Description: A query builder plugin for WordPress.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 2.0
License: GPL
Copyright: Darren Cooney & Connekt Media
*/	
	
define('EQ_VERSION', '2.0');
define('EQ_RELEASE', 'December 14, 2016');



/*
*  ewpq_install
*  Create table for storing repeater
*
*  @since 1.0.0
*/

register_activation_hook( __FILE__, 'eq_install' );
add_action( 'wpmu_new_blog', 'eq_install' );
function eq_install($network_wide) {   	
	global $wpdb;	
	add_option( "easy_query_version", EQ_VERSION ); // Add to WP Option tbl	
	
   if ( is_multisite() && $network_wide ) {      
      
      // Get all blogs in the network and activate plugin on each one
      $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
      foreach ( $blog_ids as $blog_id ) {
         switch_to_blog( $blog_id );
         eq_create_table();
         restore_current_blog();
      }
   } else {
      eq_create_table();
   } 		
}

function eq_create_table(){
   
   if(is_plugin_active('easy-query-pro/easy-query-pro.php'))
      die(__('You must de-activate Easy Query Pro before activating Easy Query.', 'easy-query'));
            
      
	global $wpdb;	
	$table_name = $wpdb->prefix . "easy_query";
	$blog_id = $wpdb->blogid;
	
	$template = '<li <?php if (!has_post_thumbnail()) { ?> class="no-img"<?php } ?>><?php if ( has_post_thumbnail() ) { the_post_thumbnail("eq-thumbnail");}?><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p class="entry-meta"><?php the_time("F d, Y"); ?></p><?php the_excerpt(); ?></li>';
	
	
	/* 
    * Multisite
    * If multisite blog and it's not id = 1, create new folder and default template 
    *
    */
   if($blog_id > 1){	   
	   $dir = EQ_PATH. 'core/templates_'. $blog_id;
   	if( !is_dir($dir) ){
         mkdir($dir);
         $tmp = fopen($dir.'/default.php', 'w');
			$w = fwrite($tmp, $template);
			fclose($myfile);
   	}
	}   	
	
	/* 
    * Create table if it doesn't already exist.	 
    */
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
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
		
		//Insert the default data in created table
      $wpdb->insert($table_name, array(
         'name' => 'default',  
         'type' => 'default', 
         'alias' => '', 
         'template' => $template,
         'pluginVersion' => EQ_VERSION,
      ));
	}	
	
}



if( !class_exists('EasyQuery') ):
	class EasyQuery {	
		
   	function __construct(){	   
   	
   		define('EQ_PATH', plugin_dir_path(__FILE__));
   		define('EQ_PAGING', plugin_dir_path(__FILE__).'core/paging.php');
   		define('EQ_TEMPLATE_PATH', plugin_dir_path(__FILE__).'core/templates/');
   		define('EQ_URL', plugins_url('', __FILE__));
   		define('EQ_ADMIN_URL', plugins_url('admin/', __FILE__));
   		define('EQ_NAME', 'easy_query');
   		define('EQ_SLUG', 'easy-query');
   		define('EQ_PRO_URL', 'https://connekthq.com/plugins/easy-query/');
   		define('EQ_TITLE', 'Easy Query Lite');	
   		define('EQ_TAGLINE', 'Create complex WordPress queries with the click of a button!');		
   		
   		add_action( 'wp_enqueue_scripts', array(&$this, 'eq_enqueue_scripts') ); // scripts		
   		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'eq_action_links') ); // dashboard links
   		add_filter( 'widget_text', 'do_shortcode' ); // Allow shortcodes in widget areas   			
   		add_action( 'after_setup_theme',  array(&$this, 'eq_image_sizes') ); // Add image sizss   	   		
   		load_plugin_textdomain( 'easy-query', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); // load text domain
   		add_shortcode( 'easy_query', array(&$this, 'ewpq_shortcode') ); // [easy_query]   		
   		
   		$this->eq_includes();	
   		
   	}	
   		
   		
   	
   	/*
   	*  eq_includes
   	*  Load these files before the theme loads
   	*
   	*  @since 1.0.0
   	*/
   	
   	function eq_includes(){
   		if( is_admin()){
   			include_once('admin/editor/editor.php');
   			include_once('admin/admin.php');
   			include_once('admin/admin-helpers.php');
   		}
   		include_once( EQ_PATH . 'core/functions.php');		
      }
      
      
      
   	/*
   	*  eq_action_links
   	*  Add plugin action links to WP plugin screen
   	*
   	*  @since 1.0.0
   	*/   
      
      function eq_action_links( $links ) {
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=easy-query') .'">'. __('Settings', 'easy-query').'</a>';
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=easy-query&tab=query-builder') .'">'. __('Query Builder', 'easy-query').'</a>';
         return $links;
      }
   
   
   
   	/*
   	*  eq_enqueue_scripts
   	*  Enqueue our scripts and create our localize variables
   	*
   	*  @since 1.0.0
   	*/
   
   	function eq_enqueue_scripts(){
   		$options = get_option( 'ewpq_settings' );
   		if(!isset($options['_ewpq_disable_css']) || $options['_ewpq_disable_css'] != '1'){
   			wp_enqueue_style( 'easy-query', plugins_url('/core/css/easy-query.css', __FILE__ ));
   		}
   	}
   	
   	
   	
   	/*
		*  eq_image_sizes
		*  Add default image size
		*
		*  @since 2.0.0
		*/
		
		public function eq_image_sizes(){   
			add_image_size( 'eq-thumbnail', 120, 120, true); // Custom thumbnail size
		} 
   	
   
   
   	/*
   	*  ewpq_shortcode
   	*  The Easy WP Query shortcode
   	*
   	*  @since 1.0.0
   	*/
   
   	function ewpq_shortcode( $atts, $content = null ) {
   		
   		$a = shortcode_atts(array(
   		   'container' => 'ul',
   		   'classes' => '',
				'posts_per_page' => '6',
				'paging' => 'true',
				'template' => 'default',
				'post_type' => 'post',
				'post_format' => '',
				'category__in' => '',	
				'category__not_in' => '',	
				'tag__in' => '',
				'tag__not_in' => '',
				'taxonomy' => '',
				'taxonomy_terms' => '',
				'taxonomy_operator' => 'IN',	
				'meta_key' => '',
				'meta_value' => '',
				'meta_compare' => 'IN',
				'year' => '',
				'month' => '',
				'day' => '',
				'author' => '',
				'search' => '',
				'custom_args' => '',
				'post__in' => '',
				'post__not_in' => '',					
				'post_status' => 'publish',					
				'order' => 'DESC',
				'orderby' => 'date',
				'offset' => '0',	
			), $atts);
			
			// Containers Options
			$container = $a['container'];	
			$classes = $a['classes'];				
			
   		$posts_per_page = $a['posts_per_page'];
   		$paging = $a['paging'];
			
			// Repeater
			$template = $a['template'];		
   		$template_type = preg_split('/(?=\d)/', $template, 2); // split $template value at number to determine type
   		$template_type = $template_type[0]; // default | template_	
   		
   		// Post type & Format
      	$post_type = explode(",", $a['post_type']);  
      	$post_format = $a['post_format'];
      	
      	// Cat & Tag
   		$category__in = trim($a['category__in']);
   		$category__not_in = trim($a['category__not_in']);
   		$tag__in = $a['tag__in'];
   		$tag__not_in = $a['tag__not_in'];
   		
   		// Taxonomy
   		$taxonomy = $a['taxonomy'];
   		$taxonomy_terms = $a['taxonomy_terms'];
   		$taxonomy_operator = $a['taxonomy_operator'];
   		
   		// Custom Fields
   		$meta_key = $a['meta_key'];
   		$meta_value = $a['meta_value'];
   		$meta_compare = $a['meta_compare'];
   		
   		// Search
   		$s = $a['search'];
   		
   		// Custom Args
   		$custom_args = $a['custom_args'];
   		
   		// Date
   		$year = $a['year'];
   		$month = $a['month'];
   		$day = $a['day'];
   		
   		// Author ID
   		$author_id = $a['author'];		
   		
   		// Ordering
   		$order = $a['order'];
   		$orderby = $a['orderby'];
   		
   		// Exclude, Offset, Status
   		$post__in = $a['post__in'];	
   		$post__not_in = $a['post__not_in'];	
   		$offset = $a['offset'];
   		$post_status = $a['post_status'];
   		
   		// Lang Support   		
   		$lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : ''; // WPML - http://wpml.org   		
   		if (function_exists('pll_current_language')) // Polylang - https://wordpress.org/plugins/polylang/
   		   $lang = pll_current_language();   		   
         if (function_exists('qtrans_getLanguage')) // qTranslate - https://wordpress.org/plugins/qtranslate/
   		   $lang = qtrans_getLanguage();  
   		   
   		
      	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      	$page = $paged - 1; // Our current page num
      	
      	// WP_Query $args
   		$args = array(
   			'post_type'             => $post_type,
   			'posts_per_page'        => $posts_per_page,
   			'order'                 => $order,
   			'orderby'               => $orderby,		
   			'post_status'           => $post_status,		
   			'ignore_sticky_posts'   => false,
   			'paged'                 => $paged,
   		);	
   		
   		
   		// Offset
   		if($offset > 0){
      		$args['offset'] = $offset + ($posts_per_page*$page);
   		}
   		
   		// Post Format & taxonomy
   		if(!empty($post_format) || !empty($taxonomy)){	
   		   $args['tax_query'] = array(			
   				'relation' => 'AND',
   		      ewpq_get_tax_query($post_format, $taxonomy, $taxonomy_terms, $taxonomy_operator)
   		   );
   	   }	
   	   
   	   // Category
   		if(!empty($category__in)){
   		   $include_cats = explode(",",$category__in);
   			$args['category__in'] = $include_cats;
   		}
         
         // Category Not In
   		if(!empty($category__not_in)){
   		   $exclude_cats = explode(",",$category__not_in);
   			$args['category__not_in'] = $exclude_cats;
   		}
         
         // Tag
   		if(!empty($tag__in)){
   		   $include_tags = explode(",",$tag__in);
   			$args['tag__in'] = $include_tags;
   		} 		 
         
         // Tag Not In
   		if(!empty($tag__not_in)){
   		   $exclude_tags = explode(",",$tag__not_in);
   			$args['tag__not_in'] = $exclude_tags;
   		}
   	    
   	   // Date (not using date_query as there was issue with year/month archives)
   		if(!empty($year)){
      		$args['year'] = $year;
   	   } 
   	   if(!empty($month)){
      		$args['monthnum'] = $month;
   	   }  
   	   if(!empty($day)){
      		$args['day'] = $day;
   	   }	
   	   
   	   // Meta Query
   		if(!empty($meta_key) && !empty($meta_value)){
   			$args['meta_query'] = array(
   			   ewpq_get_meta_query($meta_key, $meta_value, $meta_compare)				
   			);
   	   }
         
         // Author
   		if(!empty($author_id)){
   			$args['author'] = $author_id;
   		}
         
         // Search Term
   		if(!empty($s)){
   			$args['s'] = $s;
   		}  
   		
   		// Custom Args      
   		if(!empty($custom_args)){
   			$custom_args_array = explode(";",$custom_args); // Split the $custom_args at ','
   			foreach($custom_args_array as $argument){ // Loop each $argument        			 
      			$argument = preg_replace('/\s+/', '', $argument); // Remove all whitespace 	      				
   			   $argument = explode(":",$argument);  // Split the $argument at ':' 
   			   $argument_arr = explode(",", $argument[1]);  // explode $argument[1] at ','
   			   if(sizeof($argument_arr) > 1){
   			      $args[$argument[0]] = $argument_arr;
   			   }else{
   			      $args[$argument[0]] = $argument[1];      			   
   			   }
   			   
   			   
   			}
   		}
   	   
         // Meta_key, used for ordering by meta value
         if(!empty($meta_key)){
            $args['meta_key'] = $meta_key;
         }    	   
         
   		// include posts
   		if(!empty($post__in)){
   			$post__in = explode(",",$post__in);
   			$args['post__in'] = $post__in;
   		} 	   
         
   		// Exclude posts
   		if(!empty($post__not_in)){
   			$post__not_in = explode(",",$post__not_in);
   			$args['post__not_in'] = $post__not_in;
   		}
   		
         // Language
   		if(!empty($lang)){
   			$args['lang'] = $lang;
   		}            
           		
   		
   		// WP_Query
   		$eq_query = new WP_Query( $args );	
         $eq_total_posts = $eq_query->found_posts - $offset;
         $output = '';
         
   		// The Loop
   		if ($eq_query->have_posts()) : 
   		   
   		   $eq_count = $paged * $posts_per_page - $posts_per_page; // Count items
   		   $output .= '<div class="wp-easy-query" data-total-posts="'. $eq_total_posts .'">';   		   
   			$output .= '<div class="wp-easy-query-posts">';  
   			$output .= '<' . $container . ' class="'. $classes.'">';   
   			while ($eq_query->have_posts()): $eq_query->the_post();	
   				$eq_count++;                
   	         ob_start();
      			$file = ewpq_get_current_template($template, $template_type);
      			include($file);
      			$output .= ob_get_clean();	   					
            endwhile; 
            wp_reset_query();             
   			$output .= '</div>';
   			$output .= '</' . $container . '>';
   		
      		// Paging 
      		if($paging === 'true'){
         		ob_start();
      			include(EQ_PAGING);
      			$output .= ob_get_clean();
   			}	  
   			
   			$output .= '</div>';		
            
         endif;	
         	
   		return $output;
   	}  	  	
   }
   
   
   /*
   *  EasyQuery
   *  The main function responsible for returning the one true EasyQuery Instance to functions everywhere.
   *
   *  @since 1.0.0
   */
   
   function EasyQuery(){
   	global $easy_query;
   
   	if( !isset($easy_query) )
   	{
   		$easy_query = new EasyQuery();
   	}
   
   	return $easy_query;
   }
   
   
   // initialize
   EasyQuery();

endif; // class_exists check

