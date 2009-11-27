<div class="block">	
	<h3><span>Browse</span></h3>	
	
	<hr class="clear" />
	<form action="<?php echo url_for('@browse') ?>" method="get" accept-charset="utf-8" class="horizontal-form">
		<ul>
			<li class="input_text"><?php echo $form['search']->renderLabel('Name') ?> <?php echo $form['search']->render() ?></li>				
			<!-- <li class="input_select"><?php echo $form['active']->renderLabel('Last active') ?> <?php echo $form['active']->render() ?></li>	 -->
			<li class="input_check"><?php echo $form['official']->renderLabel('Official') ?> <?php echo $form['official']->render() ?></li>	
			<li class="input_select"><?php echo $form['category']->renderLabel('Category') ?> <?php echo $form['category']->render() ?></li>								
			<li class="input_submit"><input type="submit" value="Filter" id="submit_filter" />
			<?php if ($params->count() && !$form->getValue('tag')): ?>
			<?php echo link_to('Clear', '@browse') ?>
			<?php endif ?>
			</li>
			
			<?php if ($form->getValue('tag')): ?>
	 		<li><br />Filtering by tag: <strong><?php echo $form->getValue('tag') ?></strong>. <?php echo link_to('Clear', '@browse') ?></li>
			<?php endif ?>
		</ul>
		<?php echo $form['tag']->render(); ?>
		
		<?php if ($form->hasGlobalErrors()): ?>
	  <ul class="form-global-errors error_list">
	    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
	    <li><?php echo $name.': '.$error ?></li>
	    <?php endforeach; ?>
	  </ul>
	  <?php endif; ?>
		
		<?php echo $form['search']->renderError() ?>		
	</form>
	<hr class="clear" />
	
	<?php if ($pager->getResults()->count()): ?>
	<div class="block">
		<ul class="projects">
			<?php foreach ($pager->getResults() as $i => $plugin): ?>
			<?php include_partial('plugin/bit', array('plugin' => $plugin, 'i' => $i, 'search' => $form->getValue('search'))) ?>
			<?php endforeach ?>
		</ul>
	</div>
	
	<?php if($pager->haveToPaginate()): ?>
	<hr />
	
	<ul class="numbers-paginator">
	  <?php foreach ($pager->getLinks() as $page): ?>
	    <li>
	    <?php if ($page == $pager->getPage()): ?>
	      <em><?php echo $page ?></em>
	    <?php else: ?>
	      <?php echo link_to($page, 'browse', array_merge(array_filter($form->getValues()), array('page' => $page))); ?>
	    <?php endif; ?>
	    </li>
	  <?php endforeach; ?>
	</ul>  
	<?php endif ?>
	<?php else: ?>
	<p>No projects to show.</p>
	<?php endif ?>
	
</div>