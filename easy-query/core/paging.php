<?php      
   $numposts = $eq_query->found_posts;
   $max_page = $eq_query->max_num_pages;   
   if(empty($paged) || $paged == 0) {
      $paged = 1;
   }
   $pages_to_show = 5;
   $pages_to_show_minus_1 = $pages_to_show-1;
   $half_page_start = floor($pages_to_show_minus_1/2);
   $half_page_end = ceil($pages_to_show_minus_1/2);
   $start_page = $paged - $half_page_start;
   if($start_page <= 0) {
      $start_page = 1;
   }
   $end_page = $paged + $half_page_end;
   if(($end_page - $start_page) != $pages_to_show_minus_1) {
      $end_page = $start_page + $pages_to_show_minus_1;
   }
   if($end_page > $max_page) {
      $start_page = $max_page - $pages_to_show_minus_1;
      $end_page = $max_page;
   }
   if($start_page <= 0) {
      $start_page = 1;
   }
   
   if ($max_page > 1) {
      echo '<div class="wp-easy-query-paging clearfix">';
      echo '<ul class="wp-easy-query-pages">';
      
      // First Page
      if ($start_page >= 2 && $pages_to_show < $max_page) {
         $first_page_text = "&larr;";
         //echo '<li class="first-page" rel="first"><a href="'.get_pagenum_link().'" title="'. __('First Page', 'easy-query') .'">'.$first_page_text.'</a></li>';
      }
      
      // Previous Page
      if($paged > 1 && $max_page > $pages_to_show){
         echo '<li class="num prev-page">';
         echo '<a href="'.get_pagenum_link($paged - 1).'" rel="prev" title="'. __('Previous Page', 'easy-query') .'">&larr;</a>';
         echo '</li>';
      }
      
      // ... (First Page)
		if($paged > ($pages_to_show-1) && $max_page > $pages_to_show){
			echo '<li class="num"><a href="'.get_pagenum_link(1).'">1</a></li>';
			echo '<li class="num dotdotdot">...</li>';
		}      
      
      // Paging Nums
      for($i = $start_page; $i  <= $end_page; $i++) {
         if($i == $paged) {
            echo '<li class="num current">'.$i.'</li>';
         } else {
            echo '<li class="num"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
         }
      }      
      
      // ... (Last Page)
      if($paged < ($max_page - ceil($pages_to_show/2))){
			echo '<li class="num dotdotdot">...</li>';
	      echo '<li class="num"><a href="'.get_pagenum_link($max_page).'">'.$max_page.'</a></li>';
		} 
      
      // Next Page
      if($paged < $max_page && $max_page > $pages_to_show){
         echo '<li class="num next-page">';
         echo '<a href="'.get_pagenum_link($paged + 1).'" rel="next" title="'. __('Next Page', 'easy-query') .'">&rarr;</a>';
         echo '</li>';
      }
      
      // Last Page
      if ($end_page < $max_page) {
         $last_page_text = "&rarr;";
         //echo '<li class="last-page" rel="last"><a href="'.get_pagenum_link($max_page).'" title="'. __('Last Page', 'easy-query') .'">'.$last_page_text.'</a></li>';
      }
      echo '</ul>';
      echo '</div>';      
   }