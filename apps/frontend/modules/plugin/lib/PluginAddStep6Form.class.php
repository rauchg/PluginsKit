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
	
	protected $dependencies = array();
	
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
					throw new sfValidatorError($validator, sprintf('`%s` field missing or empty in %s', $required, basename($file)));
				}
			}
			
			// check for well formed dependencies
			if (isset($data['requires'])){
				foreach ($data['requires'] as $a => $b){
					
					if (is_string($b) && preg_match('/([^:]+):([^\/]*)\/(.+)/', $b, $match)){
					  $pluginName = $match[0];
					  $version = $match[1];
					  $b = $match[2];
					} else {
  					if (strstr($a, '/') || strstr($a, ':')){
  						$pieces = explode(strstr($a, '/') ? '/' : ':', $a);
  						$pluginName = $pieces[0];
  						$version = $pieces[1];						
  					} else {
  						throw new sfValidatorError($validator, sprintf('Dependency "%s" is invalid. The format should be <b>plugin-uid</b>/<b>release</b>: [<b>provided-component</b>, ...]', $a . ': ' . $b ));
  					}
					}
					
					
					$plugin = PluginPeer::retrieveBySlug($pluginName);
					
					if (!is_array($b)) $b = array($b);
					
					foreach ($b as $dep){
						if ($plugin){
							$c = new Criteria();
							$c->add(PluginTagPeer::PLUGIN_ID, $plugin);
							$plugintag = PluginTagPeer::retrieveByName($dep, $c);
							if ($plugintag){
								$plugin_tag_id = $plugintag->getId();
							} else {
								$plugin_tag_id = null;
							}
						} else {
							$plugin_tag_id = null;
						}
						
						$this->dependencies[] = array('scope' => $pluginName, 'version' => $version, 'component' => $dep, 'plugin_tag_id' => $plugin_tag_id);
					}
												
				}				
			}
			
		}
	}
	
	public function getDependencies(){
		return $this->dependencies;
	}
	
} // END class PluginAddStep6Form extends PluginAddStepForm