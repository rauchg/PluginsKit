<?php

/**
 * Runs Git commands on a repository
 *
 * @package forge
 * @subpackage util
 * @author Guillermo Rauch
 **/
class GitRepository
{
	
	public function __construct($repoUrl, $basePath, $gitPath){
		$this->repoUrl = $repoUrl;
		$this->basePath = $basePath;
		$this->gitPath = $gitPath;
		$this->path = rtrim($this->basePath, '/') . '/' . sha1($this->repoUrl);
	}
	
	public function fetch(){		
		if (!file_exists($this->getPath())){
			return $this->checkout();
		}
		
		return $this->update();
	}
	
	public function getPath(){
		return $this->path;
	}
	
	public function getRepoUrl(){
		return $this->repoUrl;
	}
	
	public function checkout(){
		$command = sprintf('%s clone %s %s', $this->gitPath, $this->getRepoUrl(), $this->getPath());
		sfContext::getInstance()->getLogger()->info('{GitRepository} Executing ' . $command);
		exec(escapeshellcmd($command), $output, $return);
		if ($return !== 0){
			throw new GitRepositoryException('Git clone failed');
    }
		return $this;		
	}
	
	public function update(){
	  @rmdir($this->getPath());
		return $this->checkout();
	}
	
} // END class GitRepository

class GitRepositoryException extends Exception {}