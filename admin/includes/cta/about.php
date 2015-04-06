<div class="cta padding-bottom">
	<h3>Other Projects</h3>
	<div class="cta-wrap">
   	<?php
   	// Parse JSON feed on dashboard
   	function get_about_data($url) {
   		$ch = curl_init();
   		$timeout = 5;
   		curl_setopt($ch, CURLOPT_URL, $url);
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
   		$data = curl_exec($ch);
   		curl_close($ch);
   		return $data;
   	}		     
   	$about_url = 'http://download.connekthq.com/easy-wp-query/projects.json';
   	$about_json = json_decode(get_about_data($about_url));
   	print "<ul>";
   	foreach($about_json->data->links as $item) {
   		print '<li><strong><a target="blank" href="'. $item->url .'">'. $item->title .'</a></strong><br>'. $item->description .'</li>';
   	}
   	print "</ul>";
   	?>	
   	</div>
	<a href="http://twitter.com/kaptonkaos" target="blank" class="visit"><i class="fa fa-twitter"></i> Follow on Twitter</a>
</div>

