<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<meta name="MobileOptimized" content="width">
	<meta name="HandheldFriendly" content="true">
	<meta http-equiv="cleartype" content="on">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,500,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo $base_url ?>theme/css/skeleton/base.css">
	<link rel="stylesheet" href="<?php echo $base_url ?>theme/css/style.css<?php echo '?' . time(); ?>">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="<?php echo $base_url ?>theme/js/scripts.js<?php echo '?' . time(); ?>"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body class="row wrapper">
	<header class="row branding is-hidden">
		<h1><?php echo anchor('post', 'Whiskers'); ?></h1>
		<?php if ($authenticated): ?>
		<div class="btn" id="user"><?php echo anchor('logout', 'sign out'); ?></div>
		<?php elseif (!($authenticated)): ?>
		<p>Welcome. <span>Please sign in</span></p>
		<?php endif; ?>		
	</header>

	<div class="row content-wrap">
		<div class="content curl">
		<article>
		<?php if (!empty($messages)): ?>
			<?php foreach ($messages as $class_type => $class_msgs) : foreach ($class_msgs as $message) : ?>
			<div class="flash messages <?php print $class_type; ?>"><?php print $message; ?></div>
			<?php endforeach; endforeach; ?>				
		<?php endif; ?>
		
		<?php echo $content ?>		
		</article>
		</div>
		
		<div id="sidebar">
			<nav id="primary">
				<ul>
					<li>
					<?php $att_current = (strstr(current_url(), 'post')) ? 'class="active"' : NULL;
					echo anchor('post', 'Post', $att_current); ?>
					</li>
					<li>
					<?php $att_current = (strstr(current_url(), 'history')) ? 'class="active"' : NULL;
					echo anchor('history', 'History', $att_current); ?>
					</li>
					<li>
					<?php $att_current = (strstr(current_url(), 'admin')) ? 'class="active"' : NULL;
					echo anchor('admin', 'Admin', $att_current); ?>
					</li>
				</ul>
			</nav>
		</div>	
	</div>
	
	<footer class="row is-hidden">
		<p>&copy; 2013 Larry Halff</p>
	</footer>		
</body>
<?php if (isset($scripts)) : ?>
<?php foreach ($scripts as $name) : ?>
<script src="<?php echo $base_url ?>theme/js/<?php echo $name ?>.js<?php echo '?' . time(); ?>"></script>
<?php endforeach; ?>
<?php endif; ?>
</html>