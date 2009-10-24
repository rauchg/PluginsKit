<?php

class PluginDependency extends BasePluginDependency
{
	
	public function isExternal(){
		return !!$this->getUrl();
	}
	
}
