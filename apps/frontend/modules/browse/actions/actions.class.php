<?php

/**
 * browse actions.
 *
 * @package    mooforge
 * @subpackage browse
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 22 2009-10-12 08:38:33Z rauchg@gmail.com $
 */
class browseActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->forward('browse', 'filter');
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeFilter(sfWebRequest $request)
  {
		$this->params = $request->getParameterHolder()->getAll();
		unset($this->params['module'], $this->params['action'], $this->params['page']);
		
		$this->form = new BrowseForm();
		$this->form->bind($this->params);
		
		# Criteria
		$c = new Criteria();
		if ($this->form->getValue('search'))
			$c->add(PluginPeer::TITLE, '%' . $this->form->getValue('search') . '%', Criteria::LIKE);			
		
		if ($this->form->getValue('category'))
		{
			$category = TermPeer::retrieveBySlug($this->form->getValue('category'));
			if ($category){
				$c->add(PluginPeer::CATEGORY_ID, $category->getId());
			}			
		}
		
		if ($this->form->getValue('tag'))
		{	
			$term = TermPeer::retrieveBySlug($this->form->getValue('tag'));
			
			if ($term){
				$c->addJoin(PluginPeer::ID, TermRelationshipPeer::PLUGIN_ID);
				$c->add(TermRelationshipPeer::TERM_ID, $term->getId());
			} else {
				$c->add(1, 2);
			}
		}
		
		# Pager
		$this->pager = new sfPropelPager('Plugin', sfConfig::get('app_browse_per_page'));
		$this->pager->setPage($request->getParameter('page', 1));
		$this->pager->setCriteria($c);
		$this->pager->init();
  }
}
