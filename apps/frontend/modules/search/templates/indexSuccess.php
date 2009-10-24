<?php use_helper('Search') ?>
<div class="block">
	<h3 class="red"><span>Search</span></h3>
	
	<hr class="clear" />
	<form action="<?php echo url_for('@search') ?>" method="get" accept-charset="utf-8" class="horizontal-form">
		<ul>
			<li class="input_text"><?php echo $form['q']->renderLabel('Search') ?> <?php echo $form['q']->render() ?></li>				
			<li class="input_submit"><input type="submit" value="Go" id="submit_search" /></li>
		</ul>

		<?php if ($form->hasGlobalErrors()): ?>
	  <ul class="form-global-errors error_list">
	    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
	    <li><?php echo $name.': '.$error ?></li>
	    <?php endforeach; ?>
	  </ul>
	  <?php endif; ?>
		
		<?php echo $form['q']->renderError() ?>		
	</form>
	<hr class="clear" />
		
  <?php $res = isset($pager) ? $pager->getResults() : false; ?>
  <?php if (!$res || !$res->count()): ?>
  No results to show
  <?php else: ?>
  <?php if ($sphinx->getLastWarning()): ?>
  Warning: <?php echo $sphinx->getLastWarning() ?>
  <?php endif ?>
  <ol start="<?php echo $pager->getFirstIndice() ?>" class="search_results">
  <?php foreach ($res as $item): ?>
    <li>
      <p class="title"><?php echo link_to(highlight_search_result($item->getTitle(), $query), '@plugin?slug=' . $item->getSlug()) ?></p>
      <p class="desc"><?php echo highlight_search_result($item->getDescriptionClean(), $query) ?></p>
			<p class="author">By <?php echo link_to($item->getAuthor()->getFullName(), '@user?username=' . $item->getAuthor()->getUsername()) ?></p>
    </li>
  <?php endforeach ?>
  </ol>
  <?php endif ?>

  <?php if ($res && $pager->haveToPaginate()): ?>	
	<hr />
	<ul class="numbers-paginator">	
    <li><?php echo link_to('&laquo;', '@search?q=' . $query . '&p=' . $pager->getFirstPage()) ?></li>
    <li><?php echo link_to('&lt;', '@search?q=' . $query . '&p=' . $pager->getPreviousPage()) ?></li>
    <?php $links = $pager->getLinks()?>
    <?php foreach ($links as $page): ?>
			<li>
      <?php echo ($page == $pager->getPage()) ? '<em>' . $page . '</em>' : link_to($page, '@search?q=' . $query . '&p=' . $page) ?>
			</li>
    <?php endforeach ?>
    <li><?php echo link_to('&gt;', '@search?q=' . $query . '&p=' . $pager->getNextPage()) ?></li>
    <li><?php echo link_to('&raquo;', '@search?q=' . $query . '&p=' . $pager->getLastPage()) ?></li>		
	</ul>
  <?php endif ?>
	
</div>