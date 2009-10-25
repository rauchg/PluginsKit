<?php use_helper('Text') ?>
<li>
	<a href="<?php echo url_for('plugin', array('slug' => $plugin->getSlug())) ?>">
		<span class="name"><?php echo highlight_text($plugin->getTitle(), isset($search) ? $search : '') ?> <?php if ($plugin->isOfficial()) echo image_tag('/images/official.gif', 'alt=Official plugin title=Official plugin') ?></span>
		<span class="downloads" title="Downloads"><?php echo (int) $plugin->getDownloadsCount() ?></span>		
		<span class="project_thumb"><?php echo thumbnail_for($plugin) ?></span>
	</a>
</li>