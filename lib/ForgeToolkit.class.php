<?php

class ForgeToolkit {
	
	public static function normalizeTag($tag){
		$tag = trim($tag); # trailing whitespace
		$tag = preg_replace('/\s\s+/', ' ', $tag); # excess whitespace
		$tag = strtolower($tag);
		return $tag;
	}
	
	public static function fromSlug($slug, $separator = '-'){
		$parts = explode($separator, $slug);
		return join(array_map('ucfirst', $parts), ' ');
	}
	
	public static function isUrl($string){
		return !!(@parse_url($url));
	}
	
	public static function isUrlAccessible($url){		
		$file_headers = @get_headers($url);
		return $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
	}
	
	public static function retrieveTitleFromURL($url, $returnMany = false){
		if (function_exists('curl_init')){
			$c = curl_init();
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($c, CURLOPT_URL, $url);
      $contents = curl_exec($c);
      curl_close($c);
			
			if (preg_match('/<title>([^<]+)</title>/', $contents, $matches))
			{
				if ($returnMany) return $matches;
				return $matches[0];
			}			
		} else throw new Exception('cURL is required');
	}
	
	/**
	 * Acts as `mkdir -p`
	 *
	 * @param string $path The path to create
	 * @return void
	 * @author Guillermo Rauch
	 */
	public static function createRecursiveDirectory($path){
		if (!file_exists($path)) @mkdir($path, 0777, true);
	}
	
}