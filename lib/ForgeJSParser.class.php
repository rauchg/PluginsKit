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

		$this->rawYaml = trim($yaml[2][0]);
		
		try {
			$this->yaml = new sfYamlParser();
			$this->yaml = $this->yaml->parse($this->rawYaml);
		} catch (InvalidArgumentException $e){
			throw new ForgeJSParserException('Error parsing the YAML fragment in the JS. Make sure it\'s valid YAML.');
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