<form id="post" class="posting-main" action="<?php echo site_url() ?>" method="post" data-endpoint="<?php echo $base_url ?>api/post">
	
	<div id="drivers" class="app-lists">	
		<?php if ($valid_drivers) : ?>
		<ul>		
			<?php foreach ($valid_drivers as $driver => $obj) : ?>
			<li class="driver">
            	            	
            	<div class="row field-shim">
            		<div class="service-icons <?php echo $driver ?>-icon"></div>
            		
            		<span class="edit-status">unedited &rsaquo;</span>
            		<p class="posting driver-text"></p>
            		
            		<div class="service-edit is-hidden">
            			<div class="post-utilities">
            				<p><span><?php echo ucwords($driver); ?>- Whiskers App</span></p>
            				<div class="btn remove"><span>Remove</span></div>
            			</div>
            		
            			<textarea id="<?php echo $driver ?>_text" class="driver-text service-textarea" name="<?php echo $driver ?>_status" data-driver="<?php echo $driver ?>"></textarea>
            			
            			<div class="post-utilities">
            				<p class="count">0 Chars</p>
            				<div class="btn close"><span>Done</span></div>
            			</div>
            		</div>
            		
            		            		
            	</div>
            	
            	</li>
            	<?php endforeach; ?>
		</ul>
		<?php else: ?>
		<p class="messages">You have to add an account before you can post. Visit the admin page to <a href="<?php echo site_url("/admin") ?>">manage your accounts</a>.</p>
		<?php endif; ?>
	</div>

	<div class="post-utilities">
		<div class="btn"><?php $att_current = (strstr(current_url(), 'history')) ? '' : NULL; echo anchor('history', 'Cancel', $att_current); ?></div>

		<p class="count">0 Chars</p>
		
		<input id="post-form-submit" name="op" type="submit" value="Post" />		
	</div>
	
	<div class="all-services">
		<h2>enter message</h2>
		<input id="post-form-submit" name="op" type="submit" value="Post" />
		<textarea id="text" name="text" class="all-drivers_text"></textarea>
		<p class="count">0 Chars</p>
	</div>
</form>