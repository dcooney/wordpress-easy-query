<div class="cta">
	<h3><?php _e('Read/Write Access', EWPQ_NAME); ?></h3>
	<div class="cta-wrap">
   	<div class="item">
   	<?php
      //Test server for write capabilities	
   	$alm_file =  EWPQ_TEMPLATE_PATH .'default.php'; // Default template   	
   	if(file_exists($alm_file)){
      	if (is_writable($alm_file))
      	    echo __('<p><i class="fa fa-check"></i><strong>Easy Query</strong></p><p class="desc">Read/Write access is enabled within the /easy-query/core/templates/ directory.', EWPQ_NAME);
      	else
      	    echo __('<p><i class="fa fa-exclamation"></i><strong>Easy Query</strong></p>Access Denied! You must enable read and write access for the Easy Query directory (/easy-query/core/template/) in order to save template data.<br/><br/>Please contact your hosting provider or site administrator for more information.', EWPQ_NAME);
      }else{
         echo __('<p><i class="fa fa-exclamation"></i><strong>Easy Query</strong></p><p class="desc">Unable to locate configuration file. Directory access may not be granted.', EWPQ_NAME);
      }   
      ?>
      </div>
	</div>
</div>