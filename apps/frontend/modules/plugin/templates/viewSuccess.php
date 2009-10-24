<?php use_helper('Text') ?>
<?php $raw = $sf_data->getRaw('plugin'); ?>

<div class="block" id="project">
	<h2 class="green"><span><?php echo $plugin->getTitle() ?> <em class="version"><?php echo $plugin->getStableTag()->getName() ?></em></span></h2>
	
	<div class="block" id="project-desc">
		<?php if ($plugin->getScreenshot()): ?><p id="thumb"><a href="<?php echo url_for_screenshot($plugin->getScreenshot()) ?>" class="remooz"><span class="project_thumb"><?php echo thumbnail_for($plugin) ?></span></a></p><?php endif; ?>
		<?php echo $raw->getDescription(); ?>
	</div>
	
	<hr />
	
	<div id="plugin-links" class="link-boxes block">
		<?php if ($plugin->getDocsUrl()): ?>
		<a href="<?php echo $plugin->getDocsUrl() ?>">Docs</a>
		<?php endif; ?>
		<?php if ($plugin->getDemourl()): ?>
		<a href="<?php echo $plugin->getDemourl() ?>">Demo</a>
		<?php endif; ?>
		<?php echo link_to('Download', 'download', array('project' => $plugin->getSlug(), 'tag' => $plugin->getStableTag()->getName())) ?>
		<?php if ($sf_user->isAuthenticated() && $sf_user->ownsPlugin($plugin)): ?>
		<?php echo link_to('Update', '@pluginupdate?slug=' . $plugin->getSlug(), array('id' => 'plugin-update')) ?>
		<?php endif ?>
	</div>
	
	<hr />
	
	<div class="block span-8 colborder">
		<h3 class="blue"><span>Details</span></h3>

		<dl class="table">
			<dt>Author</dt>
			<dd><?php echo link_to($plugin->getAuthor()->getFullname(), 'user', array('username' => $plugin->getAuthor()->getUsername())) ?></dd>
			
			<?php if ($plugin->getStableTag()): ?>
			<dt>Current version</dt>
			<dd><?php echo $plugin->getStableTag()->getName() ?></dd>
			<?php endif ?>
			
			<dt>GitHub</dt>
			<?php $github = sprintf('%s/%s', $plugin->getGithubuser(), $plugin->getGithubrepo()); ?>
			<dd><a href="http://github.com/<?php echo $github; ?>/"><?php echo $github ?></a></dd>
			
			<dt>Downloads</dt>
			<dd><?php echo $plugin->getDownloadsCount() ?></dd>
			
			<?php if ($plugin->getCategory()): ?>
			<dt>Category</dt>
			<dd><?php echo link_to($plugin->getCategory()->getTitle(), 'browse', array('category' => $plugin->getCategory()->getSlug())) ?></dd>			
			<?php endif ?>
			
			<?php if ($termsTags->count() > 0): ?>
			<dt>Tags</dt>
			<dd id="plugin-tags">
				<ul class="tags-list">
					<?php foreach ($termsTags as $term): ?>
					<?php $term = $term->getTerm(); ?>
					<li><?php echo link_to($term, 'browse', array('tag' => $term->getSlug())) ?></li>
					<?php endforeach ?>
				</ul>
			</dd>
			<?php endif ?>
			
			<dt>Report</dt>
			<dd><a href="http://github.com/<?php echo $github; ?>/issues">GitHub Issues</a></dd>
			
			<?php /*
			<dt>Discuss</dt>
			<dd><?php echo link_to(sprintf('Comments (%s)', $plugin->getCommentsCount()), '@plugindiscuss?slug=' . $plugin->getSlug()) ?></dd>
			*/ ?>			
		</dl>
	</div>

	<div class="block span-8 last">
		<h3 class="blue"><span>Releases</span></h3>
		<ul class="versions-list">
			<?php foreach($tags as $tag): ?>
				<li><?php echo link_to($tag->getName(), 'download', array('project' => $plugin->getSlug(), 'tag' => $tag->getName())) ?></li>
			<?php endforeach ?>								
		</ul>		
		
		<?php if ($dependencies->count()): ?>
		<hr />
		
		<h3 class="blue"><span>Dependencies</span></h3>
		
		<ul>
			<?php foreach ($dependencies as $dep): ?>
			<li>
				<?php if ($dep->isExternal()): ?>
				<a href="<?php echo $dep->getUrl() ?>"><?php echo truncate_text($dep->getTitle(), 0, 20) ?></a>
				<?php else: ?>
				<?php $foreignTag = $dep->getPluginTag(); ?>
				<?php echo link_to($foreignTag->getPlugin()->getTitle(), '@plugin?slug=' . $foreignTag->getPlugin()->getSlug()) ?> &mdash; <?php echo link_to($foreignTag->getName(), 'download', array('project' => $foreignTag->getPlugin()->getSlug(), 'tag' => $foreignTag->getName())) ?>
				<?php endif ?>
			</li>
			<?php endforeach ?>
		</ul>
		<?php endif ?>
		
	</div>
	
	<hr class="clear" />
	
	<div class="block section">
		<h3 class="blue"><span>How to use</span></h3>
		
		<?php echo $raw->getHowtouse(); ?>
	</div>	
	
	<?php if ($sections->count()): ?>
	<?php foreach ($sections as $section): ?>
	<hr />	
	<div class="block section">
		<h3 class="blue"><span><?php echo $section->getTitle(); ?></span></h3>		
		<?php echo $section->getRawValue()->getContent(); ?>
	</div>
	<?php endforeach ?>
	<?php endif ?>
	
	<?php if ($screenshots->count()): ?>
	<hr />	
	
	<div class="block section">
		<h3 class="blue"><span>Screenshots</span></h3>

		<ul class="screenshots-list">
			<?php foreach ($screenshots as $screenshot): ?>
			<li><a href="<?php echo url_for_screenshot($screenshot) ?>" title="<?php echo $screenshot->getTitle() ?>" class="remooz"><?php echo thumbnail_for($screenshot) ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>	
	<?php endif ?>
</div>