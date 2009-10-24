<?php

/**
 * Plugin adding step 1
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep1Form extends PluginAddStepForm
{
	
	protected $gitHubUser = null,
						$gitHubRepository = null;	
	
	public function configure(){
		$this->setWidgets(array(
			'url' => new sfWidgetFormInput
		));
		
		$this->setValidators(array(
			'url' => new ForgeValidatorGitHubUrl(array(), array('required' => 'Please provide an URL', 'invalid' => 'GitHub URL could not be parsed'))
		));
			
		$c = new sfValidatorCallback(array('callback' => array($this, 'doValidate')));
		$c->addOption('execute-if-passed', true);
		$this->validatorSchema->setPostValidator($c);
	}
	
	public function doValidate($validator, $values){
		preg_match(ForgeValidatorGitHubUrl::PATTERN, $values['url'], $parts);

		$url = sprintf('http://github.com/%s/%s/', $parts[3], $parts[4]);
		
		if (!ForgeToolkit::isUrlAccessible($url)){
			throw new sfValidatorError($validator, sprintf('Could not access <a href="%s">GitHub URL</a> (404)', $url));
		}
		
		$this->gitHubUser = $parts[3];
		$this->gitHubRepository = $parts[4];
		
		return $values;
	}
	
	/**
	 * GitHub user getter
	 *
	 * @return string github user name
	 * @author Guillermo Rauch
	 **/
	public function getGitHubUser()
	{
		return $this->gitHubUser;
	}
	
	/**
	 * GitHub repository getter
	 *
	 * @return string github repository name
	 * @author Guillermo Rauch
	 **/
	public function getGitHubRepository()
	{
		return $this->gitHubRepository;
	}
	
} // END class PluginAddStep1 extends PluginAddStepForm