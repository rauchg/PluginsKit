<?php

class PluginTag extends BasePluginTag
{
	
	public function getDownloadLink($type = 'zipball'){
		$plugin = $this->getPlugin();
		return sprintf("http://github.com/%s/%s/%s/%s", $plugin->getGithubuser(), $plugin->getGithubrepo(), $type, $this->getName());
	}
	
	public function sumDownload($save = true){
		$this->setDownloadsCount($this->getDownloadsCount() + 1);
		if ($save) $this->save();
	}
	
}
