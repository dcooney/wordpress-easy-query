<div class="cta postbox">
	<h3><?php _e('Read/Write Access', 'easy-query'); ?></h3>
	<div class="cta-wrap">
   	<div class="item">
   	<?php
      //Test Easy Query directory for write capabilities	
   	$alm_file =  EQ_TEMPLATE_PATH .'default.php'; // Default template   	
   	if(file_exists($alm_file)){
      	if (is_writable($alm_file)){
         	echo '<p class="access"><i class="fa fa-check"></i> ';
      	   echo __('Read/Write access is enabled within the /easy-query/core/templates/ directory.', 'easy-query');
      	   echo '</p>';
         } else{ 
            echo '<p class="access"><i class="fa fa-exclamation"></i> ';
      	   echo __('Access Denied! You must enable read and write access for the Easy Query directory (/easy-query/core/template/) in order to save template data.<br/><br/>Please contact your hosting provider or site administrator for more information.', 'easy-query');      	    
      	   echo '</p>';
         }
      }else{
         echo '<p class="access"><i class="fa fa-exclamation"></i> ';
         echo __('Unable to locate configuration file. Directory access may not be granted.', 'easy-query');    	    
         echo '</p>';         
      }   
      ?>
      </div>
	</div>
</div>