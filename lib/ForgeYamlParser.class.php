<?php

class ForgeYamlParser extends sfYamlParser
{
	
	protected $errors = array();
	protected $data = array();
	
	public function __construct($data){
		try {
		  $version = sfYaml::getSpecVersion();
		  sfYaml::setSpecVersion('1.2');
			$this->data = $this->parse($data);	
		  sfYaml::setSpecVersion($version);		
		} catch(InvalidArgumentException $e) {
			$this->errors[] = $e->getMessage();
		}
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function get($key, $default = null){
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}
	
	public function hasErrors(){
		return !empty($errors);
	}
	
	public function getErrors(){
		return $this->errors;
	}
	
}
