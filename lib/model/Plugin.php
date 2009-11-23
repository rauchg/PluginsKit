<?php

class Plugin extends BasePlugin
{
	
	public function isOfficial(){
		return $this->getOfficial();
	}
	
	public function getStableTag(){
		if (!isset($this->stable_tag))
		{
			$c = new Criteria();
			$c->add(PluginTagPeer::PLUGIN_ID, $this->getId());
			$c->add(PluginTagPeer::CURRENT, true);
			$this->stable_tag = PluginTagPeer::doSelectOne($c);						
		}
		
		return $this->stable_tag;
	}
	
	public function setDescription($html){
		parent::setDescription($html);
		$this->setDescriptionClean(strip_tags($html));
	}
	
	public function getGitUrl(){
		return sprintf('http://github.com/%s/%s/', $this->getGithubuser(), $this->getGithubrepo());
	}
	
	public function addTerm($term_name, $category = false){
		if (is_numeric($term_name)){
			$term = TermPeer::retrieveByPk($term_name);
		} else {
			$slug = sfPropelActAsSluggableBehaviorUtils::stripText(ForgeToolkit::normalizeTag($term_name)); 
			$term = TermPeer::retrieveBySlug($slug);	
		}
		if (!$term)
		{
			$term = new Term();
			$term->setTitle(ForgeToolkit::normalizeTag($term_name));
			$term->setCategory(false);
			$term->save();			
		}
		try {
			$rel = new TermRelationship();
			$rel->setTerm($term);
			$rel->setPlugin($this);
			$rel->save();	
		} catch (PropelException $e) {
			// avoid duplicates
		}
	}
	
	public function setTags($tags, $deletePrior = true){
		if ($deletePrior)
		{
			$c = new Criteria();
			$c->add(TermRelationshipPeer::PLUGIN_ID, $this->getId());
			TermRelationshipPeer::doDelete($c);
		}
		if ($tags){
			foreach ($tags as $tag){
				$this->addTerm($tag);
			} 
		}
		
	}
	
	public function getGitTagByName($name){
		$c = new Criteria();
		$c->add(PluginTagPeer::PLUGIN_ID, $this->getId());
		$c->add(PluginTagPeer::NAME, $name);
		return PluginTagPeer::doSelectOne($c);
	}
	
	public function sumDownload($save = true){
		$this->setDownloadsCount($this->getDownloadsCount() + 1);
		if ($save) $this->save();
	}
	
	public function setGitTags($tags, $stable = null){
		// delete all current tags which are obsolete
		$currentTags = array();
		foreach ($this->getPluginTags() as $gitTag){
			$currentTags[] = $gitTag->getName();
		}
		
		$deleteTags = array_diff($currentTags, $tags);
		foreach ($deleteTags as $tag){
			$criteria = new Criteria();
			$criteria->add(PluginPeer::PLUGIN_ID, $this->getId());
			$criteria->add(PluginPeer::NAME, $tag);
			
			PluginTagPeer::doDelete($c);
		}
		
		foreach ($tags as $i => $tag){
			if (!trim($tag)) continue;
			
			$existent = $this->getGitTagByName($tag);
			
			// if it was marked as stable and it's not the currently stable one, unmark it
			if ($existent && $existent->isCurrent() && $stable && $existent->getName() !== $stable){
				$current->setCurrent(false);
				$current->save();
			}
			if (!$existent){
				$t = new PluginTag();
				$t->setPluginId($this->getId());
				$t->setName($tag);
				if (($stable === null && ($i + 1 == sizeof($tags))) || ($tag == $stable)){
					$t->setCurrent(true);
				}
				$t->save();
				
				if ($t->isCurrent()){
					$this->stable_tag = $t;
				}
			}
		}
	}
	
	public function getCategory(){
		return $this->getTerm();
	}
	
	public function setCategory($name){
		$this->setTerm(TermPeer::retrieveByTitle($name));
		$this->save();
	}
	
	public function setDependencies($dependencies, $deletePrior = true){
		if ($deletePrior){
			$c = new Criteria();
			$c->add(PluginDependencyPeer::PLUGIN_ID, $this->getId());
			PluginDependencyPeer::doDelete($c);	
		}
		
		foreach ($dependencies as $dep){
			$obj = new PluginDependency();
			$obj->fromArray($dep, BasePeer::TYPE_FIELDNAME);
			$obj->setPluginId($this->getId());
			$obj->save();
		}

		return $this;
	}
	
	public function setArbitrarySections($sections, $deletePrior = true){
		if ($deletePrior){
			$c = new Criteria();
			$c->add(PluginSectionPeer::PLUGIN_ID, $this->getId());
			PluginSectionPeer::doDelete($c);	
		}
		
		foreach ($sections as $slug => $content){
			$s = new PluginSection();
			$s->setTitle(ForgeToolkit::fromSlug($slug));
			$s->setContent($content);
			$s->setPlugin($this);
			$s->save();
		}		
	}
	
	public function setScreenshot($url, $deletePrior = true){
		if ($deletePrior){
			$c = new Criteria();
			$c->add(PluginScreenshotPeer::PLUGIN_ID, $this->getId());
			$c->add(PluginScreenshotPeer::PRIMARY, true);
			PluginScreenshotPeer::doDelete($c);	
		}
		
		if ($url){
			$s = new PluginScreenshot();
			$s->setPlugin($this);
			$s->setUrl($url);
			$s->setPrimary(true);
			$s->save();
		}
	}
	
	public function getScreenshot(){
		$c = new Criteria();
		$c->add(PluginScreenshotPeer::PLUGIN_ID, $this->getId());
		$c->add(PluginScreenshotPeer::PRIMARY, true);
		return PluginScreenshotPeer::doSelectOne($c);
	}
	
	public function setScreenshots($screens, $deletePrior = true){
		if ($deletePrior){
			$c = new Criteria();
			$c->add(PluginScreenshotPeer::PLUGIN_ID, $this->getId());
			$c->add(PluginScreenshotPeer::PRIMARY, false);
			PluginScreenshotPeer::doDelete($c);	
		}
		
		foreach ($screens as $url => $alt)
		{
			if ($url){
				$s = new PluginScreenshot();
				$s->setPlugin($this);
				$s->setUrl($url);
				$s->setTitle($alt);
				$s->save();	
			}
		}
	}
	
	// like getPluginScreenshots but excluding primary
	public function getScreenshots(){
		$c = new Criteria();
		$c->add(PluginScreenshotPeer::PRIMARY, false);
		
		return $this->getPluginScreenshots($c);
	}
	
	public function getDownloadsCount(){
		return (int) parent::getDownloadsCount();
	}
	
	public function getCommentsCount(){
		return (int) parent::getCommentsCount();
	}
	
	public function save(PropelPDO $con = null){
		$isNew = $this->isNew();
		$ret = parent::save($con);
		if ($isNew)
		{
			$author = $this->getAuthor();
			if ($author)
			{
				$author->setPluginsCount(intval($author->getPluginsCount()) + 1);
				$author->save();
			}
		}
		return $ret;
	}
	
}

$columns_map = array('from'   => PluginPeer::TITLE,
                     'to'     => PluginPeer::SLUG);

sfPropelBehavior::add('Plugin', array('sfPropelActAsSluggableBehavior' => array('columns' => $columns_map, 'separator' => '_', 'permanent' => true)));