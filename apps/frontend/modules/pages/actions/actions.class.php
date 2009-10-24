<?php

/**
 * pages actions.
 *
 * @package    mooforge
 * @subpackage pages
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
class pagesActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->redirect('@homepage');
  }

	public function executeHowToAdd(){
		
	}
	
	public function executeGuidelines(){
		
	}

}
