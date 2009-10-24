<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<meta name="author" content="Valerio Proietti, mad4milk" />
	<meta name="copyright" content="copyright 2006 www.mad4milk.net" />
	<meta name="description" content="mootools, a super lightweight web2.0 javascript framework" />
	<meta name="keywords" content="mootools,moo.fx,javascript effects,javascript framework,ajax framework,moo.ajax" />
	<meta name="robots" content="all" />

	<!--

               ____        _                      __  __      __   __
   ___   __   / __'\     _\'\      __   ___   __ /\_\/\ \    /\ \ / /  web 2.0 beta
  /\  \_/ '\ /\ \Z\ \   / __ \    /__\ /\  \_/ '\\/_/\ \ \   \ \ \ /__
  \ \  __/\ \\ \  __ \ /\ \Z\ \  / \Z\\\ \  __/\ \  __\ \ \___\ \  _ '\
   \ \_\ \ \_\\ \_\ \ \\ \_____\/\____ \\ \_\ \ \_\/\ \\ \____\\ \_\ \_\
    \/_/  \/_/ \/_/\/_/ \/_____/\/___/\_\\/_/  \/_/\ \_\\/____/ \/_/\/_/
                                     \/_/           \/_/       be happy.

	-->
	
	<!-- Shortcut Icons -->
	
	<link href="/images/icon.png" rel="shortcut icon" type="image/x-icon" />
	<link href="/images/ipod-icon.png" rel="apple-touch-icon" />
	
	<!-- BluePrint -->
	
	<link href="/css/screen.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/css/print.css" rel="stylesheet" type="text/css" media="print" />
	
	<!-- StyleSheets -->
	
	<link href="/css/layout.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/css/main.css" rel="stylesheet" type="text/css" media="screen" />
	
	<!-- Section Specific -->
	
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'atom1'), array('absolute' => true)) ?>" />
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'rss201'), array('absolute' => true)) ?>" />	
	
	<link rel="alternate" type="text/xml" title="RSS .91 &mdash; Plugins" href="<?php echo url_for('recentfeed', array('format' => 'rss091'), array('absolute' => true)) ?>" />	
	
	<?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>	
</head>
<body>
	
	<div id="header">	
		<div class="container">
			<a href="http://mediatemple.net" id="mediatemple"><span>in partnership with mediatemple</span></a>

			<div id="logo">
				<h1><a href="<?php echo url_for('@homepage') ?>"><span>MooTools</span></a></h1>
				<h2><span>a compact javascript framework</span></h2>
			</div>

			<div id="navigation">
				<a href="/" class="first">Home</a>
				<a href="#">Download</a>
				<a href="#">Docs</a>
				<a href="#">Plugins</a>
				<a href="#">Blog</a>
				<a href="#">Demos</a>
			</div>		
		</div>	
	</div>	

	<div id="wrapper">
		<div id="container" class="container forge">
			<div id="main" class="span-18 colborder">
				<?php if ($sf_user->hasFlash('notice')): ?>
			  <div class="notice"><?php echo $sf_user->getFlash('notice') ?></div>
				<?php endif; ?>
				
				<?php echo $sf_content ?>		
			</div>
			
			<!-- Sidebar -->
			<div id="sidebar" class="span-5 last">
				<?php include_component('default', 'sidebar') ?>
			</div>
			
		</div>	
	</div>

	<div id="footer">
		<div class="container">
			<p class="copy"><a href="http://mad4milk.net" id="mucca"></a></p>
			<p>copyright &copy;2006-2009 <a href="http://mad4milk.net">Valerio Proietti</a></p>
		</div>
	</div>

</body>
</html>