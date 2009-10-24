<?php

/**
 * Plugin adding step 2
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep2Form extends PluginAddStepForm
{	
	
	protected $gitTags = array();
	
	public function configure(){
		$this->setWidgets(array(
			'id' => new sfWidgetFormInputHidden,
			'user' => new sfWidgetFormInput,
			'repository' => new sfWidgetFormInput
		));
	
		$this->setValidators(array(
			'id' => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
			'user' => new sfValidatorString,
			'repository' => new sfValidatorString
		));
		
		$c = new sfValidatorAnd(array(
			new sfValidatorPropelUnique(array(
				'model' => 'Plugin', 
				'column' => array('githubuser', 'githubrepo'),
				'field' => array('user', 'repository')
			), array('invalid' => 'This plugin is already in the forge!')),
      new sfValidatorCallback(array('callback' => array($this, 'doValidate')))
		));
		$c->addOption('execute-if-passed', true);
		$this->validatorSchema->setPostValidator($c);
	}

	public function doValidate($validator, $values){
		$tags = $this->fetch(sprintf('http://github.com/api/v2/json/repos/show/%s/%s/tags', $values['user'], $values['repository']));
		
		if ($tagsArr = @json_decode($tags))
		{
			$this->gitTags = array_keys((array) $tagsArr->tags);
			usort($this->gitTags, 'version_compare');
		} else {
			throw new sfValidatorError($validator, 'Bad GitHub response. Try again later.');
		}
		
		if (empty($this->gitTags))
		{
			throw new sfValidatorError($validator, 'GitHub repository has no tags. At least one tag is required.');
		}
	
		return $values;
	}
	
	/**
	 * Returns the Git tags
	 *
	 * @return array git tags
	 * @author Guillermo Rauch
	 **/
	function getGitTags()
	{
		return $this->gitTags;
	}
	
} // END class PluginAddStep2Form extends ForgeForm