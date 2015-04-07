<?php   
   $numposts = $eq_query->found_posts;
   $max_page = $eq_query->max_num_pages;   
   if(empty($paged) || $paged == 0) {
      $paged = 1;
   }
   $pages_to_show = 8;
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
      echo '<li class="before">'.__('Pages:', EWPQ_NAME).'</li>';
      if ($start_page >= 2 && $pages_to_show < $max_page) {
         $first_page_text = "&laquo;";
         echo '<li><a href="'.get_pagenum_link().'">'.$first_page_text.'</a></li>';
      }
      //previous_posts_link('&lt;');
      for($i = $start_page; $i  <= $end_page; $i++) {
      if($i == $paged) {
         echo ' <li class="current">'.$i.'</li> ';
      } else {
         echo ' <li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
      }
   }
   //next_posts_link('&gt;');
   if ($end_page < $max_page) {
   $last_page_text = "&raquo;";
   echo '<li><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
   }
   echo '</ul>';
   echo '</div>';
   }
