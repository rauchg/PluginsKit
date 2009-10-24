<?php
/**
 * GitHub retriever class
 *
 * @author Guillermo Rauch
 * @version $Id: ForgeGitHub.class.php 22 2009-10-12 08:38:33Z rauchg@gmail.com $
 * @copyright Devthought, 28 April, 2009
 * @package mooforge
 **/

class ForgeGitHub {
	
	protected $tags = array(),
						$readme	= '',
						$yaml = '';	
	
	function initialize($url){
		$match = preg_match('#(https?://)?(www\.)?github\.com/([^/]+)/([^/]+)(/?|/tree(.*))?#i', $url, $parts);
		if (!$match || !$parts[3] || !$parts[4]){
			throw new ForgeException('GitHub url could not be parsed');
		}
		$this->user = strtolower($parts[3]);
		$this->project = strtolower($parts[4]);
		
		$this->url = sprintf('http://github.com/%s/%s/', $this->user, $this->project);
		$this->pull();
	}
	
	function pull(){
		# Tags
		$tags = $this->fetch(sprintf('http://github.com/api/v2/json/repos/show/%s/%s/tags', $this->user, $this->project));
		if ($tagsArr = @json_decode($tags))
		{
			$this->tags = array_keys((array) $tagsArr->tags);
			usort($this->tags, 'version_compare');
		} else {
			throw new ForgeException('Bad GitHub response');
		}
		
		if (empty($this->tags))
		{
			throw new ForgeException('GitHub repository has no tags. At least one tag is needed for a download.');
		}
		
		# Get commits for README.md
		$tree = '';
		$commitsList = $this->fetch(sprintf('http://github.com/api/v2/json/commits/list/%s/%s/master/README.md', $this->user, $this->project));
		if ($commitsArr = @json_decode($commitsList))
		{				
			$commits = (array) $commitsArr->commits;
			if (!empty($commits) && isset($commits[0]->tree)) $tree = $commits[0]->tree;
		} else {
			throw new ForgeException('Bad GitHub response');
		}
		
		if (empty($tree))
		{
			throw new ForgeException('README.md not found in repository root');
		}				
		
		# README.md
		$blob = $this->fetch(sprintf('http://github.com/api/v2/json/blob/show/%s/%s/%s/README.md', $this->user, $this->project, $tree));
		
		if ($blobInfo = @json_decode($blob))
		{
			$this->readme = $blobInfo->blob->data;
		}	else {
			throw new ForgeException('Bad GitHub response');
		}
		
		if (empty($this->readme))
		{
			throw new ForgeException('README.md possibly empty, or some other weirdness occured.');
		}
		
		# Get commits for package.yml
		$tree = '';
		$commitsList = $this->fetch(sprintf('http://github.com/api/v2/json/commits/list/%s/%s/master/package.yml', $this->user, $this->project));
		if ($commitsArr = @json_decode($commitsList))
		{				
			$commits = (array) $commitsArr->commits;
			if (!empty($commits) && isset($commits[0]->tree)) $tree = $commits[0]->tree;
		} else {
			throw new ForgeException('Bad GitHub response');
		}
		
		if (empty($tree))
		{
			throw new ForgeException('package.yml not found in repository root');
		}				
		
		# package.yml
		$blob = $this->fetch(sprintf('http://github.com/api/v2/json/blob/show/%s/%s/%s/package.yml', $this->user, $this->project, $tree));
		
		if ($blobInfo = @json_decode($blob))
		{
			$this->yaml = $blobInfo->blob->data;
		}	else {
			throw new ForgeException('Bad GitHub response');
		}
		
		if (empty($this->yaml))
		{
			throw new ForgeException('package.yml possibly empty, or some other weirdness occured.');
		}		
		
		// # get the files		
		// 		try {
		// 			$repo = new GitRepository(sprintf('http://github.com/%s/%s.git', $this->getUser(), $this->getProject()), );
		// 			
		// 			if ($repo->update()){
		// 				$files = sfFinder::type('file')->name('*.js')->in($repo->getDir());	
		// 				
		// 				foreach ($files as $file){
		// 					
		// 					try {
		// 						$parser = new ForgeJSParser(file_get_contents($file));
		// 					} catch (ForgeJSParserException $e){
		// 						
		// 					}
		// 										
		// 				}
		// 			}			
		// 			
		// 		} catch (GitRepositoryException $e){
		// 			throw new ForgeException($e->getMessage());
		// 		}
		// 		
		// 		
		// 		$this->filesMap = array();
		
	}
	
	function fetch($url){
		if (function_exists('curl_init')){
			$c = curl_init();
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_USERAGENT, 'curl/7.15.5 (i686-redhat-linux-gnu) libcurl/7.15.5 OpenSSL/0.9.8b zlib/1.2.3 libidn/0.6.5');
      $contents = curl_exec($c);
      curl_close($c);
			return $contents;
		} else throw new ForgeException('cURL is required');
	}
	
	function getUser(){
		return $this->user;
	}
	
	function getProject(){
		return $this->project;
	}
	
	function getUrl(){
		return $this->url;
	}
	
	function getTags(){
		return $this->tags;
	}
	
	function getReadme(){
		return $this->readme;
	}
	
	function getYaml(){
		return $this->yaml;
	}
	
}

class ForgeException extends Exception {}

class ForgeGitHubFactory {
	
	protected static $fetched = array();
	
	public static function fetch($url){
		if (!isset(self::$fetched[$url])) self::$fetched[$url] = new ForgeGitHub();
		return self::$fetched[$url];
	}
	
	public static function count(){
		return sizeof(self::$fetched);
	}
	
}