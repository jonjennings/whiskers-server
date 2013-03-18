<div class="posting-history">
	<div class="post-utilities">
		<div class="field-shim">
			<p><?php $att_current = (strstr(current_url(), 'post')) ? '' : NULL; echo anchor('post', 'Write a new post', $att_current); ?></p>			
		</div>		
	</div>
	<div class="app-lists">
<?php
		if (count($posts) >= 1) {
			echo '<ul>';
			foreach ($posts as $key => $val) {		// For each authoritative post 
				echo '<li>';
				if (is_object($val)) {
					echo '<div class="row field-shim">';  // Display the authoritative post
						echo $val->text;
						echo "<p class='date'>".(('' != $val->time) ? date('ga, F j', $val->time) : NULL)."</p>";
					echo '</div>';
					echo '<div class="row field-shim">';
					foreach ($service_posts[$key] as $post) {	// For each service post within that authoritative post
						foreach ($post->source_urls as $source => $permalink) { // For each url inside that service post (of which there will be only 1, but this is a quick way to get the service name & url)
							echo "<div class='service-icons $source-icon'></div>";
							echo "<p class='posting'><a href='$permalink' target='_blank'>{$post->text}</a></p>";
						}
					} // end 'for each service post'
					echo '</div>';
				} else {
					var_dump($key);
				} // end 'if valid post'
				echo '</li>';
			} // end 'for each authoritative post'
			echo '</ul>';
		} // end 'if we've got some posts'
?>
	</div>
</div>