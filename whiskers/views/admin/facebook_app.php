<div class="admin-forms">
<h2>Facebook App</h2>
<form name="facebook_app" action="<?php echo site_url("admin/facebook_app"); ?>" method="post">
	<div class="messages">
	<p>You need to create a Facebook app and add the details here to continue:</p>
	
	<ul>
		<li>Go to <a target="_blank" href="http://developers.facebook.com">http://developers.facebook.com</a></li>
		<li>In the top-right corner, click Apps.</li>
		<li>Allow requests for permission for Developers.</li>
		<li>Click "Create New App".</li>
		<li>Fill in the Name field with something like "John's Whiskers", ignore the other fields.</li>
		<li>Select 'Website' for how the app integrates with Facebook, and enter the Site URL for your Whiskers installation.</li>
		<li>Get the App Key and Secret given by Facebook.</li>
		<li>Fill in the details below and you should be good to go.</li>
	</ul>
	</div>
	
	<h2>Add your Facebook App details:</h2>
	
	<div class="form-item">
		<label for="facebook_app_id" class="is-hidden">App ID</label>
		<input name="facebook_app_id" type="text" value="<?php echo $old_app_id ?>" placeholder="App ID">
	</div>
	
	<div class="form-item">
		<label for="facebook_api_secret" class="is-hidden">App Secret</label>
		<input name="facebook_api_secret" type="text" value="<?php echo $old_api_secret ?>" placeholder="App Secret">
	</div>
	
	<input type="submit" value="Save details" />
</form>
</div>
