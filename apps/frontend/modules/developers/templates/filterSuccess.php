<?php use_helper('Text') ?>
<div class="block">	
	<h3><span>Developers</span></h3>	
	
	<hr class="clear" />
	<form action="<?php echo url_for('@developers') ?>" method="get" accept-charset="utf-8" class="horizontal-form">
		<ul>
			<li class="input_text"><?php echo $form['search']->renderLabel('Name') ?> <?php echo $form['search']->render() ?></li>	
			<li class="input_check"><?php echo $form['with_plugins']->renderLabel('With plugins') ?> <?php echo $form['with_plugins']->render() ?></li>	
			<li class="input_submit"><input type="submit" value="Filter" id="submit_filter" />
			<?php if ($params->count()): ?>
			<?php echo link_to('Clear', '@developers') ?>
			<?php endif ?>
			</li>
		</ul>
		<?php echo $form['search']->renderError() ?>
	</form>
	<hr class="clear" />
	
	<?php if ($pager->getResults()->count()): ?>
	<div class="block">
		<ul class="authors">
			<?php foreach ($pager->getResults() as $author): ?>
			<li>
				<a href="<?php echo url_for('user', array('username' => $author->getUsername())) ?>">
					<span class="avatar"><?php echo avatar_for($author) ?></span>
					<span class="name"><?php echo highlight_text($author->getFullName(), $form->getValue('search')) ?></span>
					<?php if ($author->getPluginsCount()): ?><span class="badge"><?php echo $author->getPluginsCount() ?></span><?php endif ?>
				</a>
			</li>			
			<?php endforeach ?>
		</ul>
	</div>
	
	<?php if($pager->haveToPaginate()): ?>
	<hr />
	
	<?php
	    $noprev = !$pager->getPreviousPage() || ($pager->getPreviousPage() == $pager->getPage());
	    $nonext = !$pager->getNextPage() || ($pager->getNextPage() == $pager->getPage());
	?>
  <ul class="numbers-paginator">
    <li><?php echo link_to_unless($noprev, 'Previous', 'developers', array_merge(array_filter($form->getValues()), array('page' => $pager->getPreviousPage()))); ?></li>
    <li><?php echo link_to_unless($nonext, 'Next', 'developers', array_merge(array_filter($form->getValues()), array('page' => $pager->getNextPage()))); ?></li>
  </ul>
	<?php endif ?>
	<?php else: ?>
	<p>No developers to show.</p>
	<?php endif ?>
	
</div>