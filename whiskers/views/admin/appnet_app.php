<div class="admin-forms">
<h2>App.net App</h2>
<form name="appnet_app" action="<?php echo site_url("admin/appnet_app"); ?>" method="post">
	<div class="messages">
	<p>You need to create a App.net app and add the details here to continue:</p>
	
	<ul>
		<li>Go to <a target="_blank" href="http://app.net">http://app.net</a></li>
		<li>Sign in with your App.net account if you're not already</li>
		<li>Click on your avatar in the top-right and select "My Apps".</li>
		<li>Click "Create An App".</li>
		<li>Fill in the Name field with something like "John's Whiskers"</li>
		<li>Add a website url (the current url if you'd like).</li>
		<li>Enter your callback url: <strong><?php print site_url('admin/appnet_connect'); ?></strong></li>
		<li>Save the app and copy the "Client ID" and "Client Secret" to Whiskers. You should be good to go!</li>
	</ul>
	</div>
	
	<h2>Add your App.net details:</h2>
	
	<div class="form-item">
		<label for="appnet_consumer_key" class="is-hidden">Client ID</label>
		<input name="appnet_consumer_key" type="text" value="<?php echo $old_consumer_key ?>" placeholder="Client ID">
	</div>
	
	<div class="form-item">
		<label for="appnet_consumer_secret" class="is-hidden">Client Secret</label>
		<input name="appnet_consumer_secret" type="text" value="<?php echo $old_consumer_secret ?>" placeholder="Client Secret">
	</div>
	
	<input type="submit" value="Save details" />
</form>
</div>
