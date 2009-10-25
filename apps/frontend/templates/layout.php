<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="robots" content="all" />
	
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
			<div id="logo">
				<h1><a href="<?php echo url_for('@homepage') ?>"><span>PluginsKit</span></a></h1>
				<h2><span>a plugins repository</span></h2>
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
			<hr />
			<p>Powered by PluginsKit by <a href="http://devthought.com">Guillermo Rauch</a></p>
		</div>
	</div>

</body>
</html>