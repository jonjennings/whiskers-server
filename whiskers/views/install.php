<div class="admin-forms">
	<h2>Installing Whiskers</h2>
	<ul class="install messages">
	<?php foreach ($lines as $line) : ?>
		<li class="check"><?php echo $line ?></li>
	<?php endforeach; ?>
	</ul>

	<h2>Success! Now let's create your account.</h2>

	<form action="<?php echo $base_url."install/signup"; ?>" method="post">
		<div class="form-item">
    			<input type="text" name="username" placeholder="Your username">
    		</div>
    		<div class="form-item">
    			<input type="password" name="password" placeholder="Password">
    		</div>
    	
    		<p><input type="submit" value="Create account"></p>
	</form>
</div>