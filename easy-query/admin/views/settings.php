<div class="admin cnkt settings" id="ewpq-settings">
	<div class="wrap">
		<div class="header-wrap">
         <h1>
            <?php echo EQ_TITLE; ?> 
            <em><?php echo EQ_TAGLINE; ?></em>
         </h1>
      </div>         
		<?php if( isset($_GET['settings-updated']) ) { ?>
          <div id="message" class="updated inline">
              <p><strong><?php _e('Your settings have been saved.', 'easy-query') ?></strong></p>
          </div>
      <?php } ?>
	   <div class="cnkt-main">
   	   
   	   <div class="postbox">
   	   	<div class="fake-sidebar">	   	
   			   <?php include_once( EQ_PATH . 'admin/includes/cta/about.php');	?>
   	   	</div>
   	   </div>
   	   
   	   <div class="postbox">
   	   	<div class="fake-sidebar">
      	   	<h3><?php _e('Easy Query Options', 'easy-query'); ?></h3>
               
      			<form action="options.php" method="post" id="ewpq_OptionsForm">
         			<div class="cta-wrap">
      				<?php 
   					   settings_fields( 'ewpq-setting-group' );
   					   do_settings_sections( 'easy-wp-query' );	
   					   //get the older values, wont work the first time
   					   $options = get_option( '_ewpq_settings' );
   					?>	
         			</div>
   					<div id="major-publishing-actions" class="ewpq-save-settings">
         			   <button class="button button-primary" type="submit"><?php _e('Save Settings', 'easy-query'); ?></button>	
         			   <div class="loading"></div>	
                     <div class="clear"></div>
                  </div>	        
      			</form>
      			<script type="text/javascript">
               jQuery(document).ready(function() {
                  jQuery('#ewpq_OptionsForm').submit(function() { 
                     jQuery('.ewpq-save-settings .loading').fadeIn();
                     jQuery(this).ajaxSubmit({
                        success: function(){
                           jQuery('.ewpq-save-settings .loading').fadeOut(250, function(){
                              window.location.reload();
                           });
                        },
                        error: function(){
                           alert("<?php _e('Sorry, settings could not be saved.', 'easy-query'); ?>");
                        }
                     }); 
                     return false; 
                  });
               });
               </script> 
   	   	</div>
   	   </div>
   	   
	   </div>
	   <div class="cnkt-sidebar">
	   	<?php include_once( EQ_PATH . 'admin/includes/cta/pro.php'); ?> 
	   	<?php include_once( EQ_PATH . 'admin/includes/cta/writeable.php'); ?>  
			<?php include_once( EQ_PATH . 'admin/includes/cta/plugins.php');	?> 
	   </div>		   	
	</div>
</div>