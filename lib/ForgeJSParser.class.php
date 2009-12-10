<?php

/**
 * Moo .js files parser
 *
 * @package forge
 * @subpackage parser
 * @author Guillermo Rauch
 **/
class ForgeJSParser
{
	
	protected 
		$requires = array(),
		$provides = null;
	
	public function __construct($data){
		$this->data = $data;
		preg_match_all('#(?m)/\*\s*(^---(\s*$.*?)^\.\.\.)\s*#sm', $this->data, $yaml);

		if (!isset($yaml[2]) || !isset($yaml[2][0]) || !$yaml[2][0]){
			throw new ForgeJSParserException('Couldn\'t find required YAML header in JS file.');
		}

    // hack to support unindented lists. hell might break loose.
		$this->rawYaml = preg_replace('/$([\s]+)-/m', '$1 -', trim($yaml[2][0]));
		
		try {
			$this->yaml = new ForgeYamlParser($this->rawYaml);
			$this->yaml = $this->yaml->getData();
		} catch (InvalidArgumentException $e){
			throw new ForgeJSParserException('Error parsing the YAML fragment in the JS. Parser said: ' . $e->getMessage());
		}
	}
	
	public function getData(){
		return $this->yaml;
	}
	
	public function getRawData(){
		return $this->rawYaml;
	}
	
} // END class ForgeJSParser

class ForgeJSParserException extends Exception {}