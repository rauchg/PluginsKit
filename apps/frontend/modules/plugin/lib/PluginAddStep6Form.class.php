<?php

/**
 * Plugin adding step 6
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStep6Form extends PluginAddStepForm
{
	
	protected $dependencies = array(),
						$localDependencies = array();
	
	public function configure(){
		$this->setWidgets(array(
			'files' => new sfWidgetFormInput
		));
		
		$this->setValidators(array(
			'files' => new sfValidatorPass
		));
		
		$c = new sfValidatorCallback(array('callback' => array($this, 'doValidate')));
		$c->addOption('execute-if-passed', true);
		$this->validatorSchema->setPostValidator($c);
	}
	
	public function doValidate($validator, $values){
		foreach ((array) $values['files'] as $file){
			try {
				$parser = new ForgeJSParser(file_get_contents($file));
			} catch (ForgeJSParserException $e){
				throw new sfValidatorError($validator, $e->getMessage() . sprintf(' (%s)', basename($file)));
			}
			
			$data = $parser->getData();

			// check for *presence* of required fields
			$requiredFields = array('provides', 'authors');			
			foreach ($requiredFields as $required){
				if (!isset($data[$required])){
					throw new sfValidatorError($validator, sprintf('`%s` field missing in %s', $required, basename($file)));
				}
			}
			
			// check for well formed dependencies
			if (isset($data['requires'])){
				foreach ($data['requires'] as $requirement){
					
					if (is_array($requirement)){
						foreach ($requirement as $k => $v){
							if (!isset($this->dependencies[$k])) $this->dependencies[$k] = array();
							$this->dependencies[$k] = array_merge($this->dependencies[$k], is_array($v) ? $v : array());
						}
					} else {
						$this->localDependencies[] = $requirement;
					}					
					
				}				
			}
			
		}
	}
	
	public function getDependencies(){
		return $this->dependencies;
	}
	
	public function getLocalDependencies(){
		return $this->localDependencies;
	}
	
} // END class PluginAddStep6Form extends PluginAddStepForm