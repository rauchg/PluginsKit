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
	
	public function configure(){
		$this->setWidgets(array(
			'files' => new sfWidgetFormInput
		));
		
		$this->setValidators(array(
			'files' => new sfValidatorPass
		));
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
			$requiredFields = array('provides');			
			foreach ($requiredFields as $required){
				if (!isset($data[$required])){
					throw new sfValidatorError($validator, sprintf('`%s` field missing in %s', $required, basename($file)))
				}
			}
			
			// check for well formed dependencies
			if (isset($data['requires'])){
				foreach ($data['requires'] as $requirement){
					if (!preg_match('#([^/]+)/([^ ]+) ([^\n]+)#i', $requirement)){
						
					}
				}
				
			}
		}
	}
	
} // END class PluginAddStep6Form extends PluginAddStepForm