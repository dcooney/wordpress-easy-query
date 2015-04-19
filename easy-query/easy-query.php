<?php
/*
Plugin Name: Easy Query
Plugin URI: http://connekthq.com/plugins/easy-query/
Description: Create complex WordPress queries in seconds - it's that easy!
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: http://connekthq.com
Version: 1.0.0
License: GPL
Copyright: Darren Cooney & Connekt Media
*/	
	
define('EWPQ_VERSION', '1.0.0');
define('EWPQ_RELEASE', 'April 18, 2015');

/*
*  ewpq_install
*  Create table for storing repeater
*
*  @since 1.0.0
*/

register_activation_hook( __FILE__, 'ewpq_install' );
function ewpq_install() {
   
   if(is_plugin_active('easy-query-pro/easy-query-pro.php'))
      die('You must de-activate Easy Query Pro before activating Easy Query.');  	
   
	global $wpdb;	
	$table_name = $wpdb->prefix . "easy_query";
	
	$template = '<li <?php if (!has_post_thumbnail()) { ?> class="no-img"<?php } ?>><?php if ( has_post_thumbnail() ) { the_post_thumbnail(array(100,100));}?><h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3><p class="entry-meta"><?php the_time("F d, Y"); ?></p><?php the_excerpt(); ?></li>';	
		
	//Create table, if it doesn't already exist.	
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
         'pluginVersion' => EWPQ_VERSION,
      ));
	}			
}



if( !class_exists('EasyQuery') ):
	class EasyQuery {	
		
   	function __construct(){	   
   	
   		define('EWPQ_PATH', plugin_dir_path(__FILE__));
   		define('EWPQ_TEMPLATE_PATH', plugin_dir_path(__FILE__).'core/templates/');
   		define('EWPQ_URL', plugins_url('', __FILE__));
   		define('EWPQ_ADMIN_URL', plugins_url('admin/', __FILE__));
   		define('EWPQ_NAME', 'easy_query');
   		define('EWPQ_TITLE', 'Easy Query');	
   		define('EWPQ_TAGLINE', 'Create complex WordPress queries in seconds - it\'s that easy!');		
   		
   		add_action( 'wp_enqueue_scripts', array(&$this, 'ewpq_enqueue_scripts') );			
   		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'ewpq_action_links') );
   
   		add_shortcode( 'easy_query', array(&$this, 'ewpq_shortcode') );		
   		
   		// Allow shortcodes in widget areas
   		add_filter( 'widget_text', 'do_shortcode' );
   		
   		// load text domain
   		load_plugin_textdomain( 'easy-wp-query', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
   		
   		// Include Easy Query core functions
   		include_once( EWPQ_PATH . 'core/functions.php');
   		
   		// includes WP admin core
   		$this->ewpq_before_theme();	
   		
   	}	
   		
   	
   	/*
   	*  ewpq_before_theme
   	*  Load these files before the theme loads
   	*
   	*  @since 1.0.0
   	*/
   	
   	function ewpq_before_theme(){
   		if( is_admin()){
   			//include_once('admin/editor/editor.php');
   			include_once('admin/admin.php');
   		}		
      }
      
   	/*
   	*  ewpq_action_links
   	*  Add plugin action links to WP plugin screen
   	*
   	*  @since 1.0.0
   	*/   
      
      function ewpq_action_links( $links ) {
         $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=easy-query') .'">Settings</a>';
         $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=easy-query-custom-query-builder') .'">Query Builder</a>';
         return $links;
      }
   
   
   
   	/*
   	*  ewpq_enqueue_scripts
   	*  Enqueue our scripts and create our localize variables
   	*
   	*  @since 1.0.0
   	*/
   
   	function ewpq_enqueue_scripts(){
   		$options = get_option( 'ewpq_settings' );
   		if(!isset($options['_ewpq_disable_css']) || $options['_ewpq_disable_css'] != '1'){
   			wp_enqueue_style( 'easy-query', plugins_url('/core/css/easy-query.css', __FILE__ ));
   		}
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
   		   
   		
      	// $args
      	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
   		$args = array(
   			'post_type'             => $post_type,
   			'posts_per_page'        => $posts_per_page,
   			'offset'                => $offset,
   			'order'                 => $order,
   			'orderby'               => $orderby,		
   			'post_status'           => $post_status,		
   			'ignore_sticky_posts'   => false,
   			'paged'                 => $paged,
   		);	
   		
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
   	         ob_start(); // As seen here - http://stackoverflow.com/a/1288634/921927
      			$file = ewpq_get_current_template($template, $template_type);
      			include($file);
      			$output .= ob_get_contents();
      			ob_end_clean();	   					
            endwhile; 
            wp_reset_query();             
   			$output .= '</div>';
   			$output .= '</' . $container . '>';
   		
      		// Paging 
      		if($paging === 'true'){
         		ob_start();
      			$paging = EWPQ_PATH . 'core/paging.php';
      			include($paging);
      			$output .= ob_get_contents();
      			ob_end_clean();
   			}	  
   			
   			$output .= '</div>';		
            
         endif;		
   		return $output;
   	}  	  	
   }
   
   
   /*
   *  AjaxLoadMore
   *  The main function responsible for returning the one true EasyWPQuery Instance to functions everywhere.
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
