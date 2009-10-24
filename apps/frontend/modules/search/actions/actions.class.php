<?php

/**
 * search actions.
 *
 * @package    mooforge
 * @subpackage search
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
class searchActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->form = new SearchForm();
		$this->form->bind(array('q' => $request->getParameter('q')));
		if ($this->form->isValid()){		
			$this->query = $this->form->getValue('q');
      $this->page = $request->getParameter('p', 1);
      $options = array(
        'limit'   => 10,
        'offset'  => ($this->page - 1) * 10,
        'weights' => array(100, 1),
        'sort'    => sfSphinxClient::SPH_SORT_EXTENDED,
        'sortby'  => '@weight DESC',
      );
      if (!empty($this->query)){
        $this->sphinx = new sfSphinxClient($options);
        $res = $this->sphinx->Query($this->query, 'mooforge');
        $this->pager = new sfSphinxPager('Plugin', $options['limit'], $this->sphinx);
        $this->pager->setPage($this->page);
        $this->pager->setPeerMethod('retrieveByPKs');
        $this->pager->init();
        $this->logMessage('Sphinx search "' . $this->query . '" [' . $res['time'] .
                          's] found ' . $this->pager->getNbResults() . ' matches');
      }
	
		}
  }
}
