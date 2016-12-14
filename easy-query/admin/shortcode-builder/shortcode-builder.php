<span class="toggle-all"><span class="inner-wrap"><em class="collapse"><?php _e('Collapse All', 'easy-query'); ?></em><em class="expand"><?php _e('Expand All', 'easy-query'); ?></em></span></span>

<?php 
   $alm_options = get_option( 'ewpq_settings' );         
   if(!isset($alm_options['_ewpq_disable_dynamic'])) // Check if '_ewpq_disable_dynamic is set within settings
	   $alm_options['_ewpq_disable_dynamic'] = '0';		
	   
	$disable_dynamic_content = $alm_options['_ewpq_disable_dynamic'];   
?>
   

<!-- Container Type -->
<div class="row checkbox container_type" id="eq-options">
   <h3 class="heading"><?php _e('Options', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4><?php _e('Type', 'easy-query'); ?></h4>
		 	<p><?php _e('Select the container type that will wrap your Easy Query content.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">	               
            <ul>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="ul" id="ul" checked="checked">
                 <label for="ul"><?php _e('&lt;ul&gt;<span>&lt;!-- posts --&gt;</span>&lt;/ul&gt;', 'easy-query'); ?></label>
                </li>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="ol" id="ol">
                 <label for="ol"><?php _e('&lt;ol&gt;<span>&lt;!-- posts --&gt;</span>&lt;/ol&gt;', 'easy-query'); ?></label>
                </li>
                <li class="full">
                 <input class="alm_element" type="radio" name="container_type" value="div" id="div">
                 <label for="div"><?php _e('&lt;div&gt;<span>&lt;!-- posts --&gt;</span>&lt;/div&gt;', 'easy-query'); ?></label>
                </li>
            </ul>
         </div>
      </div>
      
      <div class="clear"></div>
      <hr/>
      <div class="section-title">
         <h4><?php _e('Classes', 'easy-query'); ?></h4>
		 	<p><?php _e('Target your content by adding custom classes to the container.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input class="alm_element" name="classes" type="text" id="classes" value="" placeholder="blog-listing listing content etc.">
         </div>
      </div>
   </div>
</div>
<!-- End Container Type -->

<!-- Paging -->
<div class="row checkbox container_type" id="eq-pagng">
   <h3 class="heading"><?php _e('Paging', 'easy-query'); ?></h3>
   <div class="expand-wrap">    
      <div class="section-title">
         <h4><?php _e('Enable', 'easy-query'); ?></h4>
         <p><?php _e('Allow Easy Query to page content.<br/><em>e.g. Page: <a href="javascript:void(0);">1</a> <a href="javascript:void(0);">2</a> <a href="javascript:void(0);">3</a></em>', 'easy-query'); ?></p>
		</div>
      <div class="wrap paging">
         <div class="inner">	               
            <ul>
                <li>
                 <input class="alm_element" type="radio" name="enable_paging" value="true" id="enable_paging_true" checked="checked">
                 <label for="enable_paging_true"><?php _e('True', 'easy-query'); ?></label>
                </li>
                <li>
                 <input class="alm_element" type="radio" name="enable_paging" value="false" id="enable_paging_false">
                 <label for="enable_paging_false"><?php _e('False', 'easy-query'); ?></label>
                </li>
            </ul>
         </div>
      </div>  
      
		<div class="call-out">
   		<i class="fa fa-unlock"></i> <?php _e('Unlock pagination styles with', 'easy-query'); ?> <a target="_parent" href="https://connekthq.com/plugins/easy-query/"><?php _e('Easy Query Pro', 'easy-query'); ?></a>
      </div>
          
   </div>
</div>  
<!-- End Paging -->

<!-- Template -->
<div class="row input template" id="eq-template">
   <h3 class="heading"><?php _e('Template', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select the Easy Query <a href="options-general.php?page=easy-query&tab=templates" target="_parent">template</a> you would like to use.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
	         <select id="template-select" class="alm_element" disabled="disabled">
	            <option value="default" selected="selected">Default</option>
	         </select>
	      </div>
      </div>
   <?php               
   // Go Pro!
   if (!has_action('ewpq_display_templates')){
      echo '<div class="row no-brd">';
      include( EQ_PATH . 'admin/includes/cta/extend.php');
      echo '</div>';                  
   }
   ?>
   </div>
</div>
<!-- End Templates -->
      
<!-- Posts Per Page -->
<div class="row input posts_per_page" id="alm-post-page">
   <h3 class="heading"><?php _e('Posts Per Page', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select the number of posts to load with each request.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input type="number" class="alm_element numbers-only" name="display_posts-select" id="display_posts-select" step="1" min="-1" value="6">               
         </div>
      </div>
   </div>
</div>
<!-- End Posts Per Page -->


<!-- Post Types -->
<?php
// List registered post_types
$pt_args = array(
   'public'   => true
);
$types = get_post_types($pt_args);
if($types){
	echo '<div class="row checkboxes post_types" id="alm-post-types">';   		
	echo '<h3 class="heading">'.__('Post Types', 'easy-query'). '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>'.__('Select Post Types to query.', 'easy-query'). '</p>';
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
?>
<!-- End Post Types -->

<!-- Categories -->
<?php
if($disable_dynamic_content){
   $cats = 'null';
}else{
   $cats = get_categories();
}
if($cats){ ?>		
<div class="row checkboxes categories" id="alm-categories">
   <h3 class="heading"><?php _e('Category', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4>Include</h4>
         <p><?php _e('A comma separated list of categories to include by id. (8, 15, 22 etc...)', 'easy-query'); ?><br/>
         &raquo; <a href="options-general.php?page=easy-query&tab=examples#example-category">
         <?php _e('view example', 'easy-query'); ?></a></p>
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
         <h4><?php _e('Exclude', 'easy-query'); ?></h4>
         <p><?php _e('A comma separated list of categories to exclude by ID. (3, 12, 35 etc..)', 'easy-query'); ?></p>
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
<!-- End Categories -->


<!-- Tags -->
<?php }
 
 // Tags	    
if($disable_dynamic_content){
   $tags = 'null';
}else{
   $tags = get_tags();
}
if($tags){ ?>
<div class="row checkboxes tags" id="alm-tags">
	<h3 class="heading"><?php _e('Tag', 'easy-query'); ?></h3>
	<div class="expand-wrap">
		<div class="section-title">
		<h4><?php _e('Include', 'easy-query'); ?></h4>
		<p><?php _e('A comma separated list of tags to include by id. (199, 231, 80 etc...)', 'easy-query'); ?><br/>&raquo; <a href="options-general.php?page=easy-query&tab=examples#example-tag"><?php _e('view example', 'easy-query'); ?></a></p>
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
         <h4><?php _e('Exclude', 'easy-query'); ?></h4>
         <p><?php _e('A comma separated list of tags to exclude by ID. (30, 12, 99 etc..)', 'easy-query'); ?></p>
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
<!-- End Tags -->
 
 
<!-- Taxonomy -->
<div class="row taxonomy" id="alm-taxonomy">
	<h3 class="heading"><?php _e('Taxonomy', 'easy-query'); ?></h3>
	<div class="expand-wrap">
		<div class="call-out">
   		<i class="fa fa-unlock"></i> <?php _e('Unlock taxonomy queries with', 'easy-query'); ?> <a target="_parent" href="https://connekthq.com/plugins/easy-query/"><?php _e('Easy Query Pro', 'easy-query'); ?></a>
      </div>
	</div>	
</div>
<!-- End Taxonomy -->
 

<!-- Date -->
<div class="row input date" id="alm-date">
   <h3 class="heading"><?php _e('Date', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enter a year, month(number) and day to query by date archive.<br/>&raquo; <a href="options-general.php?page=easy-query&tab=exampless#example-date">view example</a>', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <div class="wrap-30">
               <?php $today = getdate(); ?>
               <label for="input-year" class="full"><?php _e('Year:', 'easy-query'); ?></label>
               <input name="input-year" class="alm_element sm numbers-only" type="text" id="input-year" maxlength="4" placeholder="<?php echo $today['year']; ?>">
            </div>
            <div class="wrap-30">
               <label for="input-month" class="full"><?php _e('Month:', 'easy-query'); ?></label>
               <input name="input-month" class="alm_element sm numbers-only" type="text" id="input-month" maxlength="2" placeholder="<?php echo $today['mon']; ?>">
            </div>
            <div class="wrap-30">
               <label for="input-day" class="full"><?php _e('Day:', 'easy-query'); ?></label>
               <input name="input-day" class="alm_element sm numbers-only" type="text" id="input-day" maxlength="2" placeholder="<?php echo $today['mday']; ?>">
            </div>
         </div>
      </div>
   </div>
</div>
   
     
<!-- End Custom Fields -->
<div class="row custom-fields" id="alm-custom-fields">
	<h3 class="heading"><?php _e('Custom Fields (Meta Query)', 'easy-query'); ?></h3>
	<div class="expand-wrap">
		<div class="call-out">
   		<i class="fa fa-unlock"></i> <?php _e('Unlock custom field queries with', 'easy-query'); ?> <a target="_parent" href="https://connekthq.com/plugins/easy-query/"><?php _e('Easy Query Pro', 'easy-query'); ?></a>
      </div>
	</div>	
</div>
<!-- End Custom Fields -->


<?php // List Authors
if($disable_dynamic_content){
   $authors = 'null';
}else{
   $authors = get_users();
}	   	
if($authors){
	echo '<div class="row checkboxes authors" id="alm-authors">';
	echo '<h3 class="heading">' . __('Author', 'easy-query') . '</h3>';
	echo '<div class="expand-wrap">';
	echo '<div class="section-title">';
	echo '<p>' . __('Select an Author to query(by ID).', 'easy-query') . '<br/>&raquo; <a href="options-general.php?page=easy-query&tab=examples#example-author">view example</a></p>';
	echo '</div>';
	echo '<div class="wrap"><div class="inner">';
	if(!$disable_dynamic_content){
	   echo '<select class="alm_element" name="author-select" id="author-select">';
		echo '<option value="" selected="selected">-- ' . __('Select Author', 'easy-query') . ' --</option>';
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

<!-- Custom Arguments -->
<div class="row input custom-arguments" id="alm-custom-args">
   <h3 class="heading"><?php _e('Custom Arguments', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('A semicolon separated list of custom value:pair arguments.<br/><br/>e.g. tag_slug__and:design,development; event_display:upcoming. Default', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input name="custom-args" class="alm_element" type="text" id="custom-args" value="" placeholder="<?php _e('event_display:upcoming', 'easy-query'); ?>">
         </div>
      </div>
   </div>
</div> 
 
<!-- Search term -->
<div class="row input search-term" id="alm-search">
   <h3 class="heading"><?php _e('Search Term', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Enter a search term to query.', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner">
            <input name="search-term" class="alm_element" type="text" id="search-term" value="" placeholder="<?php _e('Enter search term', 'easy-query'); ?>">
         </div>
      </div>
   </div>
</div> 

<!-- Post Parameters -->
<div class="row input exclude" id="alm-exclude-posts">
   <h3 class="heading"><?php _e('Post Parameters', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
         <h4>Include</h4>
		 	<p><?php _e('A comma separated list of post ID\'s to include in query.', 'easy-query'); ?></p>
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
		 	<p><?php _e('A comma separated list of post ID\'s to exclude from query.', 'easy-query'); ?><br/>&raquo; <a href="options-general.php?page=easy-query&tab=examples#example-exclude"><?php _e('view example', 'easy-query'); ?></a></p>
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
   <h3 class="heading"><?php _e('Post Status', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Select status of the post.', 'easy-query'); ?></p>
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
   <h3 class="heading"><?php _e('Ordering', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Sort posts by Order and Orderby parameters.', 'easy-query'); ?></p>
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
   <h3 class="heading"><?php _e('Offset', 'easy-query'); ?></h3>
   <div class="expand-wrap">
      <div class="section-title">
		 	<p><?php _e('Offset the initial WordPress query by <em>\'n\'</em> number of posts', 'easy-query'); ?></p>
		 </div>
      <div class="wrap">
         <div class="inner"> 
            <input type="number" class="alm_element numbers-only" name="offset-select" id="offset-select" value="0" step="1" min="0">
         </div>
      </div>
   </div>
</div>   
      
<div class="clear"></div>    