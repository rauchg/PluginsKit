<div class="block" id="user-profile">	
	<h3><span><?php echo $author->getFullName() ?></span></h3>
	
	<span class="avatar"><?php echo avatar_for($author) ?></span>
	
	<h4>Details</h4>
	
	<dl class="user-details">
		<?php if ($author->getTwitterId()): ?>
		<dt>Twitter</dt>
		<dd><a href="http://twitter.com/<?php echo $author->getTwitterId() ?>">http://twitter.com/<?php echo $author->getTwitterId() ?></a></dd>
		<?php endif; ?>
		
		<?php if ($author->getHomepageurl()): ?>
		<dt>Homepage</dt>
		<dd><a href="<?php echo $author->getHomepageurl() ?>"><?php echo $author->getHomepageurl() ?></a></dd>
		<?php endif; ?>
		
		<?php if ($author->getLocation()): ?>
		<dt>Location</dt>
		<dd><?php echo $author->getLocation() ?></dd>
		<?php endif; ?>
		
		<?php if ($author->getAbout()): ?>
		<dt>About</dt>
		<dd><?php echo nl2br($author->getAbout()) ?></dd>
		<?php endif; ?>
	</dl>
	<hr />
	
	<h4>Plugins</h4>
	
	<?php if ($author->getPluginsCount()): ?>
	<div class="block">	
		<p>
			<?php if ($sf_user->isAuthenticated() && $sf_user->getId() == $author->getId()): ?>
			You have
			<?php else: ?>
			This user has
			<?php endif; ?>
			 <strong><?php echo $author->getPluginsCount() ?></strong> plugin<?php if($author->getPluginsCount() > 1) echo 's'; ?></p>
	</div>
	
	<ul class="projects">
		<?php foreach ($plugins as $plugin): ?>
		<?php include_partial('plugin/bit', array('plugin' => $plugin)) ?>
		<?php endforeach ?>
	</ul>
	<?php else: ?>
	<p>This user has no plugins</p>
	<?php endif; ?>
	
</div>