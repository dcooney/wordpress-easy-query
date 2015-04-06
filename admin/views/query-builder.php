<div class="admin cnkt shortcode-builder" id="ewpq-builder">	
	<div class="wrap">
		<div class="header-wrap">
			<h2><?php echo EWPQ_TITLE; ?>: <strong><?php _e('Custom Query Builder', EWPQ_NAME); ?></strong></h2>
			<p><?php _e('Create your own <a href="http://en.support.wordpress.com/shortcodes/" target="_blank">Shortcode</a> and <a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">WP_Query</a> by adjusting the parameters below.', EWPQ_NAME); ?></p>  
		</div>
		
		<h2 class="nav-tab-wrapper">
		   <a class="nav-tab nav-tab-active" title="Query Builder" href="#shortcode-builder">Query Builder</a>
         <a class="nav-tab" id="generate-query" title="WP_Query Generator" href="#query-generator">WP_Query Generator</a>
		</h2>
		<div class="spacer lg"></div>
		
		<div class="tab-content">	
   		<div class="cnkt-main">
   		   <div class="group">
      		   <form id="easy-wp-builder-form">
      			   <?php include_once( EWPQ_PATH . 'admin/shortcode-builder/shortcode-builder.php');	?>
      		   </form>
   			   <div class="row no-brd">
   					<p class="back2top"><a href="#wpcontent"><i class="fa fa-chevron-up"></i> <?php _e('Back to Top', EWPQ_NAME); ?></a></p>					
   			   </div>
   		   </div>
   	   </div>
   	   <div class="cnkt-sidebar">
   	      <div class="table-of-contents">
      	   	<div class="cta">
      	   	   <div class="cta-wrap">
      	   	      <select class="toc"></select>
      	   	   </div>
      	   	</div>
      	   	<div class="cta">
      				<h3><?php _e('Shortcode Output', EWPQ_NAME); ?></h3>
      				<div class="cta-wrap">
         				<p><?php _e('Add the following shortcode into the content editor, template file or widget area of your theme.', EWPQ_NAME); ?></p>
         				<div class="output-wrap">
         					<div id="shortcode_output"></div>
         					<span class="copy"><?php _e('Copy', EWPQ_NAME); ?></span>
         				</div>
         				<p class="small reset-shortcode-builder"><a href="javascript:void(0);" title="<?php _e('Clear all Query Builder settings', EWPQ_NAME); ?>"><i class="fa fa-refresh"></i> Reset Query Builder</a></p>
      				</div>
      	   	</div>
   	      </div>
   	   </div>
		</div>
		<div class="tab-content generator">		
		   <?php include_once( EWPQ_PATH . 'admin/query-generator/generator.php');	?>		   
		</div>
	</div>
</div>