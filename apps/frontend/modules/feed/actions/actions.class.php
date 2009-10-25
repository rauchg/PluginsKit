<?php

/**
 * feed actions.
 *
 * @package    mooforge
 * @subpackage feed
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
class feedActions extends sfActions
{

	private function getFeed($format = 'atom1'){
		$feed = sfFeedPeer::newInstance($format);

	  $feed->setTitle('PluginsKit - Latest');
	  $feed->setLink('@homepage');

	  $feedImage = new sfFeedImage();
		$feedImage->setLink('@homepage');
	  $feed->setImage($feedImage);
	
		return $feed;
	}

	public function executeRecent(sfWebRequest $request){
		$feed = $this->getFeed($request->getParameter('format'));

	  $c = new Criteria;
	  $c->addDescendingOrderByColumn(PluginPeer::CREATED_AT);
	  $c->setLimit(20);
	  $plugins = PluginPeer::doSelect($c);
		
	  foreach ((array) $plugins as $plugin)
	  {
	    $item = new sfFeedItem();
	    $item->setTitle($plugin->getTitle());
	    $item->setLink('@plugin?slug=' . $plugin->getSlug());
	    $item->setAuthorName($plugin->getAuthor()->getFullName());
	    $item->setAuthorEmail($plugin->getAuthor()->getEmail());
	    $item->setPubdate($plugin->getCreatedAt('U'));
	    $item->setUniqueId($plugin->getSlug());
	    $item->setContent($plugin->getDescription());

	    $feed->addItem($item);
	  }

	  $this->feed = $feed;
	}

}
