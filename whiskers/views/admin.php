<div class="admin-forms add-services">
	<h2>accounts</h2>
	<?php //print_r($available_drivers); ?>
	<form id="add_service" class="row" action="<?php echo site_url("admin/account_connect"); ?>" method="post">
		<p>Add an account for</p>
		
		<select name="add[driver]">
		<?php if (isset($available_drivers)) : foreach ($available_drivers as $driver) : ?>
			<option value="<?php echo $driver ?>"><?php echo ucwords($driver) ?></option>
		<?php endforeach; endif; ?>
		</select>
		
		<input type="submit" value="Add" />
	</form>

	<p>Depending on the account, you'll be asked for a user name and password, or sent to the account's website to sign in and authorize <em>Whiskers App</em>.</p>
	<?php //print_r($valid_drivers); //valid = running ?>

	<h2>current accounts</h2>
	<?php if ($valid_drivers) : ?>
	<ul class="accounts-list">
	<?php foreach ($valid_drivers as $driver => $obj): ?>
		<?php if('twitter' === $driver) : ?>
		<li class="row">
			<div>
				<h3><?php echo ucwords($driver) ?></h3>
				<a href="http://twitter.com/<?php echo $obj->access_token->screen_name ?>"><?php echo $obj->access_token->screen_name ?></a>
			</div>
		<?php endif; ?>
		
		<?php if('facebook' === $driver) : ?>
		<li class="row">
		     <div>
				<h3><?php echo ucwords($driver) ?></h3>
				<a href="<?php echo $obj->user->link ?>"><?php echo $obj->user->name ?></a>
			</div>
		<?php endif; ?>
		
		<?php if('appnet' === $driver) : ?>
		<li class="row">
			<div>
				<h3><?php echo ucwords($driver) ?></h3>
				<a href="https://alpha.app.net/<?php echo $obj->user->nickname ?>"><?php echo $obj->user->name ?></a>
			</div>
		<?php endif; ?>
	
			<form id="remove-service" action="<?php echo site_url('admin'); ?>" method="post">
				<input type="hidden" name="rm[driver]" value="<?php echo $driver; ?>" />
				<input id="remove_service_submit" name="rm[op]" type="submit" value="Remove" />
			</form>
		</li>
<?php endforeach; ?>
	</ul>
<?php else: ?>
	<p>None.</p>
<?php endif; ?>
</div>