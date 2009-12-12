<?php

class PluginScreenshot extends BasePluginScreenshot
{
	
	public function isPrimary(){
		return $this->getPrimary();
	}
	
	public function save(PropelPDO $con = null){
		$storeScreenshot = in_array(PluginScreenshotPeer::URL, $this->modifiedColumns);
		
		parent::save($con);
		
		if ($storeScreenshot) $this->storeScreenshot();
	}
	
	public function getFilename(){
		return md5($this->getUrl()) . '.png';
	}
	
	public function storeScreenshot(){
		$path = sfConfig::get('sf_upload_dir') . '/' . sfConfig::get('app_screenshots_path') . '/' . $this->getPluginId() . '/' . $this->getId();
		ForgeToolkit::createRecursiveDirectory($path . '/thumbs/');				
		$filename = $this->getFilename();
				
		try {
			$tmp = tempnam(sys_get_temp_dir(), uniqid($this->getUrl() . time()));
			@copy($this->getUrl(), $tmp);
			
			if (@file_get_contents($tmp)){
				$image = new sfImage($tmp);
				$image->saveAs($path . '/' . $filename);
				$image->thumbnail(sfConfig::get('app_screenshots_' . ($this->isPrimary() ? 'primary' : '') . 'width'), sfConfig::get('app_screenshots_' . ($this->isPrimary() ? 'primary' : '') . 'height'), 'center');
				$image->saveAs($path . '/thumbs/' . $filename, 'image/png');				
			}
		} catch (sfImageTransformException $e){
			$this->delete();
		}		
	}
	
}
