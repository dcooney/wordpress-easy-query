<?php


/*
*  eq_excerpt()
*  A custom excerpt for Easy Query
*
*  @since 2.0
*/   

// Get custom excerpt
function eq_excerpt($limit, $after = null) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);		
	if($after)
	   $excerpt = $excerpt . $after;
	   
	if($excerpt)
	   echo '<p class="eq-excerpt">'.$excerpt.'</p>';
}



/*
*  ewpq_get_current_template
*  Get the current repeater template file
*
*  @return $include (file path)
*  @since 1.0.0
*/

function ewpq_get_current_template($template, $type) {
   global $wpdb;
	$blog_id = $wpdb->blogid;
	
	$include = '';
	
	if($blog_id > 1){ // multisite
      $include = EQ_PATH. 'core/templates_'. $blog_id.'/'.$template .'.php';
      if(!file_exists($include)) //confirm file exists        			
	      $include = EQ_PATH. 'core/templates_'. $blog_id.'/default.php';
	}else{
	   $include = EQ_TEMPLATE_PATH. ''.$template .'.php';   
   } 					
	
	if(!file_exists($include)) //Global include fallback     			
	   $include = EQ_TEMPLATE_PATH . 'default.php'; 		
	
	return $include;
}



/*
*  ewpq_get_tax_query
*  Query by custom taxonomy values
*  
*  @return $args = array();
*  @since 1.0.0
*/

function ewpq_get_tax_query($post_format, $taxonomy, $taxonomy_terms, $taxonomy_operator){
   
   // Taxonomy [ONLY]
   if(!empty($taxonomy) && !empty($taxonomy_terms) && !empty($taxonomy_operator) && empty($post_format)){
      $the_terms = explode(",", $taxonomy_terms);
      $args = array(
		   'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $the_terms,
			'operator' => $taxonomy_operator,				
		);
		return $args;
	}
	
	// Post Format [ONLY]
   if(!empty($post_format) && empty($taxonomy)){
	   $format = "post-format-$post_format";
	   //If query is for standard then we need to filter by NOT IN
	   if($format == 'post-format-standard'){		   
      	if (($post_formats = get_theme_support('post-formats')) && is_array($post_formats[0]) && count($post_formats[0])) {
            $terms = array();
            foreach ($post_formats[0] as $format) {
               $terms[] = 'post-format-'.$format;
            }
         }		      
	      $args = array(
            'taxonomy' => 'post_format',
            'terms' => $terms,
            'field' => 'slug',
            'operator' => 'NOT IN',
         );
	   }else{
			$args = array(
			   'taxonomy' => 'post_format',
			   'field' => 'slug',
			   'terms' => array($format),
			);			
		}
		return $args;
	}
	
	// Taxonomy && Post Format [COMBINED]
	if(!empty($post_format) && !empty($taxonomy) && !empty($taxonomy_terms) && !empty($taxonomy_operator)){
   	$the_terms = explode(",", $taxonomy_terms);
	   $args = array(
			'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $the_terms,
			'operator' => $taxonomy_operator,
		);		
	   $format = "post-format-$post_format";
		//If query is for standard then we need to filter by NOT IN
	   if($format == 'post-format-standard'){		   
      	if (($post_formats = get_theme_support('post-formats')) && is_array($post_formats[0]) && count($post_formats[0])) {
            $terms = array();
            foreach ($post_formats[0] as $format) {
               $terms[] = 'post-format-'.$format;
            }
         }		      
	      $format_args = array(
            'taxonomy' => 'post_format',
            'terms' => $terms,
            'field' => 'slug',
            'operator' => 'NOT IN',
         );
	   }else{
			$format_args = array(
			   'taxonomy' => 'post_format',
			   'field' => 'slug',
			   'terms' => array($format),
			);			
		}
		$args[] = $format_args; // Combined format and tax $args
		return $args;	
	}
}



/*
*  ewpq_get_meta_query
*  Query by custom field values
*  
*  @return $args = array();
*  @since 1.0.0
*/

function ewpq_get_meta_query($meta_key, $meta_value, $meta_compare){
   if(!empty($meta_key) && !empty($meta_value)){ 
	   // See the docs (http://codex.wordpress.org/Class_Reference/WP_Meta_Query)
	   if($meta_compare === 'IN' || $meta_compare === 'NOT IN' || $meta_compare === 'BETWEEN' || $meta_compare === 'NOT BETWEEN'){
	   	// Remove all whitespace for meta_value because it needs to be an exact match
	   	$mv_trimmed = preg_replace('/\s+/', ' ', $meta_value); // Trim whitespace 
	   	$meta_values = str_replace(', ', ',', $mv_trimmed); // Replace [term, term] with [term,term]
	   	$meta_values = explode(",", $meta_values);	   
	   }else{	
	   	$meta_values = $meta_value;
	   }	
      $args = array(
		   'key' => $meta_key,
         'value' => $meta_values,
		   'compare' => $meta_compare,
		);
		return $args;
	}
}
