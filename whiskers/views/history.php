<div class="posting-history">
	<div class="post-utilities">
		<div class="field-shim">
			<p><?php $att_current = (strstr(current_url(), 'post')) ? '' : NULL; echo anchor('post', 'Write a new post', $att_current); ?></p>			
		</div>		
	</div>

	<div class="app-lists">
		<?php if (count($posts) > 1): ?>
		<ul>
			<?php foreach ($posts as $key => $val): ?>
			<li>
				<?php if (is_object($val)) : ?>
				<div class="row field-shim">
					<?php foreach ($val->source_urls as $source => $permalink) : ?>
					<div class="service-icons <?php echo $source ?>-icon"></div>
					<?php endforeach; ?>
					
					<p class="posting"><a href="<?php echo $permalink ?>" target="_blank"><?php echo $val->text ?></a></p>
					<p class="date"><?php $val->time != '' ? print date('ga, F j', $val->time) : NULL ?></p>
				<?php else: ?>
					<?php var_dump($key); ?>
				</div>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>