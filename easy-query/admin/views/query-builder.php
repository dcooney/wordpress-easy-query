<div class="admin cnkt shortcode-builder" id="ewpq-builder">	
	<div class="wrap">
		<div class="header-wrap">
			<h1>
   			<?php echo EQ_TITLE; ?>: <strong><?php _e('Query Builder', 'easy-query'); ?></strong>
   			<em><?php _e('Create your own Easy Query <a href="http://en.support.wordpress.com/shortcodes/" target="_blank">Shortcode</a> by adjusting the parameters below.', 'easy-query'); ?>
   			</em>
			</h1> 
		</div>
		
		<h2 class="nav-tab-wrapper">
		   <a class="nav-tab nav-tab-active" title="Query Builder" href="#shortcode-builder"><?php _e('Query Builder', 'easy-query'); ?></a>
         <a class="nav-tab" id="generate-query" title="WP_Query Generator" href="#query-generator"><?php _e('WP_Query Generator', 'easy-query'); ?></a>
		</h2>
		
		<div class="spacer lg"></div>
		
		<div class="tab-content builder">	
   		<div class="cnkt-main">
   		   <div class="group postbox">
      		   <form id="easy-wp-builder-form">
      			   <?php include_once( EQ_PATH . 'admin/shortcode-builder/shortcode-builder.php');	?>
      		   </form>
   			   <div class="row no-brd">
   					<p class="back2top" style="padding-top: 15px;"><a href="#wpcontent"><i class="fa fa-angle-up"></i>&nbsp; <?php _e('Back to Top', 'easy-query'); ?></a></p>					
   			   </div>
   		   </div>
   	   </div>
   	   
   	   <!-- Sidebar -->
   	   <div class="cnkt-sidebar">
   	      <div class="table-of-contents">
      	   	<div class="cta postbox">
      	   	   <div class="cta-wrap">
      	   	      <select class="toc"></select>
      	   	   </div>
      	   	</div>
      	   	<div class="cta postbox">
      				<h3><?php _e('Shortcode Output', 'easy-query'); ?></h3>
      				<div class="cta-wrap">
         				<p><?php _e('Add the following shortcode into the content editor, template file or widget area of your theme.', 'easy-query'); ?></p>
         				<div class="output-wrap">
         					<div id="shortcode_output"></div>
         				</div>
         				<p class="small reset-shortcode-builder"><a href="javascript:void(0);" title="<?php _e('Clear all Query Builder settings', 'easy-query'); ?>"><i class="fa fa-refresh"></i> <?php _e('Reset', 'easy-query'); ?></a></p>
      				</div>
      				<div id="major-publishing-actions" class="ewpq-save-settings">
         			   <button class="button button-primary copy" type="button"><?php _e('Copy Shortcode', 'easy-query'); ?></button>	
                     <div class="clear"></div>
                  </div>
      	   	</div>
   	      </div>
   	   </div>
   	   <!-- /Sidebar -->
		</div>
		
		<div class="tab-content generator">		
		   <?php include_once( EQ_PATH . 'admin/query-generator/generator.php');	?>		   
		</div>
		
	</div>
</div>