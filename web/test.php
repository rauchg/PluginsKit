<?php

// exec('cd /Users/willy/Sites/Personal/MooForge/git/133e9b9b0617820f673562f71f614253cc51d397; /opt/local/bin/git pull', $output, $result);
// 
// echo $result;

require_once(dirname(__FILE__) . '/../lib/vendor/symfony/lib/yaml/sfYamlParser.class.php');

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
		
		if (!isset($yaml[1]) || !$yaml[1][0]){
			throw new ForgeJSParserException('Could not find required YAML header in JS file.');
		}
		
		$this->rawYaml = preg_replace('/$([\s]+)-/m', '$1 -', trim($yaml[2][0]));
		
		try {
			$this->yaml = new sfYamlParser();
			$this->yaml = $this->yaml->parse($this->rawYaml);
		} catch (InvalidArgumentException $e){
			throw new ForgeJSParserException('Error parsing the YAML fragment in the JS. Make sure it\'s valid YAML.');
		}
		
		print_r($this->yaml);
	}
	
	public function getData(){
		return $this->yaml;
	}
	
	public function getRawData(){
		return $this->rawYaml;
	}
	
} // END class ForgeJSParser

class ForgeJSParserException extends Exception {}

new ForgeJSParser('/*
--- 
provides: 
- MerryGoRound
license: MIT-style
requires: 
 core/1.2.3: 
  - Class.Extras
  - Element.Event
  - Element.Style
  - Element.Dimensions
  - Fx.Tween
  - String
  - Array
description: A fully-automated, flexible, customizable carousel class for Mootools.
authors: 
- 3n
...
*/
sad
asd
asd');