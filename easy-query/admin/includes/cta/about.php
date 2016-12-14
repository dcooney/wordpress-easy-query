<div class="cta">
	<h3><?php _e('About the Plugin', 'easy-query'); ?></h3>
	<div class="cta-wrap config">
		<p class="lg"><?php _e('Easy Query is the fastest and simplest way to build complex WordPress queries without touching a single line of code', 'easy-query'); ?>.</p>
		<p>Create queries using the intuitive <a href="options-general.php?page=easy-query&tab=query-builder">Query Builder</a> then place the generated Shortcode directly into a template, content editor or widget area of your theme.</p>
		
		<p style="padding-top: 10px;">
   		<strong>Version</strong>: <span><?php echo EQ_VERSION; ?></span> &nbsp; 
   		<strong>Released</strong>: <span><?php echo EQ_RELEASE; ?></span>
      </p>
		
		<div class="spacer"></div>
		
		<ul class="config-nav">
   		<?php $utm = '?utm_source=WPAdmin&utm_medium=Dashboard&utm_campaign=QuickLinks'; ?>
   		<li><?php _e('Quick Links', 'easy-query'); ?>:</li>
      	<li><a href="https://connekthq.com/plugins/easy-query/<?php echo $utm; ?>" target="_blank"><?php _e('Official Website', 'easy-query'); ?></a></li>
      	<li><a href="https://connekthq.com/plugins/easy-query/docs/<?php echo $utm; ?>" target="_blank"><?php _e('Documentation', 'easy-query'); ?></a></li>
      	<li><a href="https://connekthq.com/plugins/easy-query/examples/<?php echo $utm; ?>" target="_blank"><?php _e('Examples', 'easy-query'); ?></a></li>
      	<li><a href="https://connekthq.com/plugins/easy-query/support/<?php echo $utm; ?>" target="_blank"><?php _e('Support', 'easy-query'); ?></a></li>
		</ul>
	</div>
</div>