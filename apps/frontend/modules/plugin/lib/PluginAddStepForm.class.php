<?php

/**
 * Add a plugin form
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class PluginAddStepForm extends ForgeForm
{
		
	public function __construct($defaults = array(), $options = array(), $CSRFSecret = null){
		return parent::__construct($defaults, $options, false);
	}
	
	public function fetch($url){
		if (function_exists('curl_init')){
			$c = curl_init();
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_USERAGENT, 'curl/7.15.5 (i686-redhat-linux-gnu) libcurl/7.15.5 OpenSSL/0.9.8b zlib/1.2.3 libidn/0.6.5');
      $contents = curl_exec($c);
      curl_close($c);
			return $contents;
		} else {
			throw new Exception('cURL is required');
		}
	}
	
} // END class PluginAddStepForm extends ForgeForm