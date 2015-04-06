<div class="admin cnkt settings" id="ewpq-settings">
	<div class="wrap">
		<div class="header-wrap">
         <h2><?php echo EWPQ_TITLE; ?></h2>
         <p><?php echo EWPQ_TAGLINE; ?></p>
      </div>         
		<?php if( isset($_GET['settings-updated']) ) { ?>
          <div id="message" class="updated inline">
              <p><strong><?php _e('Your settings have been saved.') ?></strong></p>
          </div>
      <?php } ?>
	   <div class="cnkt-main">
	   	<div class="group fake-sidebar">	   	
			   <?php include_once( EWPQ_PATH . 'admin/includes/cta/config.php');	?>
	   	</div>
	   	<div class="group">
   			<form action="options.php" method="post" id="ewpq_OptionsForm">
   				<?php 
   					settings_fields( 'ewpq-setting-group' );
   					do_settings_sections( 'easy-wp-query' );	
   					//get the older values, wont work the first time
   					$options = get_option( '_ewpq_settings' ); ?>	
   					<div class="row no-brd ewpq-save-settings">	       
   		            <?php submit_button('Save Settings'); ?>
                     <div class="loading"></div>	
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
                        alert("<?php _e('Sorry, settings could not be saved.', EWPQ_NAME); ?>");
                     }
                  }); 
                  return false; 
               });
            });
            </script> 	
	   	</div>
	   </div>
	   <div class="cnkt-sidebar">
			<?php include_once( EWPQ_PATH . 'admin/includes/cta/resources.php');	?>
	   	<?php include_once( EWPQ_PATH . 'admin/includes/cta/writeable.php'); ?>  
			<?php include_once( EWPQ_PATH . 'admin/includes/cta/about.php');	?> 
	   </div>		   	
	</div>
</div>