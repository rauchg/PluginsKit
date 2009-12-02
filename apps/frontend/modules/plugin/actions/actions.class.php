<?php

/**
 * plugin actions.
 *
 * @package    mooforge
 * @subpackage plugin
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 24 2009-10-16 20:53:04Z rauchg@gmail.com $
 */
class pluginActions extends ForgeActions
{
	
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->forward('@browse');
  }

	private function checkStep($num, $addid){
		$this->forward404Unless($num === $this->getUser()->getAttribute('step', null, 'plugin.add.' . $addid));
	}

	/**
	 * Add first step
	 *
	 * @author Guillermo Rauch
	 **/
	public function executeAdd(sfWebRequest $request)
	{
		$this->form = new PluginAddStep1Form();
		if ($request->isMethod('post')){
			$addid = uniqid(time() . rand(555555, 666666));
			
			if ($request->getParameter('id')){
				$plugin = PluginPeer::retrieveBySlug($request->getParameter('id'));
				$this->forward404Unless($plugin && $this->getUser()->ownsPlugin($plugin));
				
				$this->getUser()->setAttribute('step', 1, 'plugin.add.' . $addid);
				$this->getUser()->setAttribute('id', $plugin->getId(), 'plugin.add.' . $addid);				
				$this->getUser()->setAttribute('github.user', $plugin->getGithubuser(), 'plugin.add.' . $addid);
				$this->getUser()->setAttribute('github.repository', $plugin->getGithubrepo(), 'plugin.add.' . $addid);
				
				return $this->renderJson(array('success' => true, 'addid' => $addid, 'status' => 'Verifying GIT Tags'));				
			}
						
			$this->form->bind($request->getPostParameters());
			
			if ($this->form->isValid()){				
				$this->getUser()->setAttribute('step', 1, 'plugin.add.' . $addid);
				$this->getUser()->setAttribute('github.user', $this->form->getGitHubUser(), 'plugin.add.' . $addid);
				$this->getUser()->setAttribute('github.repository', $this->form->getGitHubRepository(), 'plugin.add.' . $addid);
				
				return $this->renderJson(array('success' => true, 'addid' => $addid, 'status' => 'Verifying GIT Tags'));
			}
			return $this->renderJson($this->form->toJson());
		}		
	}
	
	/**
	 * Step 2
	 *
	 * @author Guillermo Rauch
	 **/
	public function executeAdd2(sfWebRequest $request)
	{	
		if ($request->isMethod('post')){
			$addid = $request->getParameter('addid');
						
			$this->checkStep(1, $addid);
						
			$form = new PluginAddStep2Form();
			$form->bind(array(
				'id' => $this->getUser()->getAttribute('id', null, 'plugin.add.' . $addid),
				'user' => $this->getUser()->getAttribute('github.user', null, 'plugin.add.' . $addid),
				'repository' => $this->getUser()->getAttribute('github.repository', null, 'plugin.add.' . $addid)
			));			
				
			if ($form->isValid()){
				$this->getUser()->setAttribute('step', 2, 'plugin.add.' . $addid);
				$this->getUser()->setAttribute('github.tags', $form->getGitTags(), 'plugin.add.' . $addid);
				return $this->renderJson(array('success' => true, 'status' => 'Verifying Project Structure'));
			}			

			return $this->renderJson($form->toJson());		
		}
	}
	
	/**
	 * Step 3
	 *
	 * @author Guillermo Rauch
	 **/
	public function executeAdd3(sfWebRequest $request)
	{
		if ($request->isMethod('post')){
			$addid = $request->getParameter('addid');

			$this->checkStep(2, $addid);

			$form = new PluginAddStep3Form();
			$form->bind(array(
				'user' => $this->getUser()->getAttribute('github.user', null, 'plugin.add.' . $addid),
				'repository' => $this->getUser()->getAttribute('github.repository', null, 'plugin.add.' . $addid)
			));
			
			if ($form->isValid()){
				$this->getUser()->setAttribute('step', 3, 'plugin.add.' . $addid);
				$id = $this->getUser()->getAttribute('id', false, 'plugin.add.' . $addid);
				return $this->renderJson(array('success' => true, 'status' => ($id ? 'Updating' : 'Checking out') . ' repository'));			
			}
					
			return $this->renderJson($form->toJson());
		}
	}
	
	/**
	 * Step 4
	 *
	 * @author Guillermo Rauch
	 **/
	public function executeAdd4(sfWebRequest $request)
	{
		if ($request->isMethod('post')){
			$addid = $request->getParameter('addid');
			
			$this->checkStep(3, $addid);
			
			$form = new PluginAddStep4Form();
			$form->bind(array(
				'user' => $this->getUser()->getAttribute('github.user', null, 'plugin.add.' . $addid),
				'repository' => $this->getUser()->getAttribute('github.repository', null, 'plugin.add.' . $addid)				
			));
			
			if ($form->isValid()){
				$this->getUser()->setAttribute('step', 4, 'plugin.add.' . $addid);
				$this->getUser()->setAttribute('github.path', $form->getGitRepositoryPath(), 'plugin.add.' . $addid);

				return $this->renderJson(array('success' => true, 'status' => 'Verifying README.md and package.yml'));							
			}
			
			return $this->renderJson($form->toJson());
		}
	}

	/**
	 * Step 5
	 *
	 * @author Guillermo Rauch
	 **/
	public function executeAdd5(sfWebRequest $request)
	{
		if ($request->isMethod('post')){
			$addid = $request->getParameter('addid');
			
			$this->checkStep(4, $addid);
			
			$gitPath = $this->getUser()->getAttribute('github.path', '', 'plugin.add.' . $addid);
			$gitTags = $this->getUser()->getAttribute('github.tags', array(), 'plugin.add.' . $addid);
			$readme = new ForgeMDParser(file_get_contents($gitPath . '/README.md'));
			$manifest = new ForgeYamlParser(file_get_contents($gitPath . '/package.yml'));
						
			$params = array(
				'author' => $manifest->get('author'),
				'arbitrarySections' => $readme->getArbitrarySections(),
				'stabletag' => $manifest->get('current'),
				'screenshots' => $readme->getScreenshots(),
				'category' => $manifest->get('category'),
				'tags' => $manifest->get('tags'),
				'gitTags' => $gitTags,
				'title' => $manifest->get('name'),
				'screenshot' => $readme->getScreenshot(),			
				'docsurl' => $manifest->get('docs'),			
				'demourl' => $manifest->get('demo'),			
				'howtouse' => $readme->getSection('how-to-use'),			
				'description' => $readme->getDescription()
			);
						
			$form = new PluginAddStep5Form();
			$form->bind($params);
			
			if ($form->isValid()){
				$this->getUser()->setAttribute('step', 5, 'plugin.add.' . $addid);			
				$this->getUser()->setAttribute('github.params', $form->getValues(), 'plugin.add.' . $addid);			
				
				return $this->renderJson(array('success' => true, 'status' => 'Verifying JS files'));
			}
			
			return $this->renderJson($form->toJson());
		}
	}
	
	/**
	 * Completes adding
	 *
	 * @author Guillermo Rauch
	 **/
	public function executeAdd6(sfWebRequest $request)
	{
		if ($request->isMethod('post')){
			$addid = $request->getParameter('addid');
			
			$this->checkStep(5, $addid);
			
			$gitPath = $this->getUser()->getAttribute('github.path', '', 'plugin.add.' . $addid);
			$files = sfFinder::type('file')->name('*.js')->in($gitPath . '/Source');
			
			$form = new PluginAddStep6Form();
			$form->bind(array('files' => $files));
			
			if ($form->isValid()){				
				$params = $this->getUser()->getAttribute('github.params', array(), 'plugin.add.' . $addid);
				
				unset($params['author']);
				
				$params['dependencies'] = $form->getDependencies();				
				$params['githubuser'] = $this->getUser()->getAttribute('github.user', '', 'plugin.add.' . $addid);
				$params['githubrepo'] = $this->getUser()->getAttribute('github.repository', '', 'plugin.add.' . $addid);
				
				$id = $this->getUser()->getAttribute('id', false, 'plugin.add.' . $addid);			
				
				if ($id){
					$plugin = PluginPeer::retrieveByPk($id);
					
					// for propel unique validator isUpdate check
					$params['id'] = $id;
					
					$form = new PluginAddForm($plugin);
					$result = $form->bindAndSave($params);
				} else {			
					$form = new PluginAddForm();			
					$form->bindAndSave($params);	
				}
				
				if (!$form->isValid()){
					print_r($form->toJson());
					exit;
				}
				
				$this->getUser()->getAttributeHolder()->remove('plugin.add.' . $addid);

				return $this->renderJson(array(
					'success' => true, 
					'status' => 'Done!' . (!$id ? sprintf(' <a href="%s">See it here</a>', $this->getContext()->getRouting()->generate('plugin', array('slug' => $form->getObject()->getSlug()))) : '')
				));
			} else {
				return $this->renderJson($form->toJson());
			}
			
		}
	}

	public function executeView(sfWebRequest $request){		
		$this->plugin = PluginPeer::retrieveBySlug($request->getParameter('slug'));
		$this->forward404Unless($this->plugin);
		
		$this->getResponse()->setTitle(sfConfig::get('app_title_prefix') . ' ' . $this->plugin->getTitle());
		
		$c = new Criteria();
		$c->addDescendingOrderByColumn(PluginTagPeer::CREATED_AT);
		$this->tags = $this->plugin->getPluginTags($c);
		
		$this->termsTags = $this->plugin->getTermRelationships();		
		$this->screenshots = $this->plugin->getScreenshots();	
		$this->sections = $this->plugin->getPluginSections();
		$this->dependencies = $this->plugin->getPluginDependencys();
	}

  /**
   * Delete a plugin
   *
   * @author Guillermo Rauch
   **/
  public function executeDelete(sfWebRequest $request)
  {    
		$this->plugin = PluginPeer::retrieveBySlug($request->getParameter('slug'));
		$this->plugin->delete();
		
		$this->redirect('@homepage');
  }

}
