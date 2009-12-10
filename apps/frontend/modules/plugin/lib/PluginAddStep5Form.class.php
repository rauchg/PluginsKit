<?php

/**
 * Plugin adding step 5
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep5Form extends PluginAddStepForm
{
	
	public function configure(){
		$this->setWidgets(array(
			'author' => new sfWidgetFormInput,
			'arbitrarySections' => new sfWidgetFormInput,
			'stabletag' => new sfWidgetFormInput,
			'screenshots' => new sfWidgetFormInput,
			'category' => new sfWidgetFormInput,
			'tags' => new sfWidgetFormInput,
			'title' => new sfWidgetFormInput,
			'screenshot' => new sfWidgetFormInput,			
			'docsurl' => new sfWidgetFormInput,			
			'demourl' => new sfWidgetFormInput,			
			'howtouse' => new sfWidgetFormInput,			
			'description' => new sfWidgetFormInput
		));
		
		$catsCriteria = new Criteria();
		$catsCriteria->add(TermPeer::CATEGORY, true);
	
		$this->setValidators(array(
			'author' => sfConfig::get('app_plugin_dev_loose_mode') ? new sfValidatorPass : new sfValidatorPropelChoice(array('model' => 'Author', 'column' => 'username', 'required' => true), array('required' => 'The "author:" field in package.yml is required', 'invalid' => 'Please provide a valid username in the "author:" field of your package.yml')),
			'arbitrarySections' => new sfValidatorPass,
			'stabletag' => new sfValidatorPass,
			'screenshots' => new sfValidatorPass,
			'gitTags' => new sfValidatorPass,
			'category' => new sfValidatorPropelChoice(array('model' => 'Term', 'criteria' => $catsCriteria, 'column' => 'title', 'required' => true), array('invalid' => '"%value%" is not a valid category. Please check your package.yml.')),
			'tags' => new sfValidatorPass,
			'title' => new sfValidatorString(array('max_length' => 255, 'required' => true), array('required' => 'A plugin name is required. Check your package.yml for the \'name\' key')),
			'screenshot' => new sfValidatorUrl(array('required' => false), array('invalid' => 'The <a href="%value%">screenshot</a> in README.md is not a valid URL')),
			'docsurl' => new sfValidatorUrl(array('required' => false), array('invalid' => 'The "docs:" field in package.yml is not a valid URL')),			
			'demourl' => new sfValidatorUrl(array('required' => false), array('invalid' => 'The "demo:" field in package.yml is not a valid URL')),
			'howtouse' => new sfValidatorString(array('required' => true), array('required' => 'A "How to Use" section is required in your README.md')),
			'description' => new sfValidatorString(array('required' => true), array('required' => 'A description is required in your README.md'))
		));
		
		$c = new sfValidatorCallback(array('callback' => array($this, 'doValidate')));
		$c->addOption('execute-if-passed', true);
		$this->validatorSchema->setPostValidator($c);
	}
	
	public function doValidate($validator, $values){		
		$title = strtolower(trim($values['title']));		
		if ($title == 'core' || $title == 'more'){
			throw new sfValidatorError($validator, sprintf('The plugin names <b>core</b> and <b>more</b> are reserved.'));
		}
		
		$username = sfContext::getInstance()->getUser()->getUsername();
		if (sfConfig::get('sf_environment') != 'dev' && ($values['author'] !== $username)){
			throw new sfValidatorError($validator, sprintf('Your username "%s" and the one specified in package.yml don\'t match.', $username));
		}
		
		if (isset($values['stabletag']) && !in_array($values['stabletag'], $values['gitTags'])){
			throw new sfValidatorError($validator, sprintf('The current tag ("%s") in package.yml is not in the repository.', $values['stabletag']));
		}
		
		return $values;
	}
	
} // END class PluginAddStep5Form extends PluginAddForm