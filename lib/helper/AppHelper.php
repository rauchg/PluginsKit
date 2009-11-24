<?php

function avatar_for($author){
	if ($author->getAvatar()){
		return image_tag($author->getAvatar());
	} else {
		return gravatar_image_tag($author->getEmail());
	}
}

function thumbnail_for($object){
	if ($object instanceOf sfOutputEscaperObjectDecorator)
		$object = $object->getRawValue();
		
	if ($object instanceOf Plugin){
		if ($object->getScreenshot()) return thumbnail_image_tag($object->getScreenshot());		
		return '';		
	} else if ($object instanceOf PluginScreenshot){
		return thumbnail_image_tag($object);
	}
}

function thumbnail_image_tag(PluginScreenshot $object){
	$path = basename(sfConfig::get('sf_upload_dir')) . '/' . sfConfig::get('app_screenshots_path') . '/' . $object->getPluginId() . '/' . $object->getId();
	return image_tag('/' . $path . '/thumbs/' . $object->getFilename());
}

function url_for_screenshot($object){
	$path = basename(sfConfig::get('sf_upload_dir')) . '/' . sfConfig::get('app_screenshots_path') . '/' . $object->getPluginId() . '/' . $object->getId();
	return image_path('/' . $path . '/' . $object->getFilename());
}