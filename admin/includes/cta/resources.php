<div class="cta">
	<h3><?php _e('Help/Resources', EWPQ_NAME); ?></h3>
	<div class="cta-wrap">
   	<?php
   			
   		// Parse help/resources JSON feed on dashboard     
        	function get_resource_data($url) {
   			$ch = curl_init();
   			$timeout = 5;
   			curl_setopt($ch, CURLOPT_URL, $url);
   			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
   			$data = curl_exec($ch);
   			curl_close($ch);
   			return $data;
   		}		     
   		$resource_url = 'http://download.connekthq.com/easy-wp-query/resources.json';
   		$resource_json = json_decode(get_resource_data($resource_url));
   		
   		print "<ul>";
   		foreach($resource_json->data->resource as $resource) {
   			print '<li><a target="blank" href="'. $resource->url .'">'. $resource->title .'</a></li>';
   		}
   		print "</ul>";	
   	?>
	</div>
</div>