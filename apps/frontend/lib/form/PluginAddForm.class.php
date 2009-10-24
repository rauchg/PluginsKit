<?php

class PluginAddForm extends PluginForm
{
	
	public function __construct(BaseObject $object = null, $options = array(), $CSRFSecret = null)
	{
		parent::__construct($object, $options, false);
	}
	
	public function configure(){
		unset($this->widgetSchema);
		
		$catsCriteria = new Criteria();
		$catsCriteria->add(TermPeer::CATEGORY, true);
		
		$this->setWidgets(array('url' => new sfWidgetFormInput));		
		$this->setValidatorSchema(new ForgeValidatorSchema(array(
			'id' => new sfValidatorPropelChoice(array('model' => 'Plugin', 'column' => 'id', 'required' => false)),
			'author_id' => new sfValidatorInteger,
			'url' => new sfValidatorPass,
			'arbitrarySections' => new sfValidatorPass,		
			'username' => sfConfig::get('app_plugin_dev_loose_mode') ? new sfValidatorPass : new sfValidatorPropelChoice(array('model' => 'Author', 'column' => 'username', 'required' => true), array('required' => 'The "author:" field in package.yml is required', 'invalid' => 'Please provide a valid username in the "author:" field of your package.yml')),
			'stabletag' => new sfValidatorPass,			
			'dependencies' => new sfValidatorPass,	
			'screenshot' => new sfValidatorPass,
			'screenshots' => new sfValidatorPass,
			'category' => new sfValidatorPropelChoice(array('model' => 'Term', 'criteria' => $catsCriteria, 'column' => 'title', 'required' => true), array('invalid' => '"%value%" is not a valid category. Please check your package.yml.')),
			'tags' => new sfValidatorPass,			
			'gitTags' => new sfValidatorPass(array('required' => true), array('required' => 'No GitHub tags found. The project has to be tagged at least once.')),
			'title' => new sfValidatorString(array('max_length' => 255, 'required' => true), array('required' => 'A plugin name is required. Check your package.yml for the \'name\' key')),
			'screenshot' => new sfValidatorUrl(array('required' => false), array('invalid' => 'The screenshot in README.md ("%value%") is not a valid URL')),
			'docsurl' => new sfValidatorUrl(array('required' => false), array('invalid' => 'The "docs:" field in package.yml is not a valid URL')),			
			'demourl' => new sfValidatorUrl(array('required' => false), array('invalid' => 'The "demo:" field in package.yml is not a valid URL')),			
			'githubuser' => new sfValidatorPass,
			'githubrepo' => new sfValidatorPass,
			'howtouse' => new sfValidatorString(array('required' => true), array('required' => 'A "How to Use" section is required in your README.md')),
			'description' => new sfValidatorString(array('required' => true), array('required' => 'A description is required in your README.md')),			
		)));
		
		$this->validatorSchema->setPreValidator(new appValidatorSchemaForgeGitHub);
		$this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(				
        new sfValidatorPropelUnique(array('model' => 'Plugin', 'column' => array('title')), array('invalid' => 'A plugin with the title found in package.yml already exists in the forge.')),
        new sfValidatorPropelUnique(array('model' => 'Plugin', 'column' => array('githubuser', 'githubrepo')), array('invalid' => 'A plugin from this GitHub repository already exists in the forge.')),
				new appValidatorSchemaForgeGitHubTag,
				new appValidatorSchemaForgeDependencies,
				new appValidatorSchemaUsernameCheck
      ))
    );
		
		$this->widgetSchema->setNameFormat('github[%s]');
	}
	
	public function doSave($con = null){
		parent::doSave($con);
		
		$this->object->setCategory($this->getValue('category'));
		$this->object->save();

		$this->object->setScreenshot($this->getValue('screenshot'));		
		$this->object->setScreenshots($this->getValue('screenshots'));
		$this->object->setArbitrarySections($this->getValue('arbitrarySections'));
		$this->object->setTags($this->getValue('tags'));		
		$this->object->setGitTags($this->getValue('gitTags'), $this->getValue('stabletag'));		
	}
	
	public function bind(array $taintedValues = null, array $taintedFiles = null){
		return parent::bind(array_merge($taintedValues, array('author_id' => sfContext::getInstance()->getUser()->getId())));
	}
	
}