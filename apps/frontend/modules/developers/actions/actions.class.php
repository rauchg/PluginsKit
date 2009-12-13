<?php

/**
 * developers actions.
 *
 * @package    mooforge
 * @subpackage developers
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
class developersActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->forward('developers', 'filter');
  }
	
	
	public function executeFilter(sfWebRequest $request){
		$this->params = $request->getParameterHolder()->getAll();
		unset($this->params['module'], $this->params['action'], $this->params['page']);
		
		$this->form = new BrowseDevelopersForm();
		$this->form->bind($this->params);
		
		# Criteria
		$c = new Criteria();
		$c->addDescendingOrderByColumn(AuthorPeer::CREATED_AT);
		if ($this->form->getValue('search'))
			$c->add(AuthorPeer::FULLNAME, '%' . $this->form->getValue('search') . '%', Criteria::LIKE);			
		
		if ($this->form->getValue('with_plugins'))
		  $c->add(AuthorPeer::PLUGINS_COUNT, 1, Criteria::GREATER_EQUAL);
		
		# Pager
		$this->pager = new sfPropelPager('Author', 21);
		$this->pager->setPage($request->getParameter('page', 1));
		$this->pager->setCriteria($c);
		$this->pager->init();
	}

}
