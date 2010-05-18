<?php

/**
 * Plugin adding step 4
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep4Form extends PluginAddStepForm
{
	
	protected $gitRepositoryPath = null;
	
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
		try {
			$git = new GitRepository(sprintf('git://github.com/%s/%s.git', $values['user'], $values['repository']), sfConfig::get('app_git_storage_path'), sfConfig::get('app_git_command'));
			$git->fetch();
			
			$this->gitRepositoryPath = $git->getPath();			
		} catch (GitRepositoryException $e){
			throw new sfValidatorError($validator, 'Problems pulling/updating the repository.');
		}				
		
		return $values;
	}
	
	/**
	 * Returns the dir where the repo was cloned / pulled
	 *
	 * @return string repository path
	 * @author Guillermo Rauch
	 */
	public function getGitRepositoryPath(){
		return $this->gitRepositoryPath;
	}
	
} // END class PluginAddStep4Form extends PluginAddForm