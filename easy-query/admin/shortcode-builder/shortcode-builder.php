<span class="toggle-all"><span class="inner-wrap"><em class="collapse"><?php _e('Collapse All', EWPQ_NAME); ?></em><em class="expand"><?php _e('Expand All', EWPQ_NAME); ?></em></span></span>

<?php 
   $alm_options = get_option( 'ewpq_settings' );         
   if(!isset($alm_options['_ewpq_disable_dynamic'])) // Check if '_ewpq_disable_dynamic is set within settings
	   $alm_options['_ewpq_disable_dynamic'] = '0';		
	   
	$disable_dynamic_content = $alm_options['_ewpq_disable_dynamic'];   
?>
   

<!-- Container Type -->
<div class="row checkbox container_type" id="alm-container-type">
   <h3 class="heading"><?php _e('Container Options', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4><?php _e('Type', EWPQ_NAME); ?></h4>
		 	<p><?php _e('Select the container type that will wrap your Easy Query templates.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">	               
            <ul>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="ul" id="ul" checked="checked">
                 <label for="ul"><?php _e('&lt;ul&gt;<span>&lt;!-- posts --&gt;</span>&lt;/ul&gt;', EWPQ_NAME); ?></label>
                </li>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="ol" id="ol">
                 <label for="ol"><?php _e('&lt;ol&gt;<span>&lt;!-- posts --&gt;</span>&lt;/ol&gt;', EWPQ_NAME); ?></label>
                </li>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="div" id="div">
                 <label for="div"><?php _e('&lt;div&gt;<span>&lt;!-- posts --&gt;</span>&lt;/div&gt;', EWPQ_NAME); ?></label>
                </li>
            </ul>
         </div>
      </div>
      <div class="clear"></div>
      <hr/>
      <div class="section-title">
         <h4><?php _e('Classes', EWPQ_NAME); ?></h4>
		 	<p><?php _e('Target your content by adding custom classes to the container.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element" name="classes" type="text" id="classes" value="" placeholder="blog-listing listing content etc.">
         </div>
      </div>
   </div>
</div>  


<!-- Templates -->
<div class="row template" id="alm-repeaters">   		
   <h3 class="heading"><?php _e('Template', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <p><?php _e('Select which <a href="admin.php?page=easy-query-templates" target="_parent">template</a> you would like to use.', EWPQ_NAME); ?></p>
      </div>
      <div class="wrap"><div class="inner">
         <select id="template-select" disabled="disabled">
            <option value="default" selected="selected">Default</option>
         </select>
      </div>
   </div>   
   <?php               
   // Go Pro!
   if (!has_action('ewpq_display_templates')){
      echo '<div class="row no-brd">';
      include( EWPQ_PATH . 'admin/includes/cta/extend.php');
      echo '</div>';                  
   }
   ?>
</div>
</div>


<?php
// List registered post_types
$pt_args = array(
   'public'   => true
);
$types = get_post_types($pt_args);
if($types){
	echo '<div class="row checkboxes post_types" id="alm-post-types">';   		
	echo '<h3 class="heading">'.__('Post Types', EWPQ_NAME). '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>'.__('Select Post Types to query.', EWPQ_NAME). '</p>';
	echo '</div>';
	echo '<div class="wrap"><div class="inner"><ul>';
    foreach( $types as $type ){
     $typeobj = get_post_type_object( $type );
     $name = $typeobj->name;
     if( $name != 'revision' && $name != 'attachment' && $name != 'nav_menu_item' && $name != 'acf'){
         echo '<li><input class="alm_element" type="checkbox" name="chk-'.$typeobj->name.'" id="chk-'.$typeobj->name.'" data-type="'.$typeobj->name.'"><label for="chk-'.$typeobj->name.'">'.$typeobj->labels->singular_name.'</label></li>';
		}
    }
    echo '</ul></div></div>';
    echo '</div>';
    echo '</div>';
}

// List Categories

if($disable_dynamic_content){
   $cats = 'null';
}else{
   $cats = get_categories();
}
if($cats){ ?>		
<div class="row checkboxes categories" id="alm-categories">
   <h3 class="heading"><?php _e('Category', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4>Include</h4>
         <p><?php _e('A comma separated list of categories to include by id. (8, 15, 22 etc...)', EWPQ_NAME); ?><br/>
         &raquo; <a href="admin.php?page=easy-query-examples#example-category">
         <?php _e('view example', EWPQ_NAME); ?></a></p>
      </div>
      <div class="wrap">
         <div class="inner">            
            <?php
            if(!$disable_dynamic_content){
               echo '<select class="alm_element multiple" name="category-select" id="category-select" multiple="multiple">';
               foreach( $cats as $cat ){
                  echo '<option name="chk-'.$cat->slug.'" id="chk-'.$cat->slug.'" value="'.$cat->term_id.'">'.$cat->name.'</option>';
               }
               echo '</select>';
            }else{
               echo '<input type="text" class="alm_element numbers-only" name="category-select" id="category-select" placeholder="8, 15, 22 etc...">';
            }
            ?>
         </div>
      </div>
      
      <div class="clear"></div>
      <hr/>
   
      <div class="section-title">         
         <h4><?php _e('Exclude', EWPQ_NAME); ?></h4>
         <p><?php _e('A comma separated list of categories to exclude by ID. (3, 12, 35 etc..)', EWPQ_NAME); ?></p>
      </div>
      <div class="wrap">
         <div class="inner">           
            <?php
            if(!$disable_dynamic_content){
               echo '<select class="alm_element multiple" name="category-exclude-select" id="category-exclude-select" multiple="multiple">';
               foreach( $cats as $cat ){
                  echo '<option name="chk-'.$cat->term_id.'" id="chk-'.$cat->term_id.'" value="'.$cat->term_id.'">'.$cat->name.'</option>';
               }
               echo '</select>';
            }else{
               echo '<input type="text" class="alm_element numbers-only" name="category-exclude-select" id="category-exclude-select" placeholder="10, 12, 19 etc...">';
            }
            ?>
         </div>
         <div class="clear"></div>
      </div>     
   </div>
</div>
 
 <?php }
 
 // Tags	    
if($disable_dynamic_content){
   $tags = 'null';
}else{
   $tags = get_tags();
}
if($tags){ ?>
<div class="row checkboxes tags" id="alm-tags">
	<h3 class="heading"><?php _e('Tag', EWPQ_NAME); ?></h3>
	<div class="expand-wrap">
		<div class="section-title">
		<h4><?php _e('Include', EWPQ_NAME); ?></h4>
		<p><?php _e('A comma separated list of tags to include by id. (199, 231, 80 etc...)', EWPQ_NAME); ?><br/>&raquo; <a href="admin.php?page=easy-query-examples#example-tag">view example</a></p>
		</div>
		<div class="wrap">
		   <div class="inner">
           <?php
      	  if(!$disable_dynamic_content){
      	     echo '<select class="alm_element multiple" name="tag-select" id="tag-select" multiple="multiple">';
          	  foreach( $tags as $tag ){
                  echo '<option name="chk-'.$tag->slug.'" id="chk-'.$tag->slug.'" value="'.$tag->term_id.'">'.$tag->name.'</option>';
         	  }
         	  echo '</select>';
      	  }else{
         	  echo '<input type="text" class="alm_element numbers-only" name="tag-select" id="tag-select" placeholder="199, 231, 80 etc...">';
      	  }
      	   ?>
         </div>
	  </div>
	  <div class="clear"></div>
      <hr/>
   
      <div class="section-title">         
         <h4><?php _e('Exclude', EWPQ_NAME); ?></h4>
         <p><?php _e('A comma separated list of tags to exclude by ID. (30, 12, 99 etc..)', EWPQ_NAME); ?></p>
      </div>
      <div class="wrap">
         <div class="inner">           
            <?php
            if(!$disable_dynamic_content){
               echo '<select class="alm_element multiple" name="tag-exclude-select" id="tag-exclude-select" multiple="multiple">';
               foreach( $tags as $tag ){
                  echo '<option name="chk-'.$tag->term_id.'" id="chk-'.$tag->term_id.'" value="'.$tag->term_id.'">'.$tag->name.'</option>';
               }
               echo '</select>';
            }else{
               echo '<input type="text" class="alm_element numbers-only" name="tag-exclude-select" id="tag-exclude-select" placeholder="10, 12, 19 etc...">';
            }
            ?>
         </div>
         <div class="clear"></div>
      </div>
  </div>
</div>
<?php } ?>
 
<?php
// Taxonomies
$tax_args = array(
	'public'   => true,
	'_builtin' => false	
); 
$tax_output = 'objects'; // or objects
$taxonomies = get_taxonomies( $tax_args, $tax_output ); 
if ( $taxonomies ) {
	echo '<div class="row taxonomy" id="alm-taxonomy">';   		
	echo '<h3 class="heading">'.__('Taxonomy', EWPQ_NAME). '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>'.__('Select your custom taxonomy then select the terms and operator.', EWPQ_NAME). '</p>';
	echo '</div>';
	
	echo '<div class="wrap">';
	
	echo '<div class="inner">';
	echo '<select class="alm_element" name="taxonomy-select" id="taxonomy-select">';
	echo '<option value="" selected="selected">-- ' . __('Select Taxonomy', EWPQ_NAME) . ' --</option>';
    foreach( $taxonomies as $taxonomy ){
      echo '<option name="chk-'.$taxonomy->query_var.'" id="chk-'.$taxonomy->query_var.'" value="'.$taxonomy->query_var.'">'.$taxonomy->label.'</option>';
    }
    echo '</select>';
    echo '</div>';
    
    echo '<div id="taxonomy-extended">';
    echo '<div class="inner border-top" id="tax-terms">';
    echo '<label class="full">'. __('Taxonomy Terms:', EWPQ_NAME) .'</label>';
    echo '<div id="tax-terms-container" class="checkboxes"></div>';
    echo '</div>';
    
    echo '<div class="inner border-top" id="tax-operator-select">';
    echo '<label class="full">'. __('Taxonomy Operator:', EWPQ_NAME) .'</label>';
    echo '<ul class="radio">';
    echo '<li><input class="alm_element" name="tax-operator" id="tax-in-radio" value="IN" type="radio" checked="checked"><label for="tax-in-radio">IN (default)</li>';
    echo '<li><input class="alm_element" name="tax-operator" id="tax-not-in-radio" value="NOT IN" type="radio"><label for="tax-not-in-radio">NOT IN</li>';
    echo '</ul>';
    echo '</div>';	    
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}?>


<!-- Date -->
<div class="row input date" id="alm-date">
   <h3 class="heading"><?php _e('Date', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enter a year, month(number) and day to query by date archive.<br/>&raquo; <a href="admin.php?page=easy-query-examples#example-date">view example</a>', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <div class="wrap-30">
               <?php $today = getdate(); ?>
               <label for="input-year" class="full"><?php _e('Year:', EWPQ_NAME); ?></label>
               <input name="input-year" class="alm_element sm numbers-only" type="text" id="input-year" maxlength="4" placeholder="<?php echo $today['year']; ?>">
            </div>
            <div class="wrap-30">
               <label for="input-month" class="full"><?php _e('Month:', EWPQ_NAME); ?></label>
               <input name="input-month" class="alm_element sm numbers-only" type="text" id="input-month" maxlength="2" placeholder="<?php echo $today['mon']; ?>">
            </div>
            <div class="wrap-30">
               <label for="input-day" class="full"><?php _e('Day:', EWPQ_NAME); ?></label>
               <input name="input-day" class="alm_element sm numbers-only" type="text" id="input-day" maxlength="2" placeholder="<?php echo $today['mday']; ?>">
            </div>
         </div>
      </div>
   </div>
</div>
     

<?php // Custom Fields ?>
<div class="row input meta-key" id="alm-meta-key">
   <h3 class="heading"><?php _e('Custom Fields (Meta)', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <p><?php _e('Query by <a href="http://codex.wordpress.org/Class_Reference/WP_Meta_Query" target="_blank">custom fields</a>.  Enter your key(name) and value, then select your operator.', EWPQ_NAME); ?></p>
      </div>
      <div class="wrap">
         <div class="inner">
            <?php // Meta Key ?>
            <label for="meta-key" class="full"><?php _e('Key (Name):', EWPQ_NAME); ?></label>
            <input class="alm_element" name="meta-key" type="text" id="meta-key" value="" placeholder="<?php _e('Enter custom field key(name)', EWPQ_NAME); ?>">   
         </div> 
         <div id="meta-query-extended">
            <?php // Meta Value ?>
            <div class="inner border-top">
               <label for="meta-value" class="full"><?php _e('Value:', EWPQ_NAME); ?></label>
               <input class="alm_element" name="meta-value" type="text" id="meta-value" value="" placeholder="<?php _e('Enter custom field value', EWPQ_NAME); ?>">
            </div>    
            <?php // Meta Compare ?>           
            <div class="inner border-top">
               <label for="meta-compare" class="full"><?php _e('Operator:', EWPQ_NAME); ?></label>
               <select class="alm_element" id="meta-compare" name="meta-compare">
                  <option value="IN" selected="selected">IN</option>
                  <option value="NOT IN">NOT IN</option>
                  <option value="BETWEEN">BETWEEN</option>
                  <option value="NOT BETWEEN">NOT BETWEEN</option>
                  <option value="=">= &nbsp;&nbsp; (equals)</option>
                  <option value="!=">!= &nbsp; (does NOT equal)</option>
                  <option value=">">> &nbsp;&nbsp; (greater than)</option>
                  <option value=">=">>= &nbsp;(greater than or equal to)</option>
                  <option value="<">&lt; &nbsp;&nbsp; (less than)</option>
                  <option value="<=">&lt;= &nbsp;(less than or equal to)</option>
                  <option value="LIKE">LIKE</option>
                  <option value="NOT LIKE">NOT LIKE</option>
                  <option value="EXISTS">EXISTS</option>
                  <option value="NOT EXISTS">NOT EXISTS</option>
               </select>
            </div>            
         </div>
      </div>         
   </div>
</div>


<?php // List Authors
if($disable_dynamic_content){
   $authors = 'null';
}else{
   $authors = get_users();
}	   	
if($authors){
	echo '<div class="row checkboxes authors" id="alm-authors">';
	echo '<h3 class="heading">' . __('Author', EWPQ_NAME) . '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>' . __('Select an Author to query(by ID).', EWPQ_NAME) . '<br/>&raquo; <a href="admin.php?page=easy-query-examples#example-author">view example</a></p>';
	echo '</div>';
	echo '<div class="wrap"><div class="inner">';
	if(!$disable_dynamic_content){
	   echo '<select class="alm_element" name="author-select" id="author-select">';
		echo '<option value="" selected="selected">-- ' . __('Select Author', EWPQ_NAME) . ' --</option>';
	   foreach( $authors as $author ){
         echo '<option name="chk-'.$author->user_login.'" id="chk-'.$author->user_login.'" value="'.$author->ID.'">'.$author->display_name.'</option>';
	    }
	   echo '</select>';
   }else{
	  echo '<input type="text" class="alm_element numbers-only" name="author-select" id="author-select" placeholder="1">';
   }	   
   echo '</div></div>';
   echo '</div>';
   echo '</div>';
 }
?>    
 
<!-- Search term -->
<div class="row input search-term" id="alm-search">
   <h3 class="heading"><?php _e('Search Term', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enter a search term to query.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input name="search-term" class="alm_element" type="text" id="search-term" value="" placeholder="<?php _e('Enter search term', EWPQ_NAME); ?>">
         </div>
      </div>
   </div>
</div> 

<!-- Post Parameters -->
<div class="row input exclude" id="alm-exclude-posts">
   <h3 class="heading"><?php _e('Post Parameters', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4>Include</h4>
		 	<p><?php _e('A comma separated list of post ID\'s to include in query.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element numbers-only" name="include-posts" type="text" id="include-posts" value="" placeholder="66, 201, 421, 489">
         </div>
      </div>

      <div class="clear"></div>
      <hr/>
      <div class="section-title">
         <h4>Exclude</h4>
		 	<p><?php _e('A comma separated list of post ID\'s to exclude from query.', EWPQ_NAME); ?><br/>&raquo; <a href="admin.php?page=easy-query-examples#example-exclude">view example</a></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element numbers-only" name="exclude-posts" type="text" id="exclude-posts" value="" placeholder="199, 216, 345, 565">
         </div>
      </div>
   </div>
</div>    

<!-- Post Status -->
<div class="row input post-status" id="alm-post-status">
   <h3 class="heading"><?php _e('Post Status', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select status of the post.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">               
            <select class="alm_element" name="post-status" id="post-status">
                <option value="publish" selected="selected">Published</option>
                <option value="future">Future</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="private">Private</option>
                <option value="trash">Trash</option>
            </select>
         </div>
      </div>
   </div>
</div>
 
<!-- Ordering -->
<div class="row ordering" id="alm-order">
   <h3 class="heading"><?php _e('Ordering', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Sort posts by Order and Orderby parameters.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner half">
            <label class="full">Order:</label>
            <select class="alm_element" name="post-order" id="post-order">
                <option value="DESC" selected="selected">DESC (default)</option>
                <option value="ASC">ASC</option>
            </select>
         </div>
         <div class="inner half">
            <label class="full">Order By:</label>
            <select class="alm_element" name="post-orderby" id="post-orderby">
                <option value="date" selected="selected">Date (default)</option>
                <option value="title">Title</option>
                <option value="name">Name (slug)</option>
                <option value="menu_order">Menu Order</option>
                <option value="rand">Random</option>
                <option value="author">Author</option>
                <option value="ID">ID</option>
                <option value="comment_count">Comment Count</option>
            </select>
         </div>
      </div>
   </div>
</div>   

<!-- Offset -->
<div class="row input offset" id="alm-offset">
   <h3 class="heading"><?php _e('Offset', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Offset the initial WordPress query by <em>\'n\'</em> number of posts', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner"> 
            <input type="number" class="alm_element numbers-only" name="offset-select" id="offset-select" step="1" min="0">
         </div>
      </div>
   </div>
</div> 



   
<!-- Paging -->
<div class="row input paging" id="alm-paging">
   <h3 class="heading"><?php _e('Paging', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enable Easy Query to page the results.<br/><em>e.g. Page: <a href="javascript:void(0);">1</a> <a href="javascript:void(0);">2</a> <a href="javascript:void(0);">3</a></em>', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">	               
            <ul>
                <li>
                 <input class="alm_element" type="radio" name="enable_paging" value="true" id="enable_paging_true" checked="checked">
                 <label for="enable_paging_true"><?php _e('True', EWPQ_NAME); ?></label>
                </li>
                <li>
                 <input class="alm_element" type="radio" name="enable_paging" value="false" id="enable_paging_false">
                 <label for="enable_paging_false"><?php _e('False', EWPQ_NAME); ?></label>
                </li>
            </ul>
         </div>
      </div>
   </div>
</div> 

      
<!-- Posts Per Page -->
<div class="row input posts_per_page" id="alm-post-page">
   <h3 class="heading"><?php _e('Posts Per Page', EWPQ_NAME); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select the number of posts to load with each request.', EWPQ_NAME); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input type="number" class="alm_element numbers-only" name="display_posts-select" id="display_posts-select" step="1" min="-1" value="6">               
         </div>
      </div>
   </div>
</div>
   
      
<div class="clear"></div>  
   