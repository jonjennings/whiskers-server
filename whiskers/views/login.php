<form id="post" class="admin-forms" action="<?php echo $action_url ?>" method="post">
	<div class="form-item">
		<label for="username" class="is-hidden">Username</label>
		<input type="text" name="login[username]" value="" placeholder="Your username" id="username">
	</div>
	<div class="form-item">
		<label for="password" class="is-hidden">Password</label>
		<input type="password" name="login[password]" value="" placeholder="Password" id="password">
	</div>
	<input id="post-form-submit" name="op" type="submit" value="Sign in" />
</form>