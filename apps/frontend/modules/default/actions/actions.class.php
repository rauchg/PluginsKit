<?php

/**
 * default actions.
 *
 * @package    mooforge
 * @subpackage default
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		# Recently added
		$c = new Criteria();
		$c->addDescendingOrderByColumn(PluginPeer::CREATED_AT);
		$c->setLimit(3);		
		$this->recent = PluginPeer::doSelect($c);
		
		# Most downloaded
		$c = new Criteria();
		$c->addDescendingOrderByColumn(PluginPeer::DOWNLOADS_COUNT);
		$c->setLimit(3);		
		$this->hot = PluginPeer::doSelect($c);
	
		# Tags
		$c = new Criteria();
		$c->addDescendingOrderByColumn(TermPeer::COUNT);
		$c->setLimit(10);
		$this->terms = TermPeer::retrieveTags($c);
		
		# Authors
		$c = new Criteria();
		$c->addDescendingOrderByColumn(AuthorPeer::LOGGED_AT);
		$c->setLimit(6);
		// if ($this->getUser()->isAuthenticated())
		// 	$c->add(AuthorPeer::ID, $this->getUser()->getId(), Criteria::NOT_EQUAL);
		$this->authors = AuthorPeer::doSelect($c);
  }

	public function executeSecure(){
		
	}

	public function executeDownload(sfWebRequest $request){
		$project = PluginPeer::retrieveBySlug($request->getParameter('project'));		
		$this->forward404Unless($project);
		$tag = $project->getGitTagByName($request->getParameter('tag'));
		$this->forward404Unless($tag);
	
		$project->sumDownload();
		$tag->sumDownload();
		
		header('Location: ' . $tag->getDownloadLink());
		exit;
	}
	
	public function executeError404(){
		
	}

}
