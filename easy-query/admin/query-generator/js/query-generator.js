var _ewpq_admin = _ewpq_admin || {};

jQuery(document).ready(function($) {
   "use strict";  
   
   if($('#query-output').length){
	   var queryGenerator = CodeMirror.fromTextArea(document.getElementById("query-output"), {
          mode:  "application/x-httpd-php",
          lineNumbers: true,
          lineWrapping: true,
          indentUnit: 0,
          matchBrackets: true,
          viewportMargin: Infinity,
          extraKeys: {"Ctrl-Space": "autocomplete"},
      });
   }
   
   
   
   /*
   *  query_generator
   *  Generate a unique WP_Query
   *
   *  @since 1.0.0
   */
   
   _ewpq_admin.buildQuery = function(data){
      var placement = $('#query-output'),
          container_type = $('input[name=container_type]:checked').val(),
          classes = $('input#classes').val();       
        
      // Post Types  
      var post_types = '',
          post_type_count = 0;
      $('.post_types input[type=checkbox]:checked').each(function(e){         
         post_type_count++;
         if(post_type_count>1){
            post_types += ",'" + $(this).data('type') +"'";
         }else{
            post_types += "'" + $(this).data('type') + "'"; 
         }
      });        
      
      // Category In
      var cat_in = $('#category-select').val();   
      if(cat_in){
         var category__in = '',
         	 category__in_count = 0;
			$(cat_in).each(function(e){         
	         category__in_count++;
	         if(category__in_count>1){
	            category__in += ",'" + cat_in[e] +"'";
	         }else{
	            category__in += "'" + cat_in[e] + "'"; 
	         }
	      });
		}
		 
		// Category not in  
      var cat_not_in = $('#category-exclude-select').val();
      if(cat_not_in){
         var category__not_in = '',
         	 category__not_in_count = 0;
			$(cat_not_in).each(function(e){         
	         category__not_in_count++;
	         console.log(cat_not_in[e]);
	         if(category__not_in_count>1){
	            category__not_in += ",'" + cat_not_in[e] +"'";
	         }else{
	            category__not_in += "'" + cat_not_in[e] + "'"; 
	         }
	      });
		}
      
      // Tag in
      var tag_in = $('#tag-select').val(); 
      if(tag_in){
         var tag__in = '',
         	 tag__in_count = 0;
			$(tag_in).each(function(e){         
	         tag__in_count++;
	         if(tag__in_count>1){
	            tag__in += ",'" + tag_in[e] +"'";
	         }else{
	            tag__in += "'" + tag_in[e] + "'"; 
	         }
	      });
		}
        
      // Tag not in   
      var tag_not_in = $('#tag-exclude-select').val();
      if(tag_not_in){
         var tag__not_in = '',
         	 tag__not_in_count = 0;
			$(tag_not_in).each(function(e){         
	         tag__not_in_count++;
	         console.log(tag_not_in[e]);
	         if(tag__not_in_count>1){
	            tag__not_in += ",'" + tag_not_in[e] +"'";
	         }else{
	            tag__not_in += "'" + tag_not_in[e] + "'"; 
	         }
	      });
		}
      
      // Taxonomy
      var taxonomy = $('#taxonomy-select').val(); 
      
      var taxonomy_terms = '',
          taxonomy_terms_count = 0;
      $('#tax-terms-container input[type=checkbox]:checked').each(function(e){         
         taxonomy_terms_count++;
         if(taxonomy_terms_count>1){
            taxonomy_terms += ",'" + $(this).data('type') +"'";
         }else{
            taxonomy_terms += "'" + $(this).data('type') + "'"; 
         }
      });  
      var taxonomy_operator = $('#tax-operator-select input[type=radio]:checked').val();
		
      var year = $('#input-year').val();
      var monthnum = $('#input-month').val();
      var day = $('#input-day').val();
      
      var meta_key = $.trim($('#meta-key').val());
      var meta_value = $.trim($('#meta-value').val());
      var meta_compare = $.trim($('#meta-compare').val());
      
      var author = $('#author-select').val();
      
      var search = $('#search-term').val();
      
      var post_status = $('#post-status').val();
      
      var order = $('#post-order').val();
      var orderby = $('#post-orderby').val();
      
      var include_posts = $('#include-posts').val();
		if(include_posts) 
		   var include_posts = include_posts.split(',');
      
      var exclude = $('#exclude-posts').val();
		if(exclude) 
		   exclude = exclude.split(',');
		   
      var offset = $('#offset-select').val();
      if(offset === '') 
         offset = 0;
            
      var posts_per_page = $('#display_posts-select').val();
         if(posts_per_page == 0)
            posts_per_page = "-1";
      
      var is_paged = $('.paging input[name=enable_paging]:checked').val();   
      
      
      // ************************
   	// Build the query	
      // ************************
      
      var $q = '';
      $q += "<?php ";
      $q += "\n";
      
      $q += "$args = array(\n";
      
      $q += "  'post_type' => array("+ post_types + "), \n";
     
		// Category
      if(category__in)
      $q += "  'category__in' => array("+ category__in + "), \n";     
      
      // Cat Not In
      if(category__not_in)
      $q += "  'category__not_in' => array("+ category__not_in + "), \n"; 
     
      // Tag
      if(tag__in)
      $q += "  'tag__in' => array("+ tag__in + "), \n";    
      
      // Tag Not In
      if(tag__not_in)
      $q += "  'tag__not_in' => array("+ tag__not_in + "), \n";  
     
      // Date
      if(year)
      $q += "  'year' => '"+ year + "', \n";   
     
      if(monthnum)
      $q += "  'monthnum' => '"+ monthnum + "', \n";   
     
      if(day)
      $q += "  'day' => '"+ day + "', \n"; 
     
      // Taxonomy 
      if(taxonomy && taxonomy_terms && taxonomy_operator){
         $q += "  'tax_query' => array(\n";
         $q += "       'relation' => 'AND',\n"
         $q += "       array(\n";
         $q += "          'taxonomy'  => '"+ taxonomy + "', \n";
         $q += "          'field'     => 'slug', \n";
         $q += "          'terms'     => array("+ taxonomy_terms + "), \n";
         $q += "          'operator'  => '"+ taxonomy_operator + "', \n";    
         $q += "      ),\n";
         $q += "  ),\n";
      }
     
      // Custom Fields   
      if(meta_key && meta_value && meta_compare){
         $q += "  'meta_query' => array(\n";
         $q += "       array(\n";
         $q += "          'key'     => '"+ meta_key + "', \n";
         $q += "          'value'   => '"+ meta_value + "', \n";
         $q += "          'compare' => '"+ meta_compare + "', \n";    
         $q += "      ),\n";
         $q += "  ),\n";
      }
     
      // Author
      if(author)
      $q += "  'author' => '"+ author + "', \n";
     
      // Search
      if(search)
      $q += "  's' => '"+ search + "', \n";
      
      // Include Posts
      if(include_posts)
      $q += "  'post__in' => array("+ include_posts + "), \n";
      
      // Exclude Posts
      if(exclude)
      $q += "  'post__not_in' => array("+ exclude + "), \n";
      
      // Post Status
      if(post_status)
      $q += "  'post_status' => '"+ post_status + "', \n";     
      	
      // Order
      if(order)
      $q += "  'order' => '"+ order + "', \n";      
      
      // OrderBy	
      if(orderby)
      $q += "  'orderby' => '"+ orderby + "', \n";
      	
      // Offset
      if(offset)
      $q += "  'offset' => "+ offset + ", \n";
      	
      // Posts Per Page	
      if(posts_per_page)      
      $q += "  'posts_per_page' => "+ posts_per_page + ", \n";
      
      // Paged
      $q += "  'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1, \n";
      $q += ");\n";
      
      
      $q += "\n";
      
      // WP_QUERY 
      $q += "// WP_Query";
      $q += "\n";
      $q += "$eq_query = new WP_Query( $args );";
      $q += "\n";
      $q += "$offset = "+ offset +";";
      $q += "\n";
      $q += "$eq_total_posts = $eq_query->found_posts - $offset;";
      $q += "\n";
      $q += "if ($eq_query->have_posts()) : // The Loop";
      $q += "\n";
      $q += "$eq_count = $args['paged'] * $args['posts_per_page'] - $args['posts_per_page']; // Count items";
      $q += "\n";
      $q += "?>";
      $q += "\n";
      $q += "<div class=\"wp-easy-query\"  data-total-posts=\"<?php echo $eq_total_posts; ?>\">"
      $q += "\n";
      $q += "<div class=\"wp-easy-query-posts\">"
      $q += '\n';
      $q += '<' + container_type + ' class="'+ classes +'">';
      $q += "\n";
      $q += "<?php ";
      $q += "\n";
      $q += "while ($eq_query->have_posts()): $eq_query->the_post();";
      $q += "\n"; 
      $q += "$eq_count++;";  
      $q += "\n";  
      $q += "?>";
      $q += "\n";
      $q += data;
      $q += "\n"; 
      $q += "<?php endwhile; wp_reset_query(); ?> ";   
      $q += "\n"; 
      $q += "</" + container_type + ">"   
      $q += "\n"; 
      $q += "</div>";
      $q += "\n";
      
      // Paging
      if(is_paged === 'true'){
         $q += "<?php include_once(EWPQ_PATH.'core/paging.php'); ?>";
         $q += "\n";     
      } 
      
      $q += "</div>"
      $q += "\n";
      
      $q += "<?php endif; ?> ";
      
      // Set CodeMirror and textarea Val
      queryGenerator.setValue($q);      
      placement.val($q);      
   	$('.CodeMirror').removeClass('loading');
	}
	
	
	
	/*
    *  _ewpq_admin.getTemplateValue
    *  Get value of template from DB for placement in query generator
    *  
    *  @since 1.0.0
    */  
	
	_ewpq_admin.getTemplateValue = function(template) {	   							
		$.ajax({
			type: 'POST',
			url: ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_query_generator',
				template: template,
				nonce: ewpq_admin_localize.ewpq_admin_nonce,
			},
			success: function(response) {	
			   var data = response;
			   _ewpq_admin.buildQuery(data);							
			},
			error: function(xhr, status, error) {
            console.log('An error has occurred while retrieving template data.');
            $('.CodeMirror').removeClass('loading');
			}
      });
      
	}
	
	// Generate query button click
	$('#generate-query').click(function(e){
   	$('.CodeMirror').addClass('loading');
   	e.preventDefault();
   	var template = $('select#template-select').val();	
   	_ewpq_admin.getTemplateValue(template);
	});	
	
});