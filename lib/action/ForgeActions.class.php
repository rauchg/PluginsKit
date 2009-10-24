<?php

/**
 * Base forge actions class
 *
 * @package mooforge
 * @subpackage action
 * @author Guillermo Rauch
 **/
class ForgeActions extends sfActions
{
	
	/**
	 * Return data as JSON
	 *
	 * @param object|array|string Data to encode, skips encoding for strings.
	 * @return int View
	 */
	public function renderJson($data)
	{
		if (!is_string($data)) {
			$data = json_encode($data);
		}

		$this->response->setHttpHeader('Content-type', 'application/json');
		$this->response->setContent($data);

		return sfView::NONE;
	}
	
} // END class ForgeActions extends sfActions