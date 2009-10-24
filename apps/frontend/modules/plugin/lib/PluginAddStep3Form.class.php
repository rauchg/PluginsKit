<?php

/**
 * Plugin adding step 3
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep3Form extends PluginAddStepForm
{	
	
	protected $gitTrees = array();
	
	public function configure(){
		$this->setWidgets(array(
			'user' => new sfWidgetFormInput,
			'repository' => new sfWidgetFormInput
		));
	
		$this->setValidators(array(
			'user' => new sfValidatorString,
			'repository' => new sfValidatorString
		));
		
		$c = new sfValidatorCallback(array('callback' => array($this, 'doValidate')));
		$c->addOption('execute-if-passed', true);
		$this->validatorSchema->setPostValidator($c);
	}

	public function doValidate($validator, $values){
		$files = array('package.yml', 'README.md',  'Source/');
		$trees = array();
		
		foreach ($files as $file){
			$tree = null;
			$commitsList = $this->fetch(sprintf('http://github.com/api/v2/json/commits/list/%s/%s/master/%s', $values['user'], $values['repository'], $file));
			
			if ($commitsArr = @json_decode($commitsList))
			{				
				$commits = (array) $commitsArr->commits;
				if (!empty($commits) && isset($commits[0]->tree)) $tree = $commits[0]->tree;
			} else {
				throw new sfValidatorError($validator, 'Bad GitHub response');
			}

			if ($tree){
				$trees[$file] = $tree;
			} else {
				throw new sfValidatorError($validator, sprintf('<a href="http://github.com/%s/%s/blob/master/%s">/%s</a> not found in repository root.', $values['user'], $values['repository'], $file, rtrim($file, '/')));
			}			
		}		
		
		$this->gitTrees = $trees;
	
		return $values;
	}
	
	/**
	 * Returns the tree hashes for the latest commit of each required file.
	 *
	 * @return array Git trees
	 * @author Guillermo Rauch
	 */
	public function getGitTrees(){
		return $this->gitTrees;
	}
	
} // END class PluginAddStep2Form extends ForgeForm